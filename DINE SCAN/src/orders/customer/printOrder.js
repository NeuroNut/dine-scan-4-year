
    const urlParams = new URLSearchParams(window.location.search);
    const mobileNumber = urlParams.get('mobileNumber');


function printOrderSummary(order) {
    pdfMake.fonts = {
      Roboto: {
        normal: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/fonts/Roboto/Roboto-Regular.ttf',
        bold: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/fonts/Roboto/Roboto-Medium.ttf'
      }
    };

    const docDefinition = {
      content: [
        { text: "Order Summary", style: "header" },
        { text: "Order Number: #" + order.order_id },
        { text: "Amount: ₹" + order.total_amount },
        { text: "Date & Time: " + order.order_date },
        { text: "Transaction ID: " + order.payment_id },
        { text: "Customer Mobile Number: " + mobileNumber },
        { text: "Table No: " + order.table_number },
        { text: "Instructions: " + order.instructions },
        { text: "Order Items", style: "subheader" },
        {
          table: {
            body: [
              ["Food Item", "Size", "Quantity"]

              // Add more rows for each order item
            ]
          }
        }
      ],
      
      styles: {
        header: {
          fontSize: 18,
          bold: true,
          margin: [0, 0, 0, 10]
        },
        subheader: {
          fontSize: 14,
          bold: true,
          margin: [0, 10, 0, 5]
        }
      }
    };

    // Add order items to the table
    for (let item of order.items) {
      docDefinition.content[9].table.body.push([item.name, item.size_name, item.quantity]);
    }

    // Generate the PDF and trigger the download
    pdfMake.createPdf(docDefinition).download(`order_summary_${order.order_id}.pdf`);
  }
