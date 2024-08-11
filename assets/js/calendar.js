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
      calendar.innerHTML = "";

      const monthHeader = document.createElement("div");
      monthHeader.classList.add("month");
      calendar.appendChild(monthHeader);

      monthYear = months[month] + " " + year;
      document.querySelector(".current-date").innerHTML = monthYear;

      // Create table for days
      const table = document.createElement("table");

      // Create day labels (headers)
      const dayLabels = document.createElement("tr");
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
            const prevMonthDay = new Date(year, month, 0).getDate() - (daysFromPrevMonth - j) + 1;
            const lastMonth = new Date(year, month, 0).getMonth() + 1; // get previous month
            // get previous month
            cell.textContent = prevMonthDay;
            cell.setAttribute(
              "data-date",
              lastMonth + "-" + prevMonthDay + "-" + lastMonthYear
            );
            cell.classList.add("prevMonth");
          } else if (dayCount <= daysInMonth) {
            // Days from the current month
            cell.textContent = dayCount;
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
            cell.textContent = nextMonthDay;
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
  let prevMonth = document.getElementById("prevMonth");

  if (prevMonth) {
    prevMonth.addEventListener("click", function () {
      currentDate.setMonth(currentDate.getMonth() - 1);
      generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    });
  }

  let nextMonth = document.getElementById("nextMonth");
  if (nextMonth) {
    nextMonth.addEventListener("click", function () {
      currentDate.setMonth(currentDate.getMonth() + 1);
      generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    });
  }
});

