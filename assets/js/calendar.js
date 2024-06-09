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
            const prevMonthDay =
              new Date(year, month, 0).getDate() - (daysFromPrevMonth - j) + 1;
            const lastMonth = new Date(year, month, 0).getMonth() + 1; // get previous month
            // get previous month
            cell.textContent = prevMonthDay;
            cell.setAttribute(
              "data-date",
              prevMonthDay + "-" + lastMonth + "-" + lastMonthYear
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
              nextMonthDay + "-" + nextMonth + "-" + nextMonthYear
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
            <input type="time" id="form-end-time" name="rqt_end_time" value="17:00">
        </div>

        <div>
        <!-- Trigger/Open The Modal -->
            <button type="button" id="select-time">Make Request</button>
        </div>
    <div>
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

        //     let getTime = document.getElementById('time-wrapper');
        //   // Toggle the visibility of the time wrapper
        //   getTime.style.display = (getTime.style.display === 'none' || getTime.style.display === '') ? 'block' : 'none';
      }

      // Get date on click
      let selectedDate = this.dataset.date;

      // Set the selected date to the input field
      //   let dateContentInput = document.getElementById(inputId);
      //   if (dateContentInput) {
      //     dateContentInput.value = selectedDate;
      //   }

      //   console.log(selectedDate);

      let parts = selectedDate.split("-");
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

        let dateContentInput = document.getElementById(inputId);
        if (dateContentInput) {
          dateContentInput.value = formattedDate;
        }

        console.log(formattedDate);
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
      }

      document.getElementById("select-time").addEventListener("click", addTime);
    });
  }
}

