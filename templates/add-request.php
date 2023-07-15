<div class="rqt-container">
    <div class="rqt-panel-head">
        <h3>Add Request</h3>
    </div>
    <div class="rqt-panel-body">

        <div class="wrapper">
      <header>
        
        <div class="header">
          <span id="prev" class="material-symbols-rounded"><</span>
          <p class="current-date"></p>
          <span id="next" class="material-symbols-rounded">></span>
        </div>
      </header>
      <div class="calendar">
        <ul class="weeks">
          <li>Sun</li>
          <li>Mon</li>
          <li>Tue</li>
          <li>Wed</li>
          <li>Thu</li>
          <li>Fri</li>
          <li>Sat</li>
        </ul>
        <ul class="days"></ul>
      </div>
    </div>

    <div class='timepicker'>
      <div class="inner">
        <div>06:00</div>
        <div>07:00</div>
        <div>08:00</div>
        <div>09:00</div>
        <div>10:00</div>
        <div>11:00</div>
        <div>12:00</div>
        <div>13:00</div>
        <div>14:00</div>
        <div>15:00</div>
        <div>16:00</div>
        <div>17:00</div>
        <div>18:00</div>
        <div>19:00</div>
        <div>20:00</div>
      </div>
      <div class="fade-l"></div>
      <div class="fade-r"></div>
   </div>


    <form action="" method="post">
            <label for="name">Full name:</label><br>
            <input type="text" class="name" name="name"><br>

            <label for="email">Email:</label><br>
            <input type="email" class="email" name="email"><br>

            <label for="phone">Phone Number:</label><br>
            <input type="number" class="phone" name="phone"><br>

            <label for="description">Description:</label><br>
            <textarea class="description" name="description" ></textarea><br><br>

            <input type="submit" value="Save"> <br><br>





        </form>


  </div>
</div>


<script>
// var currentYear, currentMonth;

// window.addEventListener('load', function() {
//   var now = new Date();
//   currentYear = now.getFullYear();
//   currentMonth = now.getMonth();

//   displayCalendar(currentYear, currentMonth);

//   var calendar = document.getElementById("calendar");
//   calendar.addEventListener("click", function(event) {
//     var target = event.target;
//     if (target.classList.contains("calendar-date")) {
//       var date = target.dataset.date;
//       toggleTime(target, date);
//     }
//   });
// });

// function displayCalendar(year, month) {
//   var calendar = document.getElementById("calendar");
//   calendar.innerHTML = ""; // Clear the calendar

//   var header = document.createElement("h2");
//   var prevArrow = document.createElement("span");
//   prevArrow.innerHTML = "&larr; ";
//   prevArrow.addEventListener("click", function(event) {
//     event.preventDefault();
//     navigateCalendar(-1);
//   });
//   header.appendChild(prevArrow);

//   header.textContent += getMonthName(month) + " " + year;

//   var nextArrow = document.createElement("span");
//   nextArrow.innerHTML = " &rarr;";
//   nextArrow.addEventListener("click", function(event) {
//     event.preventDefault();
//     navigateCalendar(1);
//   });
//   header.appendChild(nextArrow);

//   calendar.appendChild(header);

//   var weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
//   var weekdaysContainer = document.createElement("div");
//   weekdaysContainer.classList.add("weekdays-container");

//   for (var i = 0; i < weekdays.length; i++) {
//     var weekday = document.createElement("div");
//     weekday.textContent = weekdays[i];
//     weekdaysContainer.appendChild(weekday);
//   }

//   calendar.appendChild(weekdaysContainer);

//   var firstDay = new Date(year, month, 1);
//   var lastDay = new Date(year, month + 1, 0).getDate();

//   var currentDate = 1;
//   var weekContainer = document.createElement("div");
//   weekContainer.classList.add("week-container");

//   for (var i = 0; i < firstDay.getDay(); i++) {
//     var emptyCell = createCalendarDateCell(currentYear, currentMonth - 1, new Date(year, month, 0).getDate() - firstDay.getDay() + i + 1);
//     emptyCell.classList.add("inactive-date");
//     weekContainer.appendChild(emptyCell);
//   }

//   for (var i = firstDay.getDay(); i < 7; i++) {
//     var cell = createCalendarDateCell(year, month, currentDate);
//     weekContainer.appendChild(cell);

//     currentDate++;

//     if (currentDate > lastDay) {
//       break;
//     }
//   }

//   calendar.appendChild(weekContainer);

//   while (currentDate <= lastDay) {
//     var weekContainer = document.createElement("div");
//     weekContainer.classList.add("week-container");

//     for (var i = 0; i < 7; i++) {
//       if (currentDate > lastDay) {
//         break;
//       }

//       var cell = createCalendarDateCell(year, month, currentDate);
//       weekContainer.appendChild(cell);

//       currentDate++;
//     }

//     calendar.appendChild(weekContainer);
//   }
// }

// function createCalendarDateCell(year, month, date) {
//   var cell = document.createElement("div");
//   cell.classList.add("calendar-date");
//   cell.textContent = date;
//   cell.dataset.date = formatDate(year, month, date);

//   return cell;
// }

// function navigateCalendar(direction) {
//   if (direction === -1) {
//     currentMonth--;
//     if (currentMonth < 0) {
//       currentMonth = 11;
//       currentYear--;
//     }
//   } else if (direction === 1) {
//     currentMonth++;
//     if (currentMonth > 11) {
//       currentMonth = 0;
//       currentYear++;
//     }
//   }

//   displayCalendar(currentYear, currentMonth);
// }