function setupClickEvents(className, inputId) {
  const timePicker = document.createElement("tr");
  timePicker.classList.add("time-picker");
  timePicker.innerHTML = `
    <td colspan="7">
    <div id="time-wrapper">
        <div>
            <label for="rqt">Start:</label>
            <input type="time" id="form-start-time" name="rqt_start_time" value="09:00">
        </div>

        <div>
            <label for="rqt">End:</label>
            <input type="time" id="form-end-time" name="rqt_end_time" value="10:00">
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

        let timeWrapper = document.getElementById('time-wrapper');
        timeWrapper.style.display = 'block';

        let day = document.querySelector(".day.selected");
        if (day) {
        day.addEventListener('click', function(event) {

                  // Toggle the visibility of the element with id "time-wrapper"
                  
                  if (timeWrapper.style.display === 'block') {
                      timeWrapper.style.display = 'none';
                  } else {
                      timeWrapper.style.display = 'block';
                  }           
      });
    }
  

        // Get the modal
        var modal = document.getElementById("rqt-modal");

        // Get the button that opens the modal
        var btn = document.getElementById("select-time");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal
        btn.onclick = function () {
          modal.style.display = "block";
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

        // let addRequestForm = document.getElementById('addRequestForm');

        // addRequestForm.addEventListener('submit', (e) => {
        //     e.preventDefault();
            
        //     // Reset the form messages
        //     resetMessages();
            
        //     // Collect all the data
        //     let data = {
        //         name: addRequestForm.querySelector('[name="rqt-name"]').value,
        //         email: addRequestForm.querySelector('[name="rqt-email"]').value,
        //         phone: addRequestForm.querySelector('[name="phone"]').value,
        //         description: addRequestForm.querySelector('[name="description"]').value,
        //         rqt_start_time: addRequestForm.querySelector('[name="rqt_start_time"]').value,
        //         rqt_end_time: addRequestForm.querySelector('[name="rqt_end_time"]').value,
        //         request_date: addRequestForm.querySelector('[name="request_date"]').value,
        //         time_hrs: addRequestForm.querySelector('[name="request_hrs"]').value,
        //         time_mins: addRequestForm.querySelector('[name="request_mins"]').value,
        //     };
            
        //     // Validate everything
        //     if (!data.name) {
        //         addRequestForm.querySelector('[data-error="invalidName"]').classList.add('show');
        //         return;
        //     }
        
        //     if (!validateEmail(data.email)) {
        //         addRequestForm.querySelector('[data-error="invalidEmail"]').classList.add('show');
        //         return;
        //     }
        
        //     if (!data.description) {
        //         addRequestForm.querySelector('[data-error="invalidMessage"]').classList.add('show');
        //         return;
        //     }
        
        //     // Ajax HTTP POST request
        //     let url = addRequestForm.dataset.url;
        //     let params = new URLSearchParams(new FormData(addRequestForm));
        
        //     addRequestForm.querySelector('.js-form-submission').classList.add('show');
        
        //     fetch(url, {
        //         method: 'POST',
        //         body: params,
        //     })
        //     .then((res) => res.json())
        //     .then((response) => {
        //         resetMessages();
                
        //         if (response === 0 || response.status === 'error') {
        //             addRequestForm.querySelector('.js-form-error').classList.add('show');
        //             return;
        //         }
        
        //         addRequestForm.querySelector('.js-form-success').classList.add('show');
        //         addRequestForm.reset();
        
        //         if (response.data && response.data.redirect_url) {
        //             window.location.href = response.data.redirect_url;
        //         } else {
        //             console.log("No redirect URL provided");
        //         }
        //     })
        //     .catch((error) => {
        //         resetMessages();
        //         addRequestForm.querySelector('.js-form-error').classList.add('show');
        //     });
        // });

        let addRequestForm = document.getElementById('addRequestForm');

        addRequestForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Reset the form messages
            resetMessages();
            
            // Collect all the data
            let data = {
                name: addRequestForm.querySelector('[name="rqt-name"]').value,
                email: addRequestForm.querySelector('[name="rqt-email"]').value,
                phone: addRequestForm.querySelector('[name="phone"]').value,
                description: addRequestForm.querySelector('[name="description"]').value,
                rqt_start_time: addRequestForm.querySelector('[name="rqt_start_time"]').value,
                rqt_end_time: addRequestForm.querySelector('[name="rqt_end_time"]').value,
                request_date: addRequestForm.querySelector('[name="request_date"]').value,
                time_hrs: addRequestForm.querySelector('[name="request_hrs"]').value,
                time_mins: addRequestForm.querySelector('[name="request_mins"]').value,
            };
            
            // Validate everything
            if (!data.name) {
                addRequestForm.querySelector('[data-error="invalidName"]').classList.add('show');
                return;
            }
        
            if (!validateEmail(data.email)) {
                addRequestForm.querySelector('[data-error="invalidEmail"]').classList.add('show');
                return;
            }
        
            if (!data.description) {
                addRequestForm.querySelector('[data-error="invalidMessage"]').classList.add('show');
                return;
            }
        
            // Ajax HTTP POST request
            let url = addRequestForm.dataset.url;
            let params = new URLSearchParams(new FormData(addRequestForm));
        
            addRequestForm.querySelector('.js-form-submission').classList.add('show');
        
            fetch(url, {
                method: 'POST',
                body: params,
            })
            .then((res) => res.json())
            .then((response) => {
                resetMessages();
                
                if (response === 0 || response.status === 'error') {
                    addRequestForm.querySelector('.js-form-error').classList.add('show');
                    return;
                }
        
                addRequestForm.querySelector('.js-form-success').classList.add('show');
                addRequestForm.reset();
        
                if (response.data && response.data.redirect_url) {
                    window.location.href = response.data.redirect_url;
                } else {
                  if (response.data.message) {
                    console.log(response.data.message);
                } 
                    console.log("No redirect URL provided");
                }
            })
            .catch((error) => {
                resetMessages();
                addRequestForm.querySelector('.js-form-error').classList.add('show');
            });
        });
        
        function resetMessages() {
            document.querySelectorAll('.field-msg').forEach((f) => f.classList.remove('show'));
        }
        
        function validateEmail(email) {
            let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
        


      }
      // Get date on click
      let selectedDateStr = this.dataset.date;
      console.log(selectedDateStr);

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
          console.log(formattedDate);  // Logs the formatted date string

          //Pass Date to modal form
        let modalDate = document.getElementById("modal-date");
        modalDate.innerHTML = formattedDate; 

        } else {
          console.log("Input element not found");
        }
      } else {
        console.log("Invalid date format");
      }

      function addTime() {
        // Get time on click
    
        // Set the selected time to the input field
        let selectedStartTime = document.getElementById("form-start-time").value;
        let formStartTime = document.getElementById("start-time");
        formStartTime.value = selectedStartTime;
        console.log(selectedStartTime);
    
        let selectedEndTime = document.getElementById("form-end-time").value;
        let formEndTime = document.getElementById("end-time");
        formEndTime.value = selectedEndTime;
        console.log(selectedEndTime);
    
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
    
        const timeRange = calculateTimeRange(selectedStartTime, selectedEndTime);
        console.log(`Hours: ${timeRange.hours}, Minutes: ${timeRange.minutes}`);

        // Pass time in hours and minutes to hdden form fields 
        let timeHours = document.getElementById("request_hrs");
        timeHours.value = timeRange.hours;
        let timeMins = document.getElementById("request_mins");
        timeMins.value = timeRange.minutes;
        
        // Display the time range in the modal (optional)
        let modalTimeRange = document.getElementById("modal-time-range");
        if (modalTimeRange) {
            modalTimeRange.innerHTML = `Time Range: ${timeRange.hours} hours and ${timeRange.minutes} minutes`;
        }

        // Pass Price to modal form
        let modalPrice = document.getElementById("modal-time");
        modalTime.innerHTML = selectedStartTime + " - " + selectedEndTime; 
    }

      document.getElementById("select-time").addEventListener("click", addTime);
    });
  }
  


//Code to disable days in calendar
  
function handleCalendarAndSettings() {
  const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
  const isSettingsPage = document.querySelectorAll('input.day-settings').length > 0;
  const isCalendarPage = document.querySelectorAll('.day').length > 0;

  function applyCalendarLogic() {
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      const checkboxStates = calendarSettings.disableDays;

      // Get the start and end dates directly from calendarSettings
      let startDate, endDate;
      if (checkboxStates.start_day) {
          startDate = new Date(checkboxStates.start_day);
          startDate.setHours(0, 0, 0, 0);
      }
      if (checkboxStates.end_day) {
          endDate = new Date(checkboxStates.end_day);
          endDate.setHours(23, 59, 59, 999); // Include the entire end day
      }

      document.querySelectorAll('.day').forEach(cell => {
          const cellDate = cell.getAttribute('data-date');
          const [month, day, year] = cellDate.split('-').map(Number);
          const cellDateObj = new Date(year, month - 1, day);

          const dayOfWeek = cellDateObj.getDay();

          let disable = false;

          // Disable days before today
          if (cellDateObj < today) {
              disable = true;
          }

          // Disable days based on start and end dates
          if (startDate && !endDate) {
              // Disable all days before the start date
              if (cellDateObj < startDate) {
                  disable = true;
              }
          } else if (!startDate && endDate) {
              // Disable all days after the end date
              if (cellDateObj > endDate) {
                  disable = true;
              }
          } else if (startDate && endDate) {
              // Disable all days outside the range between start and end dates
              if (cellDateObj < startDate || cellDateObj > endDate) {
                  disable = true;
              }
          }

          // Disable cell if the corresponding checkbox is checked
          if (checkboxStates[dayNames[dayOfWeek]]) {
              disable = true;
          }

          // Apply the disabled class if needed
          if (disable) {
              cell.classList.add('disabled');
          } else {
              cell.classList.remove('disabled');
          }
      });
  }

  if (isSettingsPage) {
      // Settings page logic (no changes needed here)

  } else if (isCalendarPage) {
      applyCalendarLogic();

      const calendarContainer = document.querySelector('#calendar');
      if (calendarContainer) {
          const observer = new MutationObserver(() => {
              applyCalendarLogic();
          });

          observer.observe(calendarContainer, { childList: true, subtree: true });
      } else {
          console.error('Calendar container not found. Please check the selector.');
      }
  }
}

// Call the function to initialize
handleCalendarAndSettings();




}

