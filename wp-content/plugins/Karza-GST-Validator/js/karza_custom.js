jQuery(".update_api_setting").change(function(){
    

    var value = jQuery(this).attr("data-value") == "true" ? true: jQuery(this).attr("data-value");
    var column = jQuery(this).attr("name");
    //console.log(value, typeof value);

    if (jQuery(this).prop('checked')!=true) {
        if(typeof value == "boolean")
        value=value?false:true;
        else
        value=value=="Y"?"N":"Y";
    }
    
    jQuery("#ajax_status").html('<span class="dashicons dashicons-image-rotate spinner-blue"></span>');
    jQuery.ajax({
        url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
        type: 'POST',
        dataType: "json",
        data: {
            action: 'update_karza_api_setting', // this is the function in your functions.php that will be triggered
            column: column,
            value: value,
        },
        success: function(data) {
            console.log( data );
            if(data.status==true){
                jQuery("#ajax_status").html('<span class="dashicons dashicons-saved dashicons-green"></span>');
                setTimeout(function(){
                    jQuery("#ajax_status").html(null);
                }, 5000);
            }
        }
    });
});

function update_key(obj){
    var value = jQuery(obj).val();
    var column = jQuery(obj).attr("name");

    jQuery("#ajax_status").html('<span class="dashicons dashicons-image-rotate spinner-blue"></span>');
    jQuery.ajax({
        url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
        type: 'POST',
        dataType: "json",
        data: {
            action: 'update_karza_api_setting', // this is the function in your functions.php that will be triggered
            column: column,
            value: value,
        },
        success: function(data) {
            console.log( data );
            if(data.status==true){

                //manage readonly and spinner
                jQuery(obj).prop('readonly', true).attr("data-value", value).val(value);
                jQuery("#ajax_status").html('<span class="dashicons dashicons-saved dashicons-green"></span>');
                setTimeout(function(){
                    jQuery("#ajax_status").html(null);
                }, 5000);
            }
        }
    });
}

function sendOTP(){

    if(validateKarzaLogin()){
        var username = jQuery("#karza_username").val();
        var gstin = jQuery("#gstin").val();

        jQuery("#karza_submit_btn").prop('disabled', true);
        jQuery(".karza_submit_btn_rotate").show();
        
        jQuery.ajax({
            url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
            type: 'POST',
            dataType: "json",
            data: {
                action: 'sendOTP', // this is the function in your functions.php that will be triggered
                username: username,
                gstin: gstin,
                refid: null,
            },
            success: function(data) {
                console.log(data);
                // statusMessage: "Success"
                if(data.statusCode===101){
                    jQuery("#login_form").hide();
                    jQuery("#response_form").hide();
                    jQuery("#otp_form").show();
                    jQuery("#requestId").val(data.requestId);
                }
                else if(data.statusCode===102) {
                    jQuery(".errorMessage").html("Please use valid username and gstin");
                }
                else if(typeof data.status !== 'undefined' && data.status===401) {
                    jQuery(".errorMessage").html(data.error);
                }
                else if(typeof data.status !== 'undefined' && data.status===402) {
                    jQuery(".errorMessage").html(data.error);
                }
                else {
                    jQuery(".errorMessage").html(data.statusMessage);
                }

                jQuery("#karza_submit_btn").prop('disabled', false);
                jQuery(".karza_submit_btn_rotate").hide();
            }
        });
    }
    
}

function verifyOTP(){
    if(validateOTPType()){
        var otp = jQuery("#otp").val();
        var requestId = jQuery("#requestId").val();
        jQuery("#karza_verify_btn").prop('disabled', true);
        jQuery(".karza_submit_btn_rotate").show();
        
        jQuery.ajax({
            url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
            type: 'POST',
            dataType: "json",
            data: {
                action: 'verifyOTP', // this is the function in your functions.php that will be triggered
                otp: otp,
                requestId: requestId
            },
            success: function(data) {
                console.log(data);
                // statusMessage: "Success"
                if(data.statusCode===101){
                    jQuery("#login_form").hide();
                    jQuery("#otp_form").hide();
                    jQuery("#response_form").show();
                }
                else if(data.statusCode===102) {
                    jQuery(".errorMessage").html("Please enter valid otp");
                }
                else if(typeof data.status !== 'undefined' && data.status===401) {
                    jQuery(".errorMessage").html(data.error);
                }
                else {
                    jQuery(".errorMessage").html(data.statusMessage);
                }
                jQuery("#karza_verify_btn").prop('disabled', false);
                jQuery(".karza_submit_btn_rotate").hide();
            }
        });
    }
}


function validateKarzaLogin(){
    jQuery(".errorMessage").html(null);
    jQuery('#karza_usernameError').html(null);
    jQuery('#gstinError').html(null);

    var karza_username = jQuery('#karza_username').val();
    var gstin = jQuery('#gstin').val();

    if (karza_username == '') {
        jQuery("#karza_usernameError").html("Please Enter Username");
        return false;
    }

    if (gstin == '') {
        jQuery("#gstinError").html("Please Enter GST No");
        return false;
    }

    return true;
}

function validateOTPType(){
    jQuery('#otpError').html(null);
    jQuery(".errorMessage").html(null);
    var otp = jQuery('#otp').val();

    if (otp == '') {
        jQuery("#otpError").html("Please Enter One Time Password");
        return false;
    } else if (isNaN(otp)) {
        jQuery("#otpError").html("OTP must be a number");
        return false;
    } else if (otp.length != 6) {
        jQuery("#otpError").html("OTP must be 6 digits.");
        return false;
    }

    return true;
}

function showActualData(obj){
    jQuery(obj).prop('readonly', false);
    jQuery(obj).val(jQuery(obj).attr("data-value"));
    jQuery(obj).focus();
}