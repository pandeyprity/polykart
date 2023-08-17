<?php
// Display the list of onboarding data
require_once(ABSPATH . 'wp-admin/includes/template.php');

function upload_gst_certificate($pk)
{
    // Check if the file was uploaded without errors
    if (isset($_FILES['gst_certificate']) && $_FILES['gst_certificate']['error'] == 0) {
        // Define the path where the file will be saved
        $upload_dir = wp_upload_dir();
        $target_dir = $upload_dir['basedir'] . '/wp-buyer-onboarding/';
        $filename = $pk . '__' . basename($_FILES['gst_certificate']['name']);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['gst_certificate']['tmp_name'], $target_dir . $filename)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'buyer_onboarding';
            $wpdb->update($table_name, ["gst_certificate" => $filename], ['id' => $pk]);
            return true;
        } else {
            return false;
        }
    }
}

function isEditMode(){
    if (isset($_GET['action']) && $_GET['action'] == 'edit') {
        return true;
    }
    else{
        return false;
    }
}

function wp_buyer_onboarding_create()
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'buyer_onboarding';

    // Handle delete requests
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $id = $_GET["id"];
        $result = $wpdb->update($table_name, ["status" => 0], ["id" => $id]);
        wp_redirect(admin_url() . "/admin.php?page=wp-buyer-onboarding-list");
        exit(0);
    }

    // Handle edit requests
    $brands__id = [];
    if (isset($_GET['action']) && $_GET['action'] == 'edit') {
        $id = $_GET["id"];
        $result = $wpdb->get_results("SELECT * FROM $table_name where id=$id");
        $result = $result[0];

        $temp = unserialize($result->brands);
        
        if($temp && is_array($temp))
        foreach($temp as $key=>$val)
        $brands__id[]= $val;
        
    }

    
    // Handle form submissions
    if (isset($_POST['submit'])) {
        // /print_r($_POST);exit;
        // Get form data
        $product_groups = sanitize_text_field($_POST['product_groups']);
        $sub_category = sanitize_text_field($_POST['sub_category']);
        $brands = serialize($_POST['brands']);
        $grades = sanitize_text_field($_POST['grades']);
        $contact_person = sanitize_text_field($_POST['contact_person']);
        $whatsapp_no = sanitize_text_field($_POST['whatsapp_no']);
        $email = sanitize_text_field($_POST['email']);
        $tan_no = sanitize_text_field($_POST['tan_no']);

        $data = array(
            'product_groups' => $product_groups,
            'sub_category' => $sub_category,
            'brands' => $brands,
            'grades' => $grades,
            'contact_person' => $contact_person,
            'whatsapp_no' => $whatsapp_no,
            'email' => $email,
            'tan_no' => $tan_no
        );

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $wpdb->update($table_name, $data, ['id' => $_POST['id']]);
            upload_gst_certificate($_POST['id']);
            wp_redirect(admin_url() . "/admin.php?page=wp-buyer-onboarding-list");
            exit(0);
        } else {
            //print_r($data);exit;
            $wpdb->insert($table_name, $data);
            upload_gst_certificate($wpdb->insert_id);
            wp_redirect(admin_url() . "/admin.php?page=wp-buyer-onboarding-list");
            exit(0);
        }
    }


    // Code added by anirban for product /sub categoy and brand
    $orderby = 'name';
    $order = 'asc';
    $hide_empty = false;
    $cat_args = array(
        'orderby'    => $orderby,
        'order'      => $order,
        'hide_empty' => $hide_empty,
    );


    $woo_cat_args = array(
        'taxonomy'     => 'product_cat',
        'orderby'      => 'name',
        'hide_empty'   => 0,
        'parent'       => 0
    );
    $woo_categories = get_categories($woo_cat_args);  // Fetch all categories.

    $sub_category = get_term_by( 'id', $result->sub_category, 'product_cat' );
    
    $brand = array(
        'taxonomy'     => 'brand'
    );
    $brands = get_terms('brand', $brand);  // Fetch all brands.

    $grades = array(
        'taxonomy' => 'pa_grade',
        'orderby' => 'name',
        'hide_empty' => 0,
        'parent' => 0
    );
    $allgrades = get_categories($grades); // Fetch all grades
