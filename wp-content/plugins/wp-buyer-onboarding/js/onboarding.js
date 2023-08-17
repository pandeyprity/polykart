$(document).on("click", ".mult-select-tag .input-container", function(){
    $('.mult-select-tag .btn-container button').trigger("click");
});

jQuery(document).ready(function($) {
 new MultiSelectTag('brands', {
    rounded: true,    // default true
    shadow: true      // default false
})
  //intialize select 2
  //$('.select2').select2();
 

  $(".allow_decimal").keypress(function(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
        return false;
        
        return true;
  });

  $(".update_record").change(function(){
    var product_id = $(this).attr("data-product_id");
    var value = $(this).val();
    var column = $(this).attr("name");

    ShowUpdating(product_id);
    $.ajax({
        url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
        type: 'POST',
        dataType: "json",
        data: {
            action: 'updateProduct', // this is the function in your functions.php that will be triggered
            product_id: product_id,
            column: column,
            value: value,
        },
        success: function(data) {
            console.log( data );
            if(data.status==true){
                showSaved(product_id);
            }
        }
    });
  });

  $(".update_notification").change(function(){

    var product_id = $(this).attr("data-product_id");
    var column = $(this).attr("name");
    var value= 0;
    if ($(this).prop('checked')==true){ 
        var value = 1;
    }
    ShowUpdating(product_id);
    $.ajax({
        url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
        type: 'POST',
        dataType: "json",
        data: {
            action: 'updateProduct', // this is the function in your functions.php that will be triggered
            product_id: product_id,
            column: column,
            value: value,
        },
        success: function(data) {
            console.log( data );
            if(data.status==true){
                showSaved(product_id);
            }
        }
    });
  });

  $("#product_groups").change(function() {
      var pid = $(this).find(":selected").val();
      $.ajax({
          url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
          type: 'POST',
          dataType: "json",
          data: {
              action: 'myaction', // this is the function in your functions.php that will be triggered
              pid: pid,
          },
          success: function(data) {
              //console.log( data );
              $('#sub_category').html(data.html);
          }
      });
  });

  $("#onboardingForm").submit(function(e) {
    e.preventDefault();
    $("#submit").html('<span class="dashicons dashicons-image-rotate"></span>').prop('disabled', true);
    saveForm();
    
  });
});

function ShowUpdating(product_id) {
    jQuery("#status_" + product_id).removeAttr('class');
    jQuery("#status_" + product_id).addClass("dashicons dashicons-image-rotate spin");
}

function showSaved(product_id){
    jQuery("#status_" + product_id).removeAttr('class');
    jQuery("#status_" + product_id).addClass("dashicons dashicons-saved dashicons-green");
    setTimeout(function(){
        jQuery("#status_" + product_id).removeAttr('class');
    }, 5000);
}
function saveForm(){
    var product_groups = jQuery('#product_groups').val();
    var sub_category = jQuery('#sub_category').val();
    var brands = jQuery('#brands').val();
    var grades = jQuery('#grades').val();
    var contact_person = jQuery('#contact_person').val();
    var whatsapp_no = jQuery('#whatsapp_no').val();
    var email = jQuery('#email').val();
    var tan_no = jQuery('#tan_no').val();
    var state = jQuery('#state').val();

    var data = {
        action: 'saveForm', // this is the function in your functions.php that will be triggered
        product_groups: product_groups,
        sub_category: sub_category,
        brands: brands,
        grades: grades,
        contact_person: contact_person,
        whatsapp_no: whatsapp_no,
        email: email,
        tan_no: tan_no,
        state: state
    }

    const res = jQuery.ajax({
        url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
        type: 'POST',
        dataType: "json",
        data: data,
        success: function(data) {
            
            console.log( data );
            if(data.status){
                jQuery("#last_id").val(data.last_id);
                return upload_gst_certificate_ajax();
            }
            else{
                console.log(data.message);
                return false;
            }
        }
    });
    return res;
}

function upload_gst_certificate_ajax(){

    var last_id = jQuery("#last_id").val();
    var fileInput = jQuery('#gst_certificate')[0];
    var file = fileInput.files[0];
    var formData = new FormData();
    formData.append('gst_certificate', file);
    formData.append('id', last_id);
    formData.append('action', "upload_gst_certificate_ajax");

    const res = jQuery.ajax({
        url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
        type: 'POST',
        //dataType: "json",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            data= JSON.parse(data);
            console.log( data );
            if(data.status){
                jQuery("#submit").html('Submit').prop('disabled', false);
                jQuery(".formDiv").hide();
                jQuery(".thankDiv").show();
                
                // focus on thankyou message
                $('html, body').animate({
                    scrollTop: $(".etheme-icon-list").offset().top
                  }, 1000);
            }
            else{
                console.log(data.message);
                return false;
            }
        }
    });
    return res;
}
function validateForm() {
  //alert('hello');
  jQuery('#product_groupsError').html(null);
  jQuery('#sub_categoryError').html(null);
  jQuery('#brandsError').html(null);
  jQuery('#gradesError').html(null);
  jQuery('#contact_personError').html(null);
  jQuery('#whatsapp_noError').html(null);
  jQuery('#emailError').html(null);
  jQuery('#tan_noError').html(null);
  jQuery('#gst_certificateError').html(null);

  var product_groups = jQuery('#product_groups').val();
  var sub_category = jQuery('#sub_category').val();
  var brands = jQuery('#brands').val();
  var grades = jQuery('#grades').val();
  var contact_person = jQuery('#contact_person').val();
  var whatsapp_no = jQuery('#whatsapp_no').val();
  var email = jQuery('#email').val();
  var tan_no = jQuery('#tan_no').val();
  var gst_certificate = jQuery('#gst_certificate').val();

  if (product_groups == '') {
      jQuery("#product_groupsError").html("Please Choose Product Groups");
      return false;
  }
  
  /*
  if (sub_category == '') {
      jQuery("#sub_categoryError").html("Please Choose Sub Category");
      return false;
  }
  
  if (brands == null || brands.length == 0) {
      jQuery("#brandsError").html("Please Choose Brands");
      return false;
  }

  if (jQuery("#brands").find('option:selected').length < 3) {
    jQuery("#brandsError").html("Please select at least top 3 brands");
    return false;
  }
  */
  if (grades == '') {
      jQuery("#gradesError").html("Please Choose Grades");
      return false;
  }

  if (contact_person == '') {
      jQuery("#contact_personError").html("Please Enter Contact Person Name");
      return false;
  }

  if (whatsapp_no == '') {
      jQuery("#whatsapp_noError").html("Please Enter Whatsapp No");
      return false;
  } else if (isNaN(whatsapp_no)) {
      jQuery("#whatsapp_noError").html("WhatsApp No field must be a number");
      return false;
  } else if (whatsapp_no.length != 10) {
      jQuery("#whatsapp_noError").html("WhatsApp No field must be 10 digits.");
      return false;
  }

  if (email != '') {
      var email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
      if (!email_regex.test(email)) {
          jQuery("#emailError").html("Email field is invalid.");
          return false;
      }
  }

  if (gst_certificate == '') {
      jQuery("#gst_certificateError").html("Please Upload GST Certificate");
      return false;
  }

  if(!fileValidation()){
      jQuery("#gst_certificateError").html("Please upload .pdf file only");
      return false;
  }

  return true;
}

function fileValidation(){
  var fileInput = document.getElementById('gst_certificate');
  var filePath = fileInput.value;
  var allowedExtensions = /(\.pdf)$/i;
  if(!allowedExtensions.exec(filePath)){
      return false;
  }else{
      return true;
  }
}