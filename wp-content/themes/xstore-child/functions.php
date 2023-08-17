<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 1001 );
function theme_enqueue_styles() {
	etheme_child_styles();
}

function add_manage_cat_to_author_role() 
{
    if ( ! current_user_can( 'author' ) )
        return;

    // here you should check if the role already has_cap already and if so,   abort/return;

    if ( current_user_can( 'author' ) ) 
    {
        $GLOBALS['wp_roles']->add_cap( 'author','manage_categories' );
    }
}

add_action( 'admin_init', 'add_manage_cat_to_author_role', 10, 0 );


// Save the first_name, billing_phone 
add_action('woocommerce_register_form_start', 'woocommerce_register_form_additional_fields' );

function woocommerce_register_form_additional_fields()
{
    ?>
    <p class="form-row form-row-wide">
        <input type="text" class="input-text loginwc-textbox" name="first_name" id="first_name" value="<?php if (!empty($_POST['first_name'])) esc_attr_e($_POST['first_name']); ?>" placeholder="Name"/>
    </p>

    <span class="form-row form-row-wide">
        <div class="input-group">
            <span class="input-group-addon">+91</span>
            <input type="text" name="billing_phone" id="billing_phone" class="form-control loginwc-number" placeholder="" pattern="[0-9]{10}" value="<?php if (!empty($_POST['billing_phone'])) esc_attr_e($_POST['billing_phone']); ?>" maxlength="10" />
        </div>
    </span>

    <?php 
}



// Save the first_name, billing_phone 
add_action('woocommerce_created_customer', 'woocommerce_save_register_fields1');
function woocommerce_save_register_fields1($customer_id)
{
    if (isset($_POST['first_name'])) {
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['first_name']));
    }
    
    if (isset($_POST['billing_phone'])) {
        $billing_phone = sanitize_text_field($_POST['billing_phone']);
        update_user_meta($customer_id, 'billing_phone', $billing_phone);
    }
}



// Add billing phone field to edit account form
add_action( 'woocommerce_edit_account_form', 'add_billing_phone_to_edit_account_form' );
function add_billing_phone_to_edit_account_form() {
    $user = wp_get_current_user();
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_phone"><?php _e( 'Mobile No', 'woocommerce' ); ?> <span class="required">*</span></label>
        <input type="tel" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="billing_phone" value="<?php echo esc_attr( $user->billing_phone ); ?>" autocomplete="tel">
    </p>
    <?php
}

// Save billing phone field on edit account form submit
add_action( 'woocommerce_save_account_details', 'save_billing_phone_on_edit_account', 10, 1 );
function save_billing_phone_on_edit_account( $user_id ) {
    if ( isset( $_POST['billing_phone'] ) ) {
        update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }
}



add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {

    if ( isset( $_POST['first_name'] ) && empty( $_POST['first_name'] ) ) {
        $validation_errors->add( 'first_name_error', __( 'Name is required!', 'woocommerce' ) );
    }

    if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
        $validation_errors->add( 'billing_phone_error', __( 'Mobile No is required!', 'woocommerce' ) );
    }
    return $validation_errors;
}



