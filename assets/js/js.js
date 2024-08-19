document.addEventListener("DOMContentLoaded",function(){
    let tabs = document.querySelectorAll(".settings-tabs-list li");

    for(let i =0; i < tabs.length; i++){
        tabs[i].addEventListener("click", switchTab);

        function switchTab(event){

            event.preventDefault();

            document.querySelector(".settings-tabs-list li.active").classList.remove("active");
            document.querySelector(".settings-tab-content .tab-pane.active").classList.remove("active");

            let clickedTab = event.currentTarget;
            let anchor = event.target;
            let activePaneID = anchor.getAttribute("href");

            clickedTab.classList.add("active");
            document.querySelector(activePaneID).classList.add("active");
        }
    }



});

jQuery(document).ready(function($) {
    $('.my-color-field').wpColorPicker();
    $('.ui-color').wpColorPicker();
});


//Admin calendar

const monthNames = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"];
const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

document.addEventListener("DOMContentLoaded", function() {
    const monthYear = document.getElementById("admin-month-year");
    const daysContainer = document.getElementById("admin-days");

    if (!monthYear || !daysContainer) {
        // Skip calendar-related code if elements are missing
        return;
    }

    renderCalendar(currentMonth, currentYear);

    // Populate the table with saved dates
    if (typeof savedDates !== 'undefined' && savedDates.length > 0) {
        savedDates.forEach(date => {
            const [month, day, year] = date.split('/');
            addDateToTable(day, month, year);
        });
    }

    document.getElementById("admin-prev")?.addEventListener("click", function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar(currentMonth, currentYear);
    });

    document.getElementById("admin-next")?.addEventListener("click", function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
    });

    document.getElementById("admin-days")?.addEventListener("click", function(event) {
        if (event.target.classList.contains("admin-day")) {
            const day = event.target.textContent;
            const date = `${monthNames[currentMonth]} ${day}, ${currentYear}`;

            addDateToTable(day, currentMonth + 1, currentYear);
        }
    });

    document.getElementById("settingsForm")?.addEventListener("submit", function(e) {
        e.preventDefault();
        
        let settingsForm = document.getElementById("settingsForm");
        let settingsUrl = settingsForm.dataset.url;
    
        let data = new URLSearchParams(new FormData(settingsForm));
    
        fetch(settingsUrl, {
            method: 'POST',
            body: data,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(responseData => {
            if (responseData.success) {
                alert('Data saved successfully!');
            } else {
                console.log('No new data to save: ' + (responseData.data?.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Request failed: ' + error.message);
        });
    });
});

function renderCalendar(month, year) {
    const daysContainer = document.getElementById("admin-days");
    const monthYear = document.getElementById("admin-month-year");

    if (!monthYear || !daysContainer) {
        // Skip rendering if elements are missing
        return;
    }

    monthYear.textContent = `${monthNames[month]} ${year}`;

    daysContainer.innerHTML = ""; // Clear out previous days

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = 32 - new Date(year, month, 32).getDate();

    // Fill in the blank days for the first week of the month
    for (let i = 0; i < firstDay; i++) {
        const blankDiv = document.createElement("div");
        daysContainer.appendChild(blankDiv);
    }

    // Add the actual days of the month
    for (let i = 1; i <= daysInMonth; i++) {
        const day = document.createElement("div");
        day.textContent = i;
        day.classList.add("admin-day");

        if (i === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) {
            day.classList.add("admin-active");
        }

        daysContainer.appendChild(day);
    }
}

function addDateToTable(day, month, year) {
    const tableBody = document.querySelector("#selected-dates tbody");
    const formattedDate = formatToMMDDYYYY(month, day, year);
    const sanitizedDate = formattedDate.replace(/\//g, '_'); // Replace slashes with underscores
    const existingRow = Array.from(tableBody.rows).find(row => row.cells[0].textContent === formattedDate);

    if (!existingRow) {
        const newRow = tableBody.insertRow();
        const dateCell = newRow.insertCell(0);
        dateCell.classList.add("rqt-select-date");
        const actionCell = newRow.insertCell(1);

        dateCell.textContent = formattedDate;

        const removeButton = document.createElement("button");
        removeButton.textContent = "Remove";
        removeButton.classList.add("remove-btn");
        removeButton.addEventListener("click", function() {
            // Remove the row and the corresponding hidden input
            tableBody.deleteRow(newRow.rowIndex - 1);

            const hiddenInput = document.getElementById(`hidden-${sanitizedDate}`);
            if (hiddenInput) {
                hiddenInput.remove();
            }

            checkIfTableEmpty();
        });

        actionCell.appendChild(removeButton);

        // Hide "No dates selected" message if adding a date
        document.getElementById("no-dates-message").style.display = "none";

        // Create a hidden input with the formatted date
        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = `selected_dates[]`; // Array-like name for easy handling
        hiddenInput.id = `hidden-${sanitizedDate}`;
        hiddenInput.value = formattedDate;

        // Append the hidden input to the form
        document.getElementById("settingsForm").appendChild(hiddenInput);
    }

    checkIfTableEmpty();
}

function formatToMMDDYYYY(month, day, year) {
    month = month.toString().padStart(2, '0'); // Ensure month is two digits
    day = day.toString().padStart(2, '0'); // Ensure day is two digits
    return `${month}/${day}/${year}`;
}

function checkIfTableEmpty() {
    const tableBody = document.querySelector("#selected-dates tbody");
    const noDatesMessage = document.getElementById("no-dates-message");

    if (tableBody.rows.length === 0) {
        noDatesMessage.style.display = "table-row";
    } else {
        noDatesMessage.style.display = "none";
    }
}