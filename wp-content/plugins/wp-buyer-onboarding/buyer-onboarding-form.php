<?php
// Display the list of onboarding data
require_once(ABSPATH . 'wp-admin/includes/template.php');


function wp_buyer_onboarding_form()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'buyer_onboarding';

    // Handle form submissions
    if (isset($_POST['submit'])) {
        // Now Files are saving via ajax method
        
    }


    // Code added by anirban for product /sub categoy and brand
    $orderby = 'name';
    $order = 'asc';
    $hide_empty = false;
    $cat_args = array(
        'orderby' => $orderby,
        'order' => $order,
        'hide_empty' => $hide_empty,
    );

    $woo_cat_args = array(
        'taxonomy' => 'product_cat',
        'orderby' => 'name',
        'hide_empty' => 0,
        'parent' => 0
    );
    $woo_categories = get_categories($woo_cat_args); // Fetch all categories.

    $brand = array(
        'taxonomy' => 'brand'
    );
    $brands = get_terms('brand', $brand); // Fetch all brands.



    $grades = array(
        'taxonomy' => 'pa_grade',
        'orderby' => 'name',
        'hide_empty' => 0,
        'parent' => 0
    );
    $allgrades = get_categories($grades); // Fetch all categories
    ?>
      
    <div class="wrap">
        <div class="wrap nosubsub formDiv">
            <div class="elementor-element elementor-element-a254bd8 elementor-widget elementor-widget-heading" data-id="a254bd8" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
			    <h5 class="elementor-heading-title elementor-size-xl"><b style="border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; font-size: 13px; line-height: inherit; font-family: &quot;Open Sans&quot;, Arial, Arimo, Helvetica, sans-serif; vertical-align: baseline; color: rgb(68, 68, 68); text-transform: none; white-space: normal;"><span class="size" style="border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 21.3333px; line-height: inherit; font-family: inherit; vertical-align: baseline;">Buyer Onboarding Form</span></b></h5>		</div>
			</div>
            <h1 class="wp-heading-inline"> <?php echo esc_html(get_admin_page_title()); ?> </h1>
            <div class="col-wrap">
                <div class="form-wrap">
                    
                    <form id="onboardingForm" method="post" enctype="multipart/form-data">
                        <div class="form-field form-required term-name-wrap costom_input">
                            <label for="product_groups" class="required_astrisk">Product Groups </label>
                            <select name="product_groups" id="product_groups" class="" required>
                                <option value="">--Select--</option> 
                                <?php foreach ($woo_categories as $key => $category) { ?> 
                                    <option value="<?php echo $category->term_id; ?>"> <?php echo $category->name; ?> </option>
                                     <?php 
                                    } ?>
                            </select>
                            <span class="formError" id="product_groupsError"></span>
                        </div>
                        <div class="form-field form-required term-name-wrap costom_input" required>
                            <label for="sub_category" class="">Sub Category</label>
                            <select name="sub_category" id="sub_category" onclick="" class="sub_category" class="">
                                <option value="">--Select--</option>
                            </select>
                            <span class="formError" id="sub_categoryError"></span>
                        </div>
                        <div class="form-field form-required term-name-wrap costom_input">
                            <label for="brands" class="required_astrisk">Brands</label> <br />
                            <select name="brands[]" id="brands" multiple="multiple" class="regular-text myinput" data-placeholder=""> 
                                <?php foreach ($brands as $key => $brand) { ?> 
                                    <option value="<?php echo $brand->term_id; ?>"> <?php echo $brand->name; ?> </option> <?php } ?> 

                                    <!-- <option value="33" data-select2-id="17">Brand 01</option>
                                    <option value="85" data-select2-id="18">Brand 02</option>
                                    <option value="83" data-select2-id="19">Brand 03</option>
                                    <option value="86" data-select2-id="20">Brand 04</option>
                                    <option value="84" data-select2-id="21">Brand 05</option> -->
                            </select>
                            <span class="formError" id="brandsError"></span>
                        </div>
                        <div class="form-field form-required term-name-wrap costom_input">
                            <label for="grades" class="required_astrisk">Grades</label>
                            <label for="grades" class="" style="float: right; color: #6c757dc7!important;">Click here to <a href="<?=home_url();?>/grades" target="_blank">know more</a> </label>
                            <select name="grades" id="grades" required class="">
                                <option value="">--Select--</option> <?php foreach ($allgrades as $key => $grade) { ?> 
                                    <option value="<?php echo $grade->term_id; ?>"> <?php echo $grade->name; ?> </option> <?php } ?>
                            </select>
                            <span class="formError" id="gradesError"></span>
                        </div>
                          <div class="form-field form-required term-name-wrap costom_input">
                            <label for="statelist" class="required_astrisk">Select State </label>
                            <select name="state" id="state" class="" required>
                                  <option value="Andhra Pradesh">--State--</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