// Show GST no input box in account detail page
add_action('woocommerce_edit_account_form', 'add_gst_number_to_edit_account');
function add_gst_number_to_edit_account()
{
    $user_id = get_current_user_id();
    $gst_number = get_user_meta($user_id, 'gst_number', true);
    $registered_company = get_user_meta($user_id, 'registered_company', true);
    $type_of_business = get_user_meta($user_id, 'type_of_business', true);
    $nature_of_business = get_user_meta($user_id, 'nature_of_business', true);
    // $user = wp_get_current_user();
    ?>
    <div class="my-custom-fields">
        Do you have registered company?
        <label class="woocommerce-form__label woocommerce-form__label-for-radio radio">
            <input type="radio" id="registered_company_yes" name="registered_company" value="yes" <?php echo $registered_company == 'yes' ? 'checked' : null; ?> /> Yes
        </label>
        <label class="woocommerce-form__label woocommerce-form__label-for-radio radio">
            <input type="radio" id="registered_company_no" name="registered_company" value="no" <?php echo $registered_company == 'no' ? 'checked' : null; ?> /> No
        </label>
        <span class="error" id="registered_companyError"></span>
    </div>

    <fieldset id="company_information" style="display: <?= $registered_company == 'yes' ? 'block' : 'none'; ?>">
        <legend> Business Information </legend>
        <div class="my-custom-fields" style="margin-bottom: 1.43rem;">
            <div class="form-row form-row-wide">
                <label for="type_of_business">
                    <?php _e('Nature of Business', 'text-domain'); ?>
                    <span class="required">*</span>
                </label>
                <select class="" name="type_of_business" id="type_of_business">
                    <option value="">--Select--</option>
                    <option value="Software development" <?= $type_of_business == "Software development" ? "selected" : "" ?>>Software development</option>
                    <option value="Construction" <?= $type_of_business == "Construction" ? "selected" : "" ?>>Construction</option>
                    <option value="Retail" <?= $type_of_business == "Retail" ? "selected" : "" ?>>Retail</option>
                    <option value="Healthcare" <?= $type_of_business == "Healthcare" ? "selected" : "" ?>>Healthcare</option>
                    <option value="Financial services" <?= $type_of_business == "Financial services" ? "selected" : "" ?>>Financial services</option>
                    <option value="Manufacturing" <?= $type_of_business == "Manufacturing" ? "selected" : "" ?>>Manufacturing</option>
                    <option value="Hospitality" <?= $type_of_business == "Hospitality" ? "selected" : "" ?>>Hospitality</option>
                    <option value="Education" <?= $type_of_business == "Education" ? "selected" : "" ?>>Education</option>
                    <option value="Agriculture" <?= $type_of_business == "Agriculture" ? "selected" : "" ?>>Agriculture</option>
                    <option value="Transportation" <?= $type_of_business == "Transportation" ? "selected" : "" ?>>Transportation</option>
                    <option value="Energy and utilities" <?= $type_of_business == "Energy and utilities" ? "selected" : "" ?>>Energy and utilities</option>
                    <option value="Real estate" <?= $type_of_business == "Real estate" ? "selected" : "" ?>>Real estate</option>
                    <option value="Media and entertainment" <?= $type_of_business == "Media and entertainment" ? "selected" : "" ?>>Media and entertainment</option>
                    <option value="Automotive" <?= $type_of_business == "Automotive" ? "selected" : "" ?>>Automotive</option>
                    <option value="Telecommunications" <?= $type_of_business == "Telecommunications" ? "selected" : "" ?>>Telecommunications</option>
                    <option value="Consumer goods" <?= $type_of_business == "Consumer goods" ? "selected" : "" ?>>Consumer goods</option>
                    <option value="Professional services" <?= $type_of_business == "Professional services" ? "selected" : "" ?>>Professional services</option>
                    <option value="Non-profit organizations" <?= $type_of_business == "Non-profit organizations" ? "selected" : "" ?>>Non-profit organizations</option>
                    <option value="Government agencies" <?= $type_of_business == "Government agencies" ? "selected" : "" ?>>Government agencies</option>
                    <option value="Sports and recreation" <?= $type_of_business == "Sports and recreation" ? "selected" : "" ?>>Sports and recreation</option>
                    <option value="Other" <?= $type_of_business == "Other" ? "selected" : null; ?>>Other (If other please specify text box)</option>
                </select>
                <span class="error" id="type_of_businessError"></span>
            </div>

            <div class="form-row form-row-wide" style="margin-top: 1.43rem; display: <?= $type_of_business == "Other" ? "block" : "none"; ?>;" id="nature_of_business_div">
                <input type="text" class="" name="nature_of_business" id="nature_of_business" placeholder="Nature of Business" value="<?= $nature_of_business;?>" <?= $type_of_business == "Other" ? "required" : null; ?>/>
                <span class="error" id="nature_of_businessError"></span>
            </div>
        </div>

        <div class="my-custom-fields">
            <div class="form-row form-row-wide">
                <label for="gst_no">
                    <?php _e('GST No', 'text-domain'); ?>
                    <span class="required">*</span>
                </label>
                <input type="text" class="input-text" name="gst_no" id="gst_no" placeholder="<?php _e('GST No', 'text-domain'); ?>" value="<?php echo esc_attr($gst_number); ?>" autocomplete="off" maxlength="15" />
                <span class="error gst_noError"></span>
                <p id="gst_status"></p>
            </div>
        </div>
    </fieldset>


    <script>
        $('input[name="registered_company"]').on("change", function() {
            var registered_company = $('input[name="registered_company"]:checked').val();
            console.log("Selected value: " + registered_company);

            if(registered_company=="yes"){
                $("#company_information").show();
                $("#type_of_business").prop("required", true);
            }
            if(registered_company=="no"){
                $("#company_information").hide();
                $("#type_of_business").prop("required", false);
                $("#nature_of_business").prop("required", false);
            }
        });
        
        $("#type_of_business").on("change", function() {
            if ($(this).val() == "Other") {
                $("#nature_of_business_div").show();
                $("#nature_of_business").prop("required", true);
            } else {
                $("#nature_of_business_div").hide();
                $("#nature_of_business").prop("required", false);
            }
        });
        </script>
<?php
}

