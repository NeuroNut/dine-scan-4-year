import warnings
warnings.filterwarnings('ignore')
from flask import Flask, render_template
import matplotlib.pyplot as plt
from io import BytesIO
import base64
import pandas as pd
import matplotlib.pyplot as plt

import mysql.connector

conn = mysql.connector.connect(user="root", password="", host="localhost", port=3306, database="dinescanmenu")

# SQL queries to fetch data from the three tables
orders_query = 'SELECT * FROM orders'
menu_items_query = 'SELECT * FROM menu_items'
order_items_query = 'SELECT * FROM order_items'

# Create Pandas DataFrames from the queried data
orders_df = pd.read_sql(orders_query, conn)
menu_items_df = pd.read_sql(menu_items_query, conn)
order_items_df = pd.read_sql(order_items_query, conn)

# Data analysis and visualization
# 1. Number of orders in each month
orders_df['order_date'] = pd.to_datetime(orders_df['order_date'])
orders_df['order_month'] = orders_df['order_date'].dt.month
monthly_order_count = orders_df['order_month'].value_counts().sort_index()

# 2. Number of orders by status
order_status_count = orders_df['status'].value_counts()

# 3. Total revenue by month
monthly_revenue = orders_df.groupby(orders_df['order_date'].dt.strftime('%Y-%m'))['total_amount'].sum()

# 4. Total revenue by table number
table_revenue = orders_df.groupby('table_number')['total_amount'].sum()

# 5. Most ordered menu items
most_ordered_items = order_items_df['menu_item_id'].value_counts().head(5)


app = Flask(__name__)

# ... (previous code)

@app.route('/')
def index():
    # ... (previous code)

    # Generate and save the five plots
    plots = []
    for i in range(1, 6):
        plt.figure()
        if i == 1:
            monthly_order_count.plot(kind='bar', title='Number of Orders per Month')
            plt.xlabel('Month')
            plt.ylabel('Number of Orders')
        elif i == 2:
            order_status_count.plot(kind='bar', title='Number of Orders by Status')
        elif i == 3:
            monthly_revenue.plot(kind='bar', title='Total Revenue per Month')
        elif i == 4:
            table_revenue.plot(kind='bar', title='Total Revenue by Table Number')
        elif i == 5:
            most_ordered_items.plot(kind='bar', title='Most Ordered Menu Items')

        # Save the plot to a BytesIO object
        img_buffer = BytesIO()
        plt.savefig(img_buffer, format='png')
        img_buffer.seek(0)
        img_data = base64.b64encode(img_buffer.read()).decode('utf-8')
        plots.append(img_data)

    # Close all plots
    plt.close('all')

    # Render the HTML template with the plots
    return render_template('index.html', plots=plots)

if __name__ == '__main__':
    app.run(debug=True)
