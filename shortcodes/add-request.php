<div class="calendar-wrapper">
    <div id="calendar"></div>

    <div id="rqt-modal" class="rqt-modal">
        <div class="rqt-modal-content">
        <div class="close-wrapper"><span class="close">&times;</span></div>
        <div class="modal-form-wrapper">            
            <form method="post" id="addRequestForm" data-url="<?php echo admin_url('admin-ajax.php'); ?>">            

            <div id="details-confirm-check">
                <div id="modal-date"></div>  
                <div id="modal-time"></div>
                <div id="modal-price"></div>

            </div>

                <p>Add your details..</p>
                

               <label for="name">Full name:</label><br>
                <input type="text" class="name" name="rqt-name"><br>
                <small class="field-msg error" data-error="invalidName">Your Name is Required</small><br>

                <label for="email">Email:</label><br>
                <input type="email" class="email" name="rqt-email"><br>
                <small class="field-msg error" data-error="invalidEmail">The Email address is not valid</small><br>

                <div id="front_phone_field_div">
                    <label for="phone">Phone Number:</label><br>
                    <input type="number" id="front_phone_field" class="phone" name="phone"><br>
                    <small class="field-msg error" data-error="invalidPhone">The Phone field is Required</small><br>
                </div>

                <div id="front_description_field_div">
                    <label for="description">Description:</label><br>
                    <textarea id="front_description_field" class="description" name="description"></textarea><br>
                    <small class="field-msg error" data-error="invalidMessage">A Description is Required</small><br>
                </div>

                

               

                <input type="hidden" id="start-time" name="rqt_start_time">
                <input type="hidden" id="end-time" name="rqt_end_time">
                <input type="hidden" id="request_date" name="request_date">

                <input type="hidden" id="request_hrs" name="request_hrs">
                <input type="hidden" id="request_mins" name="request_mins">
                

                <button type="submit" class="rqt-submit-btn">Submit</button>

                <small class="field-msg js-form-submission">Submission in process, please wait&hellip;</small>
                <small class="field-msg success js-form-success">Request Successfully submitted, thank you!</small>
                <small class="field-msg error js-form-error">There was a problem with the request Form, please try again!</small>

                <input type="hidden" name="action" value="submit_request"> 
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('submit-request-nonce'); ?>">

            </form>

            <form method="post" id="priceRequestForm" data-url="<?php echo admin_url('admin-ajax.php'); ?>">            
                <input type="hidden" id="request_hrs_price" name="request_hrs">
                <input type="hidden" id="request_mins_price" name="request_mins">
                <input type="hidden" name="action" value="price_request"> 
                <input type="hidden" id="price-request-nonce" name="nonce" value="<?php echo wp_create_nonce('price-request-nonce'); ?>">
            </form>


            </div>
        </div>
    </div>
</div>