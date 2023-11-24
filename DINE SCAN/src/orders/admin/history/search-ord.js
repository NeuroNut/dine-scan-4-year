const ordersList = document.querySelector("#orders-list");
const prevPageBtn = document.querySelector("#prev-page-btn");
const nextPageBtn = document.querySelector("#next-page-btn");
const pageInfo = document.querySelector("#page-info");
const searchInput = document.querySelector("#search-input");
const searchInputd = document.querySelector("#search-input-d");
const ordersPerPageSelect = document.querySelector("#orders-per-page-select"); // Add this line

searchInput.addEventListener("input", loadorders);
searchInputd.addEventListener("input", loadorders);
ordersPerPageSelect.addEventListener("change", updateOrdersPerPage); // Add this line

let ord = 'desc';
let currentPage = 1;
let ordersPerPage = parseInt(ordersPerPageSelect.value); // Add this line
let ordersData = [];

// Load orders data on page load
loadorders();

// Event listeners for pagination buttons
prevPageBtn.addEventListener("click", goToPrevPage);
nextPageBtn.addEventListener("click", goToNextPage);

function loadorders() {
    const searchValue = searchInput.value.trim();
    const searchValued = searchInputd.value.trim();
    
  fetch(`search-ord.php?search=${encodeURIComponent(searchValue)}&searchd=${encodeURIComponent(searchValued)}`)
    .then(response => response.json())
    .then(data => {
      ordersData = data;
      updateordersTable();
    })
    .catch(error => {
      console.error(error);
      ordersList.innerHTML = "<tr><td colspan='4'>Failed to load orders data</td></tr>";
    });
   
}

function updateordersTable() {
  const startIndex = (currentPage - 1) * ordersPerPage;
  const endIndex = startIndex + ordersPerPage;
  const displayedorders = ordersData.slice(startIndex, endIndex);

  ordersList.innerHTML = "";

  if(ord === "desc"){
  displayedorders.sort((a, b) => new Date(b.order_date) - new Date(a.order_date));
  }
  else{
    displayedorders.sort((a, b) => new Date(a.order_date) - new Date(b.order_date));
  }

  displayedorders.forEach(order => {
    // Create table rows and populate data
    const tr = document.createElement("tr");
    tr.innerHTML = `
        <td>${order.order_id}</td>
        <td>${order.table_number}</td>
        <td>${order.total_amount}</td>
        <td>${order.mobileNumber}</td>
        <td class="date" style="white-space: nowrap;">${order.order_date}</td>
        <td>${order.status}</td>
        <td>${order.instructions}</td>
        <td>${order.payment_mode}</td>
        <td >${order.payment_id}</td>
          <td> <button class="button" type = "submit" onclick="viewDetails(${order.order_id})" >View Details</button></td>
          <td> <button class="button" type = "submit" onclick="openDelForm(${order.order_id})" >Delete</button></td>
        `;
    ordersList.appendChild(tr);
  });

  updatePageInfo();
}

function updatePageInfo() {
  const totalPages = Math.ceil(ordersData.length / ordersPerPage);
  pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;

  prevPageBtn.disabled = currentPage === 1;
  nextPageBtn.disabled = currentPage === totalPages;
}

function goToPrevPage() {
  if (currentPage > 1) {
    currentPage--;
    updateordersTable();
  }
}

function goToNextPage() {
  const totalPages = Math.ceil(ordersData.length / ordersPerPage);
  if (currentPage < totalPages) {
    currentPage++;
    updateordersTable();
  }
}

function sortTableByDate(order) {
    ord = order;
    loadorders();
}

function formatTime(time) {
  const [hours, minutes] = time.split(":");
  let formattedHours = parseInt(hours);
  let period = "AM";

  if (formattedHours >= 12) {
    period = "PM";
    if (formattedHours > 12) {
      formattedHours -= 12;
    }
  }

  return `${formattedHours}:${minutes} ${period}`;
}

function updateOrdersPerPage() {
  ordersPerPage = parseInt(ordersPerPageSelect.value);
  updateordersTable();
}