// Save GST Number and Registered Company fields on My Account > Edit Account page
add_action('woocommerce_save_account_details', 'save_gst_number_and_registered_company_on_edit_account', 10, 1);
function save_gst_number_and_registered_company_on_edit_account($user_id)
{
    if (isset($_POST['registered_company']) && $_POST['registered_company']=="yes") {
        update_user_meta($user_id, 'registered_company', 'yes');
        update_user_meta($user_id, 'type_of_business', sanitize_text_field($_POST['type_of_business']));
        update_user_meta($user_id, 'gst_number', sanitize_text_field($_POST['gst_no']));

        if(sanitize_text_field($_POST['type_of_business']) == "Other"){
            update_user_meta($user_id, 'nature_of_business', sanitize_text_field($_POST['nature_of_business']));
        }
        else {
            update_user_meta($user_id, 'nature_of_business', sanitize_text_field($_POST['type_of_business']));
        }
        
    }
    
    if (isset($_POST['registered_company']) && $_POST['registered_company']=="no") {
        update_user_meta($user_id, 'registered_company', 'no');
        update_user_meta($user_id, 'type_of_business', null);
        update_user_meta($user_id, 'nature_of_business', null);
        update_user_meta($user_id, 'gst_number', null);
    }
}



// display popup on cart if company information is not filled
add_action("woocommerce_before_checkout_form", 'my_custom_content');