?>
    <div class="wrap">
        <div class="wrap nosubsub">
            <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>
            <hr class="wp-header-end">

            <div class="col-wrap">
                <div class="form-wrap">
                    <h2><?php echo isset($_GET['id']) ? 'Edit Onboarding Data' : 'Add Onboarding Data'; ?></h2>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $result->id ?? null; ?>" />

                        <div class="form-field form-required term-name-wrap">
                            <label for="product_groups" class="required">Product Groups </label>
                            <select name="product_groups" id="product_groups">
                                <option value="">--Select--</option>
                                <?php foreach ($woo_categories as $key => $category) { ?>
                                    <option value="<?php echo $category->term_id;?>" <?=(($result->product_groups ?? null)==$category->term_id)?"selected":null;?>><?php echo $category->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-field form-required term-name-wrap">
                            <label for="sub_category" class="required">Sub Category</label>
                            <select name="sub_category" id="sub_category" onclick="" class="sub_category">
                                <?php
                                if(isEditMode()){
                                    echo '<option value="'.$sub_category->term_id.'">'.$sub_category->name.'</option>';
                                }
                                else{
                                    echo '<option value="">--Select--</option>';
                                }
                                ?>
                                
                            </select>
                        </div>

                        <div class="form-field form-required term-name-wrap">
                            <label for="brands" class="required">Brands </label>
                            <select name="brands[]" id="brands" multiple="multiple" class="obform-control select2" data-placeholder="" >

                                <?php foreach ($brands as $key => $brand) { ?>
                                    <option value="<?=$brand->term_id; ?>" <?php echo in_array($brand->term_id, $brands__id)?"selected":null;?>><?=$brand->name; ?></option>
                                <?php } ?>
                                <!-- <option value="33" data-select2-id="17">Brand 01</option>
                                <option value="85" data-select2-id="18">Brand 02</option>
                                <option value="83" data-select2-id="19">Brand 03</option>
                                <option value="86" data-select2-id="20">Brand 04</option>
                                <option value="84" data-select2-id="21">Brand 05</option> -->
                            </select>
                        </div>

                        <div class="form-field form-required term-name-wrap">
                            <label for="grades" class="required">Grades</label>
                            <select name="grades" id="grades" >
                                <option value="">--Select--</option> <?php foreach ($allgrades as $key => $grade) { ?> 
                                    <option value="<?=$grade->term_id;?>" <?=(($result->grades ?? null)==$grade->term_id)?"selected":null;?>> <?=$grade->name;?> </option> <?php } ?>
                            </select>
                        </div>

                        <div class="form-field form-required term-name-wrap">
                            <label for="contact_person" class="required">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" class="regular-text" value="<?= $result->contact_person ?? null; ?>"  />
                        </div>

                        <div class="form-field form-required term-name-wrap">
                            <label for="whatsapp_no" class="required">WhatsApp No</label>
                            <input type="tel" name="whatsapp_no" id="whatsapp_no" class="regular-text" value="<?= $result->whatsapp_no ?? null; ?>" maxlength="10"  />
                        </div>

                        <div class="form-field form-required term-name-wrap">
                            <label for="email" class="">Email</label>
                            <input type="email" name="email" id="email" class="regular-text" value="<?= $result->email ?? null; ?>"  />
                        </div>


                        <div class="form-field form-required term-name-wrap">
                            <label for="tan_no">Company TAN No</label>
                            <input type="text" name="tan_no" id="tan_no" class="regular-text" value="<?= $result->tan_no ?? null; ?>" maxlength="255">
                        </div>

                        <div class="form-field form-required term-name-wrap">
                            <label for="gst_certificate" class="<?=isEditMode()?null:"required";?>">GST Certificate</label>
                            <input type="file" name="gst_certificate" id="gst_certificate" class="regular-text" accept=".pdf" <?=isEditMode()?null:"required";?> />
                        </div>

                        <?php wp_nonce_field('add_buyer_onboarding_data', 'add_buyer_onboarding_data_nonce'); ?>
                        <?php submit_button('Add Data'); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
}