<option value="Arunachal Pradesh">Arunachal Pradesh</option>
<option value="Assam">Assam</option>
<option value="Bihar">Bihar</option>
<option value="Chandigarh">Chandigarh</option>
<option value="Chhattisgarh">Chhattisgarh</option>
<option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
<option value="Daman and Diu">Daman and Diu</option>
<option value="Delhi">Delhi</option>
<option value="Lakshadweep">Lakshadweep</option>
<option value="Puducherry">Puducherry</option>
<option value="Goa">Goa</option>
<option value="Gujarat">Gujarat</option>
<option value="Haryana">Haryana</option>
<option value="Himachal Pradesh">Himachal Pradesh</option>
<option value="Jammu and Kashmir">Jammu and Kashmir</option>
<option value="Jharkhand">Jharkhand</option>
<option value="Karnataka">Karnataka</option>
<option value="Kerala">Kerala</option>
<option value="Madhya Pradesh">Madhya Pradesh</option>
<option value="Maharashtra">Maharashtra</option>
<option value="Manipur">Manipur</option>
<option value="Meghalaya">Meghalaya</option>
<option value="Mizoram">Mizoram</option>
<option value="Nagaland">Nagaland</option>
<option value="Odisha">Odisha</option>
<option value="Punjab">Punjab</option>
<option value="Rajasthan">Rajasthan</option>
<option value="Sikkim">Sikkim</option>
<option value="Tamil Nadu">Tamil Nadu</option>
<option value="Telangana">Telangana</option>
<option value="Tripura">Tripura</option>
<option value="Uttar Pradesh">Uttar Pradesh</option>
<option value="Uttarakhand">Uttarakhand</option>
<option value="West Bengal">West Bengal</option>

                            </select>
                            <span class="formError" id="stateError"></span>
                        </div>
        
                        <div class="form-field form-required term-name-wrap costom_input">
                            <label for="contact_person" class="required_astrisk">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" class="regular-text myinput" value="" required />
                            <span class="formError" id="contact_personError"></span>
                        </div>
                        <div class="form-field form-required term-name-wrap costom_input">
                            <label for="whatsapp_no" class="required_astrisk">WhatsApp No</label>
                            <input type="tel" name="whatsapp_no" id="whatsapp_no" class="regular-text myinput" value="" maxlength="10" required />
                            <span class="formError" id="whatsapp_noError"></span>
                        </div>
                        <div class="form-field form-required term-name-wrap costom_input">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="regular-text myinput" value=""  />
                            <span class="formError" id="emailError"></span>
                        </div>
                                
                                <div class="form-field form-required term-name-wrap costom_input">
                            <label for="email">If interested in credit, you can <a href="<?=home_url();?>/credit-request" target="blank" style="color:#3a6cae;"><b>Apply Here</b></a></label>
                           
                        </div>
                        <div class="form-field form-required term-name-wrap costom_input">
                            <label for="tan_no">Company TAN No</label>
                            <input type="text" name="tan_no" id="tan_no" class="regular-text myinput" value="" maxlength="255" />
                            <span class="formError" id="tan_noError"></span>
                        </div>
                        <div class="form-field form-required term-name-wrap costom_input">
                            <label for="gst_certificate" class="required_astrisk">GST Certificate</label>
                            <input type="file" name="gst_certificate" id="gst_certificate" class="regular-text myinput" accept=".pdf" />
                            <span class="formError" id="gst_certificateError"></span>
                        </div>
                        <div class="form-field term-name-wrap costom_input">
                            <label></label>
                           <center>
                                <button type="submit" name="submit" id="submit" class="elementor-button" onclick="return validateForm();" style="font-size: 16px; font-weight: 500; fill: #FFFFFF; color: #FFFFFF; background-color: #0B3954; --fill-color: #FFFFFF; border-radius: 4px 4px 4px 4px; padding: 16px 36px 16px 36px;">Submit</button>
                                <input type="hidden" name="last_id" id="last_id" />
                            </center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="wrap nosubsub thankDiv" style="display: none;">
            <div class="col-wrap" style="text-align: center; ">
                <div id="container_obsm">
                    <p style="font-weight: 500; color: #0a8103;">
                        Thank you form submitted successfully.
                    </p>
                </div>
            </div>
        </div>
    </div>
    

<?php
}

add_shortcode('buyer_onboarding_form', 'wp_buyer_onboarding_form');
?>