// display popup on dashboard if company information is not filled
add_action('woocommerce_account_dashboard', 'my_custom_content');
function my_custom_content()
{
    if(is_user_logged_in()){
        $user_id = get_current_user_id();

        //update code
        if(isset($_POST["save_company_information"]))
        if (isset($_POST['registered_company']) && $_POST['registered_company']=="yes") {
            update_user_meta($user_id, 'registered_company', 'yes');
            if(sanitize_text_field($_POST['type_of_business']) == "Other"){
                update_user_meta($user_id, 'type_of_business', sanitize_text_field($_POST['type_of_business']));
                update_user_meta($user_id, 'nature_of_business', sanitize_text_field($_POST['nature_of_business']));
            }
            else {
                update_user_meta($user_id, 'type_of_business', sanitize_text_field($_POST['type_of_business']));
                update_user_meta($user_id, 'nature_of_business', sanitize_text_field($_POST['type_of_business']));
            }
            
        }
        
        if (isset($_POST['registered_company']) && $_POST['registered_company']=="no") {
            update_user_meta($user_id, 'registered_company', 'no');
            update_user_meta($user_id, 'type_of_business', null);
            update_user_meta($user_id, 'nature_of_business', null);
        }

        $registered_company = get_user_meta($user_id, 'registered_company', true);
        if(!in_array($registered_company, ["yes", "no"])){
            $type_of_business = get_user_meta($user_id, 'type_of_business', true);
            ?>
            <div class="modal-overlay">
                <div class="modal">
                    <div class="modal-header">
                        <h3>Complete Profile</h3>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="my-custom-fields">
                                Would you be transacting as a business?
                                <label class="woocommerce-form__label woocommerce-form__label-for-radio radio">
                                    <input type="radio" id="registered_company_yes" name="registered_company" value="yes" <?php echo $registered_company == 'yes' ? 'checked' : null; ?> required /> Yes
                                </label>
                                <label class="woocommerce-form__label woocommerce-form__label-for-radio radio">
                                    <input type="radio" id="registered_company_no" name="registered_company" value="no" <?php echo $registered_company == 'no' ? 'checked' : null; ?> required /> No
                                </label>
                                <span class="error" id="registered_companyError"></span>
                            </div>
                            

                            <fieldset id="company_information" style="display: <?= $registered_company == 'yes' ? 'block' : 'none'; ?>">
                                <legend> Business Information </legend>
                                <div class="my-custom-fields" style="margin-bottom: 1.43rem;">
                                    <div class="form-row form-row-wide">
                                        <label for="type_of_business">
                                            <?php _e('Nature of Business', 'text-domain'); ?>
                                            <span class="required">*</span>
                                        </label>
                                        <select name="type_of_business" id="type_of_business" class="">
                                            <option value="">--Select--</option>
                                            <option value="Software development" <?= $type_of_business == "Software development" ? "selected" : "" ?>>Software development</option>
                                            <option value="Construction" <?= $type_of_business == "Construction" ? "selected" : "" ?>>Construction</option>
                                            <option value="Retail" <?= $type_of_business == "Retail" ? "selected" : "" ?>>Retail</option>
                                            <option value="Healthcare" <?= $type_of_business == "Healthcare" ? "selected" : "" ?>>Healthcare</option>
                                            <option value="Financial services" <?= $type_of_business == "Financial services" ? "selected" : "" ?>>Financial services</option>
                                            <option value="Manufacturing" <?= $type_of_business == "Manufacturing" ? "selected" : "" ?>>Manufacturing</option>
                                            <option value="Hospitality" <?= $type_of_business == "Hospitality" ? "selected" : "" ?>>Hospitality</option>
                                            <option value="Education" <?= $type_of_business == "Education" ? "selected" : "" ?>>Education</option>
                                            <option value="Agriculture" <?= $type_of_business == "Agriculture" ? "selected" : "" ?>>Agriculture</option>
                                            <option value="Transportation" <?= $type_of_business == "Transportation" ? "selected" : "" ?>>Transportation</option>
                                            <option value="Energy and utilities" <?= $type_of_business == "Energy and utilities" ? "selected" : "" ?>>Energy and utilities</option>
                                            <option value="Real estate" <?= $type_of_business == "Real estate" ? "selected" : "" ?>>Real estate</option>
                                            <option value="Media and entertainment" <?= $type_of_business == "Media and entertainment" ? "selected" : "" ?>>Media and entertainment</option>
                                            <option value="Automotive" <?= $type_of_business == "Automotive" ? "selected" : "" ?>>Automotive</option>
                                            <option value="Telecommunications" <?= $type_of_business == "Telecommunications" ? "selected" : "" ?>>Telecommunications</option>
                                            <option value="Consumer goods" <?= $type_of_business == "Consumer goods" ? "selected" : "" ?>>Consumer goods</option>
                                            <option value="Professional services" <?= $type_of_business == "Professional services" ? "selected" : "" ?>>Professional services</option>
                                            <option value="Non-profit organizations" <?= $type_of_business == "Non-profit organizations" ? "selected" : "" ?>>Non-profit organizations</option>
                                            <option value="Government agencies" <?= $type_of_business == "Government agencies" ? "selected" : "" ?>>Government agencies</option>
                                            <option value="Sports and recreation" <?= $type_of_business == "Sports and recreation" ? "selected" : "" ?>>Sports and recreation</option>
                                            <option value="Other" <?= $type_of_business == "Other" ? "selected" : null; ?>>Other (If other please specify text box)</option>
                                        </select>
                                        <span class="error" id="type_of_businessError"></span>
                                    </div>
                                    <div class="form-row form-row-wide" style="margin-top: 1.43rem; display: none;" id="nature_of_business_div">
                                        <input type="text" class="" name="nature_of_business" id="nature_of_business" placeholder="If other please specify text box" />
                                        <span class="error" id="nature_of_businessError"></span>
                                    </div>
                                </div>

                            </fieldset>
                            <div class="my-custom-fields">
                                <button type="submit" class="woocommerce-Button button wp-element-button" name="save_company_information" value="Save changes" onclick="return ValidatePopupForm()">Save changes</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <script>
            $('input[name="registered_company"]').on("change", function() {
                var registered_company = $('input[name="registered_company"]:checked').val();
                console.log("Selected value: " + registered_company);

                if(registered_company=="yes"){
                    $("#company_information").show();
                    $("#type_of_business").prop("required", true);
                }
                if(registered_company=="no"){
                    $("#company_information").hide();
                    $("#type_of_business").prop("required", false);
                    $("#nature_of_business").prop("required", false);
                }
            });
            
            $("#type_of_business").on("change", function() {
                if ($(this).val() == "Other") {
                    $("#nature_of_business_div").show();
                    $("#nature_of_business").prop("required", true);
                } else {
                    $("#nature_of_business_div").hide();
                    $("#nature_of_business").prop("required", false);
                }
            });
            </script>
            <?php
        }
    }
}



