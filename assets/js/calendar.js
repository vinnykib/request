document.addEventListener("DOMContentLoaded", function () {
  const calendar = document.getElementById("calendar");
  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  function generateCalendar(year, month) {
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const currMonth = month + 1;
    const nextMonthYear = new Date(year, month + 1, 1).getFullYear(); // get previous month year
    const lastMonthYear = new Date(year, month, 0).getFullYear(); // get previous month

    if (calendar) {
      // Clear the previous calendar content
      calendar.innerHTML = "";

      // Create table for days
      const table = document.createElement("table");

      // Create a row for the calendar header
      const headerRow = document.createElement("tr");
      headerRow.classList.add("calendar-header");
      const headerCell = document.createElement("th");
      headerCell.setAttribute("colspan", 7); // Span across all 7 columns

      // Create the inner content of the header (navigation and current date)
      headerCell.innerHTML = `
            <span id="prevMonth" class="material-symbols-rounded"><</span>
            <span class="current-date">${months[month]} ${year}</span>
            <span id="nextMonth" class="material-symbols-rounded">></span>
        `;
      headerRow.appendChild(headerCell);
      table.appendChild(headerRow);

      // Create day labels (headers)
      const dayLabels = document.createElement("tr");
      dayLabels.classList.add("week-header");
      const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
      for (const dayName of dayNames) {
        const dayLabel = document.createElement("th");
        dayLabel.textContent = dayName;
        dayLabels.appendChild(dayLabel);
      }
      table.appendChild(dayLabels);

      // Calculate the number of days to display from the previous and next months
      const daysFromPrevMonth = firstDay.getDay(); // Days to display from the previous month
      const daysFromNextMonth = 6 * 7 - daysFromPrevMonth - daysInMonth; // Days to display from the next month

      // Create days
      let dayCount = 1;

      for (let i = 0; i < 6; i++) {
        const row = document.createElement("tr");
        row.classList.add("week");
        let weekHasDays = false; // Flag to check if the week has any days

        for (let j = 0; j < 7; j++) {
          const cell = document.createElement("td");
          cell.classList.add("day");

          // Determine whether to display a day from the previous, current, or next month
          if (i === 0 && j < daysFromPrevMonth) {
            // Days from the previous month
            const prevMonthDay =
              new Date(year, month, 0).getDate() - (daysFromPrevMonth - j) + 1;
            const lastMonth = new Date(year, month, 0).getMonth() + 1; // get previous month
            // cell.textContent = prevMonthDay;
            cell.innerHTML = `
                        <span class="date-day">${prevMonthDay}</span>
                    `;
            cell.setAttribute(
              "data-date",
              lastMonth + "-" + prevMonthDay + "-" + lastMonthYear
            );
            cell.classList.add("prevMonth");
          } else if (dayCount <= daysInMonth) {
            // Days from the current month
            // cell.textContent = dayCount;
            cell.innerHTML = `
                        <span class="date-day">${dayCount}</span>
                    `;
            cell.setAttribute(
              "data-date",
              currMonth + "-" + dayCount + "-" + year
            );

            // Highlight today's date
            const today = new Date();
            if (
              year === today.getFullYear() &&
              month === today.getMonth() &&
              dayCount === today.getDate()
            ) {
              cell.classList.add("today");
            }

            dayCount++;
            weekHasDays = true;
          } else {
            // Days from the next month
            const nextMonthDay = dayCount - daysInMonth;
            const nextMonth = new Date(year, month + 1, 1).getMonth() + 1; // get next month
            // cell.textContent = nextMonthDay;
            cell.innerHTML = `
                        <span class="date-day">${nextMonthDay}</span>
                    `;
            cell.setAttribute(
              "data-date",
              nextMonth + "-" + nextMonthDay + "-" + nextMonthYear
            );
            cell.classList.add("nextMonth");
            dayCount++;
          }

          row.appendChild(cell);
        }

        // Add the row only if it has days
        if (weekHasDays) {
          table.appendChild(row);
        }
      }

      calendar.appendChild(table);
      setupClickEvents("week td", "request_date");
    }
  }

  const currentDate = new Date();
  generateCalendar(currentDate.getFullYear(), currentDate.getMonth());

  // Add event listeners for navigating months
  document.addEventListener("click", function (event) {
    if (event.target.id === "prevMonth") {
      currentDate.setMonth(currentDate.getMonth() - 1);
      generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    } else if (event.target.id === "nextMonth") {
      currentDate.setMonth(currentDate.getMonth() + 1);
      generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    }
  });
});

