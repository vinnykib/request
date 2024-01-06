<div class="rqt-container">
    <div class="rqt-panel-head">
        <h3>Add Request</h3>
    </div>
    <div class="rqt-panel-body">

        <div class="wrapper">
      <header>
        
        <div class="header" id="header">
          <span id="prev" class="material-symbols-rounded"><</span>
          <button id="prevMonth">Previous Month</button>
          <button id="nextMonth">Next Month</button>
          <p class="current-date"></p>
          <span id="next" class="material-symbols-rounded">></span>
        </div>
      </header>

                
      
      <div id="calendar"></div>
      

    <div class='timepicker'>
    <form action="" method="post">
    <label for="rqt">Start:</label>
    <input type="time" class="start-time" name="rqt_start_time" value="09:00">

    <label for="rqt">End:</label>
    <input type="time" class="end-time" name="rqt_end_time" value="17:00">
</form>
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

            <input type="hidden" id="start_time" name="rqt_start_time">
            <input type="hidden" id="end_time" name="rqt_end_time">            

            <input type="hidden" id="request_date" name="request_date">

            <input type="submit" value="Save"> <br><br>





        </form>


  </div>
</div>


<script>

document.addEventListener('DOMContentLoaded', function () {
    const calendar = document.getElementById('calendar');
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];


    function generateCalendar(year, month) {
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const currMonth = month + 1;
        const nextMonthYear = new Date(year, month + 1, 1).getFullYear(); // get previous month year
        const lastMonthYear = new Date(year, month, 0).getFullYear(); // get previous month


        calendar.innerHTML = '';

        
        const monthHeader = document.createElement('div');
        monthHeader.classList.add('month');     
        calendar.appendChild(monthHeader);

        // Create month header
        monthHeader.innerHTML = `
            <span>${months[month]} ${year}</span>
        `;



        // Create table for days
        const table = document.createElement('table');

        // Create day labels (headers)
        const dayLabels = document.createElement('tr');
        const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        for (const dayName of dayNames) {
            const dayLabel = document.createElement('th');
            dayLabel.textContent = dayName;
            dayLabels.appendChild(dayLabel);
        }
        table.appendChild(dayLabels);

        // Calculate the number of days to display from the previous and next months
        const daysFromPrevMonth = firstDay.getDay(); // Days to display from the previous month
        const daysFromNextMonth = (6 * 7) - daysFromPrevMonth - daysInMonth; // Days to display from the next month

        // Create days
        let dayCount = 1;

        for (let i = 0; i < 6; i++) {
            const row = document.createElement('tr');
            row.classList.add('week');     
            let weekHasDays = false; // Flag to check if the week has any days

            for (let j = 0; j < 7; j++) {
                const cell = document.createElement('td');
                cell.classList.add('day');  
                
                // Determine whether to display a day from the previous, current, or next month
                if (i === 0 && j < daysFromPrevMonth) {
                    // Days from the previous month
                    const prevMonthDay = new Date(year, month, 0).getDate() - (daysFromPrevMonth - j) + 1;
                    const lastMonth = new Date(year, month, 0).getMonth() + 1// get previous month
                    // get previous month
                    cell.textContent = prevMonthDay;
                    cell.setAttribute('data-date',prevMonthDay+'-'+lastMonth+'-'+lastMonthYear);
                    cell.classList.add('prevMonth');
                } else if (dayCount <= daysInMonth) {
                    // Days from the current month
                    cell.textContent = dayCount;
                    cell.setAttribute('data-date',dayCount+'-'+currMonth+'-'+year); 

                    // Highlight today's date
                    const today = new Date();
                    if (year === today.getFullYear() && month === today.getMonth() && dayCount === today.getDate()) {
                        cell.classList.add('today');
                    }

                    dayCount++;
                    weekHasDays = true;
                } else {
                    // Days from the next month
                    const nextMonthDay = dayCount - daysInMonth;
                    const nextMonth = new Date(year, month + 1, 1).getMonth() + 1// get next month
                    cell.textContent = nextMonthDay;
                    cell.setAttribute('data-date',nextMonthDay+'-'+nextMonth+'-'+nextMonthYear); 
                    cell.classList.add('nextMonth');
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
        setupClickEvents('week td', 'request_date');   
    }

    const currentDate = new Date();
    generateCalendar(currentDate.getFullYear(), currentDate.getMonth());

    // Add event listeners for navigating months
    document.getElementById('prevMonth').addEventListener('click', function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    });

    document.getElementById('nextMonth').addEventListener('click', function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    });
});



function setupClickEvents(className, inputId) {
  // Step 1: Get the list of elements by class name
  let items = document.querySelectorAll('.' + className);

  const timePicker = document.createElement('div');
  timePicker.classList.add('timePicker');
  timePicker.innerHTML = 'Helo';

  // Step 2: Add a click event listener to each element
  for (let i = 0; i < items.length; i++) {
    items[i].addEventListener('click', function() {
      // Step 3: Remove selected class from all elements
      for (let j = 0; j < items.length; j++) {
        items[j].classList.remove('selected');
        items[j].parentNode.classList.remove('selected');
   
      }

      // Step 4: Add selected class to the clicked element
      this.classList.add('selected');
      this.parentElement.classList.add('selected');
      let week = document.querySelector('.week.selected');

      if(week){
        week.parentElement.insertBefore(timePicker, week.nextSibling);
      }
   

      // Get date on click
      let selectedDate = this.dataset.date;

      // Set the selected date to the input field
      let dateContentInput = document.getElementById(inputId);
      if (dateContentInput) {
        dateContentInput.value = selectedDate;
      }

      console.log(selectedDate);
         
    });
  }
}

    

</script>

<style>
  
#calendar {
    width: 300px;
    margin: 50px auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
}

th {
    background-color: #007bff;
    color: #fff;
}

td:hover {
    background-color: #f2f2f2;
}

.today {
    background-color: #4CAF50;
    color: white;
}
.time {
    height: 30px;
}
.timepicker div {
    display: inline;
}
</style>



<?php

    if (isset($_POST['name']) && isset($_POST['email'])) {

    $user_name = sanitize_user($_POST['name']);
    $user_email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $description = sanitize_text_field($_POST['description']);

    $rqt_start_time = sanitize_text_field($_POST['rqt_start_time']);
    $rqt_end_time = sanitize_text_field($_POST['rqt_end_time']);

    $request_date = sanitize_text_field($_POST['request_date']);


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

      // Update post meta data
      update_post_meta( $post_id, 'rqt_start_time', $rqt_start_time );
      update_post_meta( $post_id, 'rqt_end_time', $rqt_end_time );
      update_post_meta( $post_id, 'request_date', $request_date );
      

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


   