// display GST No Input Box on checkout page
add_action("woocommerce_before_checkout_billing_form", 'displayGSTNo');
function displayGSTNo()
{
    $user_id = get_current_user_id();
    $type_of_business = get_user_meta( $user_id, 'type_of_business', true );
    $nature_of_business = get_user_meta( $user_id, 'nature_of_business', true );
    $gst_number = get_user_meta( $user_id, 'gst_number', true );
    $registered_company = get_user_meta( $user_id, 'registered_company', true );
    
    // Check if user has GST number
        ?>
        <div class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
            <label style="padding-left: 0px !important;">
                <input name="registered_company" type="checkbox" class="toggle_checkbox" data-value="true" value="yes" />
                <span class="check"></span>
            </label>
            <span> Is your business GST Registered ? </span>
        </div>
        <fieldset id="company_information" style="display: none;">
            <legend> Business Information </legend>
            <div class="my-custom-fields" style="margin-bottom: 1.43rem;">
                <div class="form-row form-row-wide">
                    <label for="type_of_business">
                        <?php _e('Nature of Business', 'text-domain'); ?>
                        <span class="required">*</span>
                    </label>
                    <select class="" name="type_of_business" id="type_of_business">
                        <option value="">--Select--</option>
                        <option value="Software development" <?= $type_of_business == "Software development" ? "selected" : "" ?>>Software development</option>
                        <option value="Construction" <?= $type_of_business == "Construction" ? "selected" : "" ?>>Construction</option>
                        <option value="Retail" <?= $type_of_business == "Retail" ? "selected" : "" ?>>Retail</option>
                        <option value="Healthcare" <?= $type_of_business == "Healthcare" ? "selected" : "" ?>>Healthcare</option>
                        <option value="Financial services" <?= $type_of_business == "Financial services" ? "selected" : "" ?>>Financial services</option>
                        <option value="Manufacturing" <?= $type_of_business == "Manufacturing" ? "selected" : "" ?>>Manufacturing</option>
                        <option value="Hospitality" <?= $type_of_business == "Hospitality" ? "selected" : "" ?>>Hospitality</option>
                        <option value="Education" <?= $type_of_business == "Education" ? "selected" : "" ?>>Education</option>
                        <option value="Agriculture" <?= $type_of_business == "Agriculture" ? "selected" : "" ?>>Agriculture</option>
                        <option value="Transportation" <?= $type_of_business == "Transportation" ? "selected" : "" ?>>Transportation</option>
                        <option value="Energy and utilities" <?= $type_of_business == "Energy and utilities" ? "selected" : "" ?>>Energy and utilities</option>
                        <option value="Real estate" <?= $type_of_business == "Real estate" ? "selected" : "" ?>>Real estate</option>
                        <option value="Media and entertainment" <?= $type_of_business == "Media and entertainment" ? "selected" : "" ?>>Media and entertainment</option>
                        <option value="Automotive" <?= $type_of_business == "Automotive" ? "selected" : "" ?>>Automotive</option>
                        <option value="Telecommunications" <?= $type_of_business == "Telecommunications" ? "selected" : "" ?>>Telecommunications</option>
                        <option value="Consumer goods" <?= $type_of_business == "Consumer goods" ? "selected" : "" ?>>Consumer goods</option>
                        <option value="Professional services" <?= $type_of_business == "Professional services" ? "selected" : "" ?>>Professional services</option>
                        <option value="Non-profit organizations" <?= $type_of_business == "Non-profit organizations" ? "selected" : "" ?>>Non-profit organizations</option>
                        <option value="Government agencies" <?= $type_of_business == "Government agencies" ? "selected" : "" ?>>Government agencies</option>
                        <option value="Sports and recreation" <?= $type_of_business == "Sports and recreation" ? "selected" : "" ?>>Sports and recreation</option>
                        <option value="Other" <?= $type_of_business == "Other" ? "selected" : null; ?>>Other (If other please specify text box)</option>
                    </select>
                    <span class="error" id="type_of_businessError"></span>
                </div>

                <div class="form-row form-row-wide" style="margin-top: 1.43rem; display: <?= $type_of_business == "Other" ? "block" : "none"; ?>;" id="nature_of_business_div">
                    <input type="text" class="" name="nature_of_business" id="nature_of_business" placeholder="Nature of Business" value="<?= $nature_of_business;?>" <?= $type_of_business == "Other" ? "required" : null; ?>/>
                    <span class="error" id="nature_of_businessError"></span>
                </div>
            </div>

            <div class="my-custom-fields">
                <div class="form-row form-row-wide">
                    <label for="gst_no">
                        <?php _e('GST No', 'text-domain'); ?>
                        <span class="required">*</span>
                    </label>
                    <input type="text" class="input-text" name="gst_no" id="gst_no" placeholder="<?php _e('GST No', 'text-domain'); ?>" value="<?php echo esc_attr($gst_number); ?>" autocomplete="off" maxlength="15" />
                    <span class="error" id="gst_noError"></span>
                </div>
            </div>

            <div class="my-custom-fields" style="margin-top: 1.43rem;">
                <div class="form-row form-row-wide">
                    <label for="gst_no">
                        <?php _e('GST Status', 'text-domain'); ?>
                        <span class="required">*</span>
                    </label>
                    <p id="gst_status"></p>
                </div>
            </div>
        </fieldset>
        <script>
        $('input[name="registered_company"]').on("change", function() {
            var registered_company = $('input[name="registered_company"]:checked').val();
            console.log("Selected value: " + registered_company);

            if(registered_company=="yes"){
                $("#company_information").show();
                $("#type_of_business").prop("required", true);
            }
            if(typeof registered_company==="undefined"){
                $("#company_information").hide();
                $("#type_of_business").prop("required", false);
                $("#nature_of_business").prop("required", false);
            }
        });
        
        $("#type_of_business").on("change", function() {
            if ($(this).val() == "Other") {
                $("#nature_of_business_div").show();
                $("#nature_of_business").prop("required", true);
            } else {
                $("#nature_of_business_div").hide();
                $("#nature_of_business").prop("required", false);
            }
        });
        </script>
        <?php
}


