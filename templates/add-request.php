<div class="calendar-wrapper">
    <div class="calendar-header" id="calendar-header">
        <span id="prevMonth" class="material-symbols-rounded"><</span>
        <span class="current-date"></span>
        <span id="nextMonth" class="material-symbols-rounded">></span>
    </div>
    <div id="calendar"></div>

    <div id="rqt-modal" class="rqt-modal">
        <div class="rqt-modal-content">
            <span class="close">&times;</span>
            <form method="post" id="addRequestForm" data-url="<?php echo admin_url('admin-ajax.php'); ?>">            
            

            <div>
                Date: <p id="modal-date"></p>  
                Time: <p" id="modal-time"></p>
            </div>

                <p>Add your details..</p>
                

                <br><label for="name">Full name:</label><br>
                <input type="text" class="name" name="rqt-name"><br>
                <small class="field-msg error" data-error="invalidName">Your Name is Required</small>

                <label for="email">Email:</label><br>
                <input type="email" class="email" name="rqt-email"><br>
                <small class="field-msg error" data-error="invalidEmail">The Email address is not valid</small>

                <label for="phone">Phone Number:</label><br>
                <input type="number" class="phone" name="phone"><br>

                <label for="description">Description:</label><br>
                <textarea class="description" name="description"></textarea><br>
                <small class="field-msg error" data-error="invalidMessage">A Description is Required</small><br>

                <input type="hidden" id="start-time" name="rqt_start_time">
                <input type="hidden" id="end-time" name="rqt_end_time">
                <input type="hidden" id="request_date" name="request_date">

                <input type="hidden" id="request_hrs" name="request_hrs">
                <input type="hidden" id="request_mins" name="request_mins">
                

                <button type="submit" class="btn btn-default btn-lg btn-sunset-form">Submit</button>

                <small class="field-msg js-form-submission">Submission in process, please wait&hellip;</small>
                <small class="field-msg success js-form-success">Message Successfully submitted, thank you!</small>
                <small class="field-msg error js-form-error">There was a problem with the Contact Form, please try again!</small>

                <input type="hidden" name="action" value="submit_request"> 
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('submit-request-nonce'); ?>">
            </form>
        </div>
    </div>
</div>
