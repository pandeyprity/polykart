<?php
function karza_login()
{
  global $wpdb;

  $table_name = $wpdb->prefix . 'karza_reponse';

  $res = GetAllRecords_karza();
  $results = $res["data"];
  $i = $res["start"];
?>
  <div>
    <span class="errorMessage"> </span>
  </div>
  <div class="wrap">
    <div class="wrap nosubsub">
      <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>
      <div id="login_form" style="display: block;">
        <div class="form-field form-required term-name-wrap costom_input">
          <label for="product_groups" class="required_astrisk">GSTIN Username </label>
          <input type="text" name="karza_username" id="karza_username" class="regular-text myinput" value="testuser1" />
          <span class="formError" id="karza_usernameError"></span>
        </div>

        <div class="form-field form-required term-name-wrap costom_input">
          <label for="product_groups" class="required_astrisk">GSTIN </label>
          <input type="text" name="gstin" id="gstin" class="regular-text myinput" value="27AAATW4183C2ZG" />
          <span class="formError" id="gstinError"></span>
        </div>

        <center>
          <button type="button" class="elementor-button" onclick="sendOTP()" id="karza_submit_btn" style="font-size: 16px; font-weight: 500; fill: #FFFFFF; color: #FFFFFF; background-color: #0B3954; --fill-color: #FFFFFF; border-radius: 4px 4px 4px 4px; padding: 16px 36px 16px 36px;">
            Submit
          </button>
          <span class="karza_submit_btn_rotate" style="display: none;">
            <img src ="<?= home_url() . "/wp-content/plugins/Karza-GST-Validator/image/rotate.svg";?>" alt="Loading" style="height: 50px;"/>
          </span>
        </center>
      </div>

      <div id="otp_form" style="display: none;">
        <div class="form-field form-required term-name-wrap costom_input">
          <label for="product_groups" class="required_astrisk">One Time Password </label>
          <input type="text" name="otp" id="otp" class="regular-text myinput" maxlength="6" />
          <span class="formError" id="otpError"></span>
        </div>
        <input type="hidden" id="requestId" />
        <center>
          <button type="button" class="elementor-button" onclick="verifyOTP();" id="karza_verify_btn" style="font-size: 16px; font-weight: 500; fill: #FFFFFF; color: #FFFFFF; background-color: #0B3954; --fill-color: #FFFFFF; border-radius: 4px 4px 4px 4px; padding: 16px 36px 16px 36px;">
              Verify
          </button>
          <span class="karza_submit_btn_rotate" style="display: none;">
            <img src ="<?= home_url() . "/wp-content/plugins/Karza-GST-Validator/image/rotate.svg";?>" alt="Loading" style="height: 50px;"/>
          </span>
        </center>
      </div>

      <div id="response_form" style="display: none;">
        <div class="col-wrap" style="text-align: center; ">
          <div class="container1">
            <svg viewBox="0 0 76 76" class="success-message__icon icon-checkmark" style="width: 50px; fill: #3DC480;">
              <circle cx="38" cy="38" r="36"></circle>
              <path fill="none" stroke="#FFFFFF" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M17.7,40.9l10.9,10.9l28.7-28.7"></path>
            </svg>
            <h3 style="color: #3DC480;text-transform: unset;margin-top: 10px;">Your credit request has been submitted</h3>
          </div>
        </div>
      </div>

    </div>

  </div>
<?php
}

add_shortcode("karza_login", "karza_login");
?>