//  call a function after the user have pressed the "Place order" button
add_action('woocommerce_checkout_update_order_meta', 'save_custom_fields_data');
function save_custom_fields_data($order_id) {
    // $order_id = $order->get_id();
    // print_r($_POST);exit();
    if (isset($_POST['registered_company']) && $_POST['registered_company']=="yes") {
        update_post_meta($order_id, 'registered_company', 'yes');
        update_post_meta($order_id, 'type_of_business', sanitize_text_field($_POST['type_of_business']));
        update_post_meta($order_id, 'gst_number', sanitize_text_field($_POST['gst_no']));

        if(sanitize_text_field($_POST['type_of_business']) == "Other"){
            update_post_meta($order_id, 'nature_of_business', sanitize_text_field($_POST['nature_of_business']));
        }
        else {
            update_post_meta($order_id, 'nature_of_business', sanitize_text_field($_POST['type_of_business']));
        }
    }
}


// Remove additional information tab
add_filter( 'woocommerce_product_tabs', 'remove_additional_information_tab', 100, 1 );
function remove_additional_information_tab( $tabs ) {
    unset($tabs['additional_information']);

    return $tabs;
}


/*
//add additional information detail below Tag
add_action( 'woocommerce_product_meta_end', 'additional_info_under_add_to_cart', 35 );
function additional_info_under_add_to_cart() {
    
    global $product;

    if ( $product ) {

        // Get the product attributes
        $attributes = $product->get_attributes();

        // Check if there are any attributes
        if ( !empty($attributes) ) {

            // Loop through the attributes and display them in a key-value pair format
            foreach ( $attributes as $attribute ) {
                $name = $attribute->get_name();
                $values = $attribute->get_options();
                ?>
                <div class="products-page-cats">
                    <span class="posted_in"><?=esc_html(ucfirst(str_replace('pa_', '', $name)));?>: 
                        <a href="javascript: void(0)" rel="tag">
                            <?php 
                            
                            if (str_contains($name, 'pa_')) { 
                                foreach ( $values as $value ) {
                                    $term = get_term_by( 'id', $value, $name );
                                    if ( $term ) {
                                        echo esc_html( $term->name ) . ' ';
                                    }
                                }
                            }
                            else{
                                echo implode(" ", $values);
                            }
                            ?>
                        </a>
                    </span>
                </div>
                <?php
            }
        }

    }
}

*/