// function toggleTime(target, date) {
//   var timeDiv = document.getElementById("time");

//   var timeDisplay = timeDiv.querySelector(".time-display");

//   if (timeDisplay && timeDisplay.dataset.date === date) {
//     timeDiv.removeChild(timeDisplay);
//     target.classList.remove("active-date");
//   } else {
//     var now = new Date();
//     var hours = now.getHours();
//     var minutes = now.getMinutes();
//     var seconds = now.getSeconds();

//     var timeString = formatTime(hours) + ":" + formatTime(minutes) + ":" + formatTime(seconds);

//     var timeDisplay = document.createElement("div");
//     timeDisplay.textContent = "Time for " + date + ": " + timeString;
//     timeDisplay.dataset.date = date;
//     timeDisplay.classList.add("time-display");

//     timeDiv.appendChild(timeDisplay);
//     target.classList.add("active-date");
//   }

//   var calendar = document.getElementById("calendar");
//   var calendarHeight = calendar.offsetHeight;
//   var timeDisplayHeight = timeDiv.offsetHeight;
//   var scrollPosition = target.offsetTop + target.offsetHeight;

//   if (scrollPosition + timeDisplayHeight > calendarHeight) {
//     calendar.scrollTop = scrollPosition - calendarHeight + timeDisplayHeight;
//   }
// }

// function formatTime(value) {
//   return value < 10 ? "0" + value : value;
// }

// function getMonthName(month) {
//   var monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
//     'July', 'August', 'September', 'October', 'November', 'December'];
//   return monthNames[month];
// }

// function formatDate(year, month, date) {
//   return year + "-" + formatTime(month + 1) + "-" + formatTime(date);
// }


//New calendar js



const daysTag = document.querySelector(".days"),
currentDate = document.querySelector(".current-date"),
prevNextIcon = document.querySelectorAll(".header span");

// getting new date, current year and month
let date = new Date(),
currYear = date.getFullYear(),
currMonth = date.getMonth();

// storing full name of all months in array
const months = ["January", "February", "March", "April", "May", "June", "July",
              "August", "September", "October", "November", "December"];

const renderCalendar = () => {
    let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(), // getting first day of month
    lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(), // getting last date of month
    lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(), // getting last day of month
    lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate(); // getting last date of previous month
    let liTag = "";

    for (let i = firstDayofMonth; i > 0; i--) { // creating li of previous month last days
        liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
    }

    for (let i = 1; i <= lastDateofMonth; i++) { // creating li of all days of current month
        // adding active class to li if the current day, month, and year matched
        let isToday = i === date.getDate() && currMonth === new Date().getMonth() 
                     && currYear === new Date().getFullYear() ? "active" : "";
        liTag += `<li class="${isToday}">${i}</li>`;
    }

    for (let i = lastDayofMonth; i < 6; i++) { // creating li of next month first days
        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`
    }
    currentDate.innerText = `${months[currMonth]} ${currYear}`; // passing current mon and yr as currentDate text
    daysTag.innerHTML = liTag;
}
renderCalendar();

prevNextIcon.forEach(icon => { // getting prev and next icons
    icon.addEventListener("click", () => { // adding click event on both icons
        // if clicked icon is previous icon then decrement current month by 1 else increment it by 1
        currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

        if(currMonth < 0 || currMonth > 11) { // if current month is less than 0 or greater than 11
            // creating a new date of current year & month and pass it as date value
            date = new Date(currYear, currMonth, new Date().getDate());
            currYear = date.getFullYear(); // updating current year with new date year
            currMonth = date.getMonth(); // updating current month with new date month
        } else {
            date = new Date(); // pass the current date as date value
        }
        renderCalendar(); // calling renderCalendar function
    });
});

//Show time



let gettime = document.querySelector(".timepicker");

 // Step 1: Get the list of elements by class name
 let items = document.querySelectorAll('.days li');


// Step 2: Add a click event listener to each element
for (let i = 0; i < items.length; i++) {
  items[i].addEventListener('click', function() {
    // Step 3: Remove active class from all elements
    for (let j = 0; j < items.length; j++) {
      items[j].classList.remove('selected');
    }

    // Step 4: Add active class to the clicked element
    this.classList.add('selected');

    // gettime.classList.toggle("show-time");



  // if (gettime.style.display === "none") {
  //   gettime.style.display = "block";
  // } else {
  //   gettime.style.display = "none";
  // }

  });
}


</script>



<?php

// add_action('user_register', 'create_custom_user');

// function create_custom_user(){

    if (isset($_POST['name']) && isset($_POST['email'])) {

    $user_name = sanitize_user($_POST['name']);
    $user_email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $description = sanitize_text_field($_POST['description']);


    $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

    $user = wp_create_user($user_name, $random_password, $user_email);

    if (!is_wp_error($user)) {

    $user_id = new WP_User($user);

     // Assign a role to the user
    $user_id->remove_role('subscriber');
    $user_id->add_role('request_customer');

    // Update user meta data
    update_user_meta($user, 'phone', $phone);

    if($user_id){

    $new_post = array(
      'post_title' => 'New Request',
      'post_content' => $description,
      'post_type' => 'service_cpt',
      'post_author' => $user_id->ID,
      'post_status' => 'draft'
     );
     
     $post_id = wp_insert_post($new_post);
     
     if($post_id){
      echo "Request added successfully with the ID of ".$post_id." and user id of ".$user_id->ID;
     } else {
      echo "Error, Request not created";
     }

    }


    }

    else {
        // An error occurred
        $error_message = $user->get_error_message();
        echo $error_message;
    }
  
      
    }


   