function setupClickEvents(className, inputId) {
  const timePicker = document.createElement("tr");
  timePicker.classList.add("time-picker");
  timePicker.innerHTML = `
    <td colspan="7">
    <div id="time-wrapper">
        <input type="hidden" id="form-hidden-date" name="hidden-date">
        <div>
            <label for="rqt">Start:</label>
            <input type="time" id="form-start-time" name="rqt_start_time">
        </div>

        <div>
            <label for="rqt">End:</label>
            <input type="time" id="form-end-time" name="rqt_end_time">
        </div>

        <div>
        <!-- Trigger/Open The Modal -->
            <button type="button" id="select-time">Make Request</button>
        </div>
    </div>
    </td>
            `;

  // Step 1: Get the list of elements by class name
  let items = document.querySelectorAll("." + className);

  // Step 2: Add a click event listener to each element
  for (let i = 0; i < items.length; i++) {
    items[i].addEventListener("click", function () {
      //   Step 3: Remove selected class from all elements
      for (let j = 0; j < items.length; j++) {
        items[j].classList.remove("selected");
        items[j].parentNode.classList.remove("selected");
      }

      // Step 4: Add selected class to the clicked element
      this.classList.add("selected");
      this.parentElement.classList.add("selected");

      let week = document.querySelector(".week.selected");

      if (week) {
        week.parentElement.insertBefore(timePicker, week.nextSibling);

        let timeWrapper = document.querySelector(".time-picker");
        timeWrapper.style.display = "table-row";

        let day = document.querySelector(".day.selected");
        if (day) {
          day.addEventListener("click", function (event) {
            // Toggle the visibility of the element with id "time-wrapper"

            if (timeWrapper.style.display === "table-row") {
              timeWrapper.style.display = "none";
              this.classList.remove("selected");
            } else {
              timeWrapper.style.display = "table-row";
              this.classList.add("selected");
            }
          });
        }

        // Get the modal
        let modal = document.getElementById("rqt-modal");

        // Get the button that opens the modal
        let btn = document.getElementById("select-time");

        // Get the <span> element that closes the modal
        let span = document.getElementsByClassName("close")[0];

        btn.onclick = function () {
          // Get the start and end time input values
          const startTime = document.getElementById("form-start-time").value;
          const endTime = document.getElementById("form-end-time").value;
        
          // Check if both start and end times are filled in
          if (!startTime || !endTime) {
            alert("Please select both start and end times before proceeding.");
          } else if (new Date(`1970-01-01T${endTime}`) <= new Date(`1970-01-01T${startTime}`)) {
            // Ensure end time is greater than start time
            alert("End time must be later than start time.");
          } else {
            // If validation passes, display the modal
            modal.style.display = "block";
          }
        };
        
        

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
          modal.style.display = "none";
        };

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        };

        //Ajax

        let addRequestForm = document.getElementById("addRequestForm");

        addRequestForm.addEventListener("submit", (e) => {
          e.preventDefault();

          // Reset the form messages
          resetMessages();

          // Collect all the data
          let data = {
            name: addRequestForm.querySelector('[name="rqt-name"]').value,
            email: addRequestForm.querySelector('[name="rqt-email"]').value,
            phone: addRequestForm.querySelector('[name="phone"]').value,
            description: addRequestForm.querySelector('[name="description"]')
              .value,
            rqt_start_time: addRequestForm.querySelector(
              '[name="rqt_start_time"]'
            ).value,
            rqt_end_time: addRequestForm.querySelector('[name="rqt_end_time"]')
              .value,
            request_date: addRequestForm.querySelector('[name="request_date"]')
              .value,
            time_hrs: addRequestForm.querySelector('[name="request_hrs"]')
              .value,
            time_mins: addRequestForm.querySelector('[name="request_mins"]')
              .value,
          };

          // Validate everything

          ////////
          const isRequiredState = calendarSettings.disableFields;
          ////////

          if (!data.name) {
            addRequestForm
              .querySelector('[data-error="invalidName"]')
              .classList.add("show");
            return;
          }

          if (!validateEmail(data.email)) {
            addRequestForm
              .querySelector('[data-error="invalidEmail"]')
              .classList.add("show");
            return;
          }

          if (!data.phone && isRequiredState.is_phone_required) {
            addRequestForm
              .querySelector('[data-error="invalidPhone"]')
              .classList.add("show");
            return;
          }

          if (!data.description && isRequiredState.is_description_required) {
            addRequestForm
              .querySelector('[data-error="invalidMessage"]')
              .classList.add("show");
            return;
          }

          // Ajax HTTP POST request
          let url = addRequestForm.dataset.url;
          let params = new URLSearchParams(new FormData(addRequestForm));

          addRequestForm
            .querySelector(".js-form-submission")
            .classList.add("show");

          fetch(url, {
            method: "POST",
            body: params,
          })
            .then((res) => res.json())
            .then((response) => {
              resetMessages();

              if (response === 0 || response.status === "error") {
                addRequestForm
                  .querySelector(".js-form-error")
                  .classList.add("show");
                return;
              }

              addRequestForm
                .querySelector(".js-form-success")
                .classList.add("show");
              addRequestForm.reset();

              if (response.data && response.data.redirect_url) {
                window.location.href = response.data.redirect_url;
              } else {
                if (response.data.message) {
                  console.log(response.data.message);
                }
                let modal = document.getElementById("rqt-modal");

                // Add the fade-out class to trigger the animation
                modal.classList.add("fade-out");

                // Set a delay before hiding the modal to match the animation duration
                setTimeout(function () {
                  modal.style.display = "none";
                  modal.classList.remove("fade-out"); // Remove the class for next time
                }, 2000); // Duration should match the animation duration (0.5s)
                console.log("No redirect URL provided");
              }
            })
            .catch((error) => {
              resetMessages();
              addRequestForm
                .querySelector(".js-form-error")
                .classList.add("show");
            });
        });

        function resetMessages() {
          document
            .querySelectorAll(".field-msg")
            .forEach((f) => f.classList.remove("show"));
        }

        function validateEmail(email) {
          let re =
            /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return re.test(String(email).toLowerCase());
        }
      }
      // Get date on click
      let selectedDateStr = this.dataset.date;
     
      let parts = selectedDateStr.split("-");
      if (parts.length === 3) {
        // Assuming the format is mm-dd-yyyy
        let month = parseInt(parts[0], 10);
        let day = parseInt(parts[1], 10);
        let year = parseInt(parts[2], 10);

        // Create a new Date object
        let selectedDate = new Date(year, month - 1, day);

        let options = {
          weekday: "long",
          year: "numeric",
          month: "long",
          day: "numeric",
        };
        let formattedDate = selectedDate.toLocaleDateString("en-US", options);

        let dateContentInput = document.getElementById("request_date");
        if (dateContentInput) {
          dateContentInput.value = formattedDate;
          console.log(formattedDate); // Logs the formatted date string

          //Pass Date to modal form
          let modalDate = document.getElementById("modal-date");
          modalDate.innerHTML = "Date: " + formattedDate;
        } else {
          console.log("Input element not found");
        }
      } else {
        console.log("Invalid date format");
      }

      function addTime() {
        // Get time on click

        // Set the selected time to the input field
        let selectedStartTime =
          document.getElementById("form-start-time").value;
        let formStartTime = document.getElementById("start-time");
        formStartTime.value = selectedStartTime;
        console.log(selectedStartTime);

        let selectedEndTime = document.getElementById("form-end-time").value;
        let formEndTime = document.getElementById("end-time");
        formEndTime.value = selectedEndTime;
        console.log(selectedEndTime);

        // if (!selectedStartTime || !selectedEndTime) {
        //   alert("Please select a start and end time.");
        //   return; // Stop further execution if start time is not selected
        // }

        // Pass time to modal form
        let modalTime = document.getElementById("modal-time");
        modalTime.innerHTML = selectedStartTime + " - " + selectedEndTime;

        // Calculate time range
        function calculateTimeRange(startTime, endTime) {
          const start = new Date(`1970-01-01T${startTime}:00`);
          const end = new Date(`1970-01-01T${endTime}:00`);
          const diff = end - start;
          const hours = Math.floor(diff / (1000 * 60 * 60));
          const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
          return { hours, minutes };
        }

        const timeRange = calculateTimeRange(
          selectedStartTime,
          selectedEndTime
        );
        console.log(`Hours: ${timeRange.hours}, Minutes: ${timeRange.minutes}`);

        // Pass time in hours and minutes to hidden form fields
        let timeHours = document.getElementById("request_hrs");
        timeHours.value = timeRange.hours;
        let timeMins = document.getElementById("request_mins");
        timeMins.value = timeRange.minutes;

        let timeHoursPrice = document.getElementById("request_hrs_price");
        timeHoursPrice.value = timeRange.hours;
        let timeMinsPrice = document.getElementById("request_mins_price");
        timeMinsPrice.value = timeRange.minutes;

        // Display the time range in the modal (optional)
        let modalTimeRange = document.getElementById("modal-time-range");
        if (modalTimeRange) {
          modalTimeRange.innerHTML = `Time Range: ${timeRange.hours} hours and ${timeRange.minutes} minutes`;
        }

        // Send the data via Fetch API
        let priceRequestForm = document.getElementById("priceRequestForm");
        let ajaxurl = priceRequestForm.dataset.url;
        let priceRequestNonce = document.getElementById(
          "price-request-nonce"
        ).value;

        fetch(ajaxurl, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams({
            action: "price_request", // Add the action parameter
            request_hrs: timeHours.value, // Ensure field names match
            request_mins: timeMins.value,
            nonce: priceRequestNonce, // Include the nonce if required
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.error) {
              console.error("Error:", data.message);
            } else {
              // Handle successful response
              // Update your UI here
              if (data.data.price) {
                document.getElementById("modal-price").textContent =
                  "Price: " + data.data.currency + " " + data.data.price;
              }
            }
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }

      document.getElementById("select-time").addEventListener("click", addTime);



    ////////////////

 
    let startTimeInput = document.getElementById('form-start-time');
    let endTimeInput = document.getElementById('form-end-time');
    let bufferTimeSet = calendarSettings.bufferTime;
    let bufferHours = Number(bufferTimeSet.allowed_buffer_time); // Define the buffer time in hours
    
    // Get the allowed time range from settings
    const startTimeSet = calendarSettings.disableTime;
    let minTime = startTimeSet.start_time;
    const endTimeSet = calendarSettings.disableTime;
    let maxTime = endTimeSet.end_time;
    
    // Function to set the minimum time based on buffer for today
    function setMinTime() {
        let currentDate = new Date();
        let today = currentDate.toLocaleDateString('en-CA'); // Format as 'yyyy-mm-dd'
        let timeInputDate = document.getElementById('modal-date'); // Date selected in the modal
        let dateString = timeInputDate.textContent || timeInputDate.innerText;
        let parsedDate = new Date(dateString);
        let selectedDate = parsedDate.toLocaleDateString('en-CA'); // Selected date in 'yyyy-mm-dd'
    
        // If the selected date is today, apply buffer logic and disallow past time
        if (selectedDate === today) {
            let minDateTime = new Date(currentDate);
            minDateTime.setHours(minDateTime.getHours() + bufferHours); // Add buffer hours
    
            let minHours = minDateTime.getHours().toString().padStart(2, '0');
            let minMinutes = minDateTime.getMinutes().toString().padStart(2, '0');
            let minTimeWithBuffer = `${minHours}:${minMinutes}`;
    
            // Set the min attribute only for today's date
            startTimeInput.setAttribute('buffer', minTimeWithBuffer);
            console.log("Min Time With Buffer Set:", minTimeWithBuffer);
        } else {
            // For future dates, set the default min time and no buffer
            startTimeInput.setAttribute('min', minTime);
        }
    }
    
    // Validate the start and end times based on both buffer and allowed time range
    function validateTimeInput(inputElement) {
        let currentDate = new Date();
        let today = currentDate.toLocaleDateString('en-CA');
        let selectedDate = new Date(document.getElementById('modal-date').textContent || document.getElementById('modal-date').innerText).toLocaleDateString('en-CA');
    
        // Get the minimum time (adjust for buffer if today)
        let minAllowedTime = inputElement.getAttribute('min');
        let maxAllowedTime = inputElement.getAttribute('max'); // Get the maximum allowed time

         // Get the buffer time (adjust for buffer if today)
         let alowedBufferTime = inputElement.getAttribute('buffer');
    
        // Single alert for both conditions
        if (selectedDate === today && inputElement.value < alowedBufferTime) {
            alert(`Please select a time after the buffer period: ${formatTime(alowedBufferTime)}.`);
            inputElement.value = ''; // Clear the invalid input
        } else if (inputElement.value < minTime || inputElement.value > maxAllowedTime) {
            alert(`Please select a time between ${formatTime(minTime)} and ${formatTime(maxAllowedTime)}.`);
            inputElement.value = ''; // Clear the invalid input
        }
    }
    
    // Format time to 12-hour format for readability
    function formatTime(time) {
        let [hours, minutes] = time.split(':');
        let suffix = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12; // Convert 24-hour format to 12-hour
        return `${hours}:${minutes} ${suffix}`;
    }
    
    // Set the initial min and max time attributes based on allowed range
    function setMinMaxTimes() {
        startTimeInput.setAttribute('min', minTime);
        startTimeInput.setAttribute('max', maxTime);
        endTimeInput.setAttribute('min', minTime);
        endTimeInput.setAttribute('max', maxTime);
    }
    
    // Initial calls to set minimum time with buffer and min/max times
    setMinTime();
    setMinMaxTimes();
    
    // Event listeners to validate time inputs
    startTimeInput.addEventListener('change', function() {
        validateTimeInput(startTimeInput);
    });
    
    endTimeInput.addEventListener('change', function() {
        validateTimeInput(endTimeInput);
    });
    

      ///////////

      
    });
  }
  


let phoneField = document.getElementById("setting_phone_field");
let descriptionField = document.getElementById("setting_description_field");

let phone = document.getElementById("front_phone_field_div");
let phoneInput = document.getElementById("front_phone_field");

let description = document.getElementById("front_description_field_div");
let descriptionTextArea = document.getElementById("front_description_field");

const fieldsCheckboxState = calendarSettings.disableFields;

// Handle phone field visibility and requirement
if (fieldsCheckboxState.setting_phone_field) {
  phone.classList.add("hideField");
  // Remove required functionality if hidden
  phoneInput.classList.remove("requiredField");
} else {
  phone.classList.remove("hideField");
  if (fieldsCheckboxState.is_phone_required) {
    phoneInput.classList.add("requiredField");
  } else {
    phoneInput.classList.remove("requiredField");
  }
}

// Handle description field visibility and requirement
if (fieldsCheckboxState.setting_description_field) {
  description.classList.add("hideField");
  // Remove required functionality if hidden
  descriptionTextArea.classList.remove("requiredField");
} else {
  description.classList.remove("hideField");
  if (fieldsCheckboxState.is_description_required) {
    descriptionTextArea.classList.add("requiredField");
  } else {
    descriptionTextArea.classList.remove("requiredField");
  }
}



 //Code to disable days in calendar

  function handleCalendarAndSettings() {
    const dayNames = [
      "sunday",
      "monday",
      "tuesday",
      "wednesday",
      "thursday",
      "friday",
      "saturday",
    ];
    const isSettingsPage = document.querySelectorAll("input.day-settings").length > 0;
    const isCalendarPage = document.querySelectorAll(".day").length > 0;

    function applyCalendarLogic() {
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      const checkboxStates = calendarSettings.disableDays;

      let startDate, endDate;
      if (checkboxStates.start_day) {
        startDate = new Date(checkboxStates.start_day);
        startDate.setHours(0, 0, 0, 0);
      }
      if (checkboxStates.end_day) {
        endDate = new Date(checkboxStates.end_day);
        endDate.setHours(23, 59, 59, 999);
      }
  

      // Ensure savedDates is an array; default to an empty array if not
      const disabledDates = Array.isArray(savedDates)
        ? savedDates.map((dateString) => {
            const [month, day, year] = dateString.split("/").map(Number);
            const date = new Date(year, month - 1, day);
            date.setHours(0, 0, 0, 0);
            // console.log(`Parsed disabled date: ${date.toISOString()}`); // Debugging line
            return date;
          })
        : [];

      document.querySelectorAll(".day").forEach((cell) => {
        const cellDate = cell.getAttribute("data-date");
        const [month, day, year] = cellDate.split("-").map(Number);
        const cellDateObj = new Date(year, month - 1, day);
        cellDateObj.setHours(0, 0, 0, 0); // Normalize to midnight local time

        // console.log(`Processing cell date: ${cellDateObj.toISOString()}`); // Debugging line

        const dayOfWeek = cellDateObj.getDay();

        let disable = false;

        if (cellDateObj < today) {
          disable = true;
        }

        if (startDate && !endDate) {
          if (cellDateObj < startDate) {
            disable = true;
          }
        } else if (!startDate && endDate) {
          if (cellDateObj > endDate) {
            disable = true;
          }
        } else if (startDate && endDate) {
          if (cellDateObj < startDate || cellDateObj > endDate) {
            disable = true;
          }
        }

        if (checkboxStates[dayNames[dayOfWeek]]) {
          disable = true;
        }

        if (
          disabledDates.some(
            (disabledDate) => disabledDate.getTime() === cellDateObj.getTime()
          )
        ) {
          // console.log(`Disabling date: ${cellDateObj.toISOString()}`); // Debugging line
          disable = true;
        }

        if (disable) {
          cell.classList.add("disabled");
        } else {
          cell.classList.remove("disabled");
        }
      });
    }

    if (isSettingsPage) {
      // Settings page logic (no changes needed here)
    } else if (isCalendarPage) {
      applyCalendarLogic();

      const calendarContainer = document.querySelector("#calendar");
      if (calendarContainer) {
        const observer = new MutationObserver(() => {
          applyCalendarLogic();
        });

        observer.observe(calendarContainer, { childList: true, subtree: true });
      } else {
        console.error(
          "Calendar container not found. Please check the selector."
        );
      }
    }
  }

  // Initialize the function
  handleCalendarAndSettings();
}

//Profile tabs js
document.addEventListener("DOMContentLoaded", function () {
  let tabs = document.querySelectorAll(".profile-tabs-list li");

  for (let i = 0; i < tabs.length; i++) {
    tabs[i].addEventListener("click", switchTab);

    function switchTab(event) {
      event.preventDefault();

      document
        .querySelector(".profile-tabs-list li.active")
        .classList.remove("active");
      document
        .querySelector(".profile-tab-content .tab-pane.active")
        .classList.remove("active");

      let clickedTab = event.currentTarget;
      let anchor = event.target;
      let activePaneID = anchor.getAttribute("href");

      clickedTab.classList.add("active");
      document.querySelector(activePaneID).classList.add("active");
    }
  }
});