add_action( 'woocommerce_product_meta_end', 'additional_info_under_add_to_cart', 35 );
function additional_info_under_add_to_cart() {
    global $product;

    if ( $product && $product->is_type( 'variable' ) && $product->is_visible() ) {

        // Get the product attributes
        $attributes = $product->get_attributes();

        // Check if there are any attributes
        if ( ! empty( $attributes ) ) {

            // Loop through the attributes and display them in a key-value pair format
            foreach ( $attributes as $attribute ) {
                if ( $attribute->get_visible() ) {
                    $name   = $attribute->get_name();
                    $values = $attribute->get_options();
                    ?>
                    <div class="products-page-cats">
                        <span class="posted_in"><?= esc_html( ucfirst( str_replace( 'pa_', '', $name ) ) ); ?>: 
                            <a href="javascript: void(0)" rel="tag">
                                <?php 

                                if ( str_contains( $name, 'pa_' ) ) { 
                                    foreach ( $values as $value ) {
                                        $term = get_term_by( 'id', $value, $name );
                                        if ( $term ) {
                                            echo esc_html( $term->name ) . ' ';
                                        }
                                    }
                                } else {
                                    echo implode( ' ', $values );
                                }
                                ?>
                            </a>
                        </span>
                    </div>
                    <?php
                }
            }
        }
    }
}






add_action( 'wpo_wcpdf_before_order_details', 'add_gst_number_and_business_to_invoice', 10, 2 );
function add_gst_number_and_business_to_invoice( $type, $order ) {
    $gst_number = get_post_meta( $order->get_id(), 'gst_number', true );
    $nature_of_business = get_post_meta( $order->get_id(), 'nature_of_business', true );
    if ( $gst_number ) {
        echo '<div class="billing-gst" style="margin-bottom:10px;"><span style="font-weight:bold;">' . __( 'GST Number:', 'your-text-domain' ) . '</span> ' . $gst_number . '</div>';
    }
    if ( $nature_of_business ) {
        echo '<div class="billing-nature" style="margin-bottom:10px;"><span style="font-weight:bold;">' . __( 'Nature of Business:', 'your-text-domain' ) . '</span> ' . $nature_of_business . '</div>';
    }
}


//Hide Price Range for WooCommerce Variable Products
add_filter( 'woocommerce_variable_sale_price_html', 'lw_variable_product_price', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'lw_variable_product_price', 10, 2 );

 

function lw_variable_product_price( $v_price, $v_product ) {

 

// Product Price
$prod_prices = array( $v_product->get_variation_price( 'min', true ),$v_product->get_variation_price( 'max', true ) );
$prod_price = $prod_prices[0]!==$prod_prices[1] ? sprintf(__('From: %1$s', 'woocommerce'),wc_price( $prod_prices[0] ) ) : wc_price( $prod_prices[0] );

 

// Regular Price
$regular_prices = array( $v_product->get_variation_regular_price( 'min', true ),  $v_product->get_variation_regular_price( 'max', true ) );
sort( $regular_prices );
$regular_price = $regular_prices[0]!==$regular_prices[1] ? sprintf(__('From: %1$s','woocommerce'), wc_price( $regular_prices[0] ) ) : wc_price( $regular_prices[0] );

 

if ( $prod_price !== $regular_price ) {
$prod_price = '<del>'.$regular_price.$v_product->get_price_suffix() . '</del> <ins>' . 
                       $prod_price . $v_product->get_price_suffix() . '</ins>';
}
return $prod_price;
}