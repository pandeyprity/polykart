<?php

// Enqueue dashicons font
function enqueue_dashicons() {
    wp_enqueue_style( 'dashicons' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_dashicons' );

add_action('wp_enqueue_scripts', 'include_required_css_and_js');
add_action('admin_enqueue_scripts', 'include_required_css_and_js');
function include_required_css_and_js() {
    wp_enqueue_style('onboarding_style', plugins_url('/css/onboarding.css', __FILE__));

  // wp_enqueue_style('select2_style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css');
  // wp_enqueue_script('select2_js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array(), false, true);
    
    //wp_enqueue_style('select2_style', '/js/choice-css.css');
   //wp_enqueue_script('select2_js', '/js/choice-js.js', array(), false, true);
    
  wp_enqueue_style('multi-select-tag-custom', plugins_url('/css/multi-select-tag-custom.css', __FILE__));
  wp_enqueue_script('multi-select-tag-custom', plugins_url('/js/multi-select-tag-custom.js', __FILE__), array(), false, true);
}


/* Anirban added for ajax calling */
add_action('admin_enqueue_scripts', 'so_enqueue_scripts');
add_action('wp_enqueue_scripts', 'so_enqueue_scripts');
function so_enqueue_scripts(){
  wp_register_script('ajaxHandle', plugins_url('/js/onboarding.js', __FILE__), array(), false, true);
  wp_enqueue_script('ajaxHandle');
  wp_localize_script('ajaxHandle', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action("wp_ajax_myaction", "so_wp_ajax_function" );
add_action("wp_ajax_nopriv_myaction", "so_wp_ajax_function" );
function so_wp_ajax_function(){

    $parent_id=$_POST['pid'];    
    $child_args = array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent'   => $parent_id
    );
    $child_product_cats = get_terms( $child_args );
    $searchResultHTML = null;
    if(!empty($child_product_cats)){
      foreach ($child_product_cats as $child_product_cat)
      {
          $searchResultHTML .= '<option value="'.$child_product_cat->term_id.'">'.$child_product_cat->name.'</option>';
      }
    }
    else
    {
        $searchResultHTML .= '<option value="">No Subcategories</option>';
    } 

    $data = array("html"  => $searchResultHTML);
    echo json_encode($data);
    wp_die();
}

add_action("wp_ajax_saveForm", "saveForm" );
add_action("wp_ajax_nopriv_saveForm", "saveForm" );


add_action("wp_ajax_upload_gst_certificate_ajax", "upload_gst_certificate_ajax" );
add_action("wp_ajax_nopriv_upload_gst_certificate_ajax", "upload_gst_certificate_ajax" );

add_action("wp_ajax_updateProduct", "updateProduct" );
add_action("wp_ajax_nopriv_updateProduct", "updateProduct" );


function saveForm(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'buyer_onboarding';
    $id = $_POST['product_groups'];
    $sub_category = $_POST['sub_category'];

    if ($term = get_term_by('id', $id, 'product_cat')) {
        $product_groups = $term->name;
    }
    if ($terms = get_term_by('id', $sub_category, 'product_cat')) {
        $sub_category = $terms->name;
    }

    $product_groups = sanitize_text_field($_POST['product_groups']);
    $sub_category = sanitize_text_field($_POST['sub_category']);
    $brands = serialize($_POST['brands']);
    $grades = sanitize_text_field($_POST['grades']);
    $contact_person = sanitize_text_field($_POST['contact_person']);
    $whatsapp_no = sanitize_text_field($_POST['whatsapp_no']);
    $email = sanitize_text_field($_POST['email']);
    $tan_no = sanitize_text_field($_POST['tan_no']);
    $state = sanitize_text_field($_POST['state']);
    $data = array(
        'product_groups' => $product_groups,
        'sub_category' => $sub_category,
        'brands' => $brands,
        'grades' => $grades,
        'contact_person' => $contact_person,
        'whatsapp_no' => $whatsapp_no,
        'email' => $email,
        'tan_no' => $tan_no,
        'state' => $state
    );
    $wpdb->insert($table_name, $data);
    // upload_gst_certificate($wpdb->insert_id);
    if ($wpdb->insert_id) {
        // $response=sendWhatsApMessage($wpdb->insert_id);
        echo json_encode(["status"=> true, "message"=> "Record Inserted Successfully", "last_id"=> $wpdb->insert_id]);
    }else{
        echo json_encode(["status"=> false, "message"=> "Something went wrong"]);
    }
    wp_die();
}


function upload_gst_certificate_ajax(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'buyer_onboarding';

    // Check if the file was uploaded without errors
    if (isset($_FILES['gst_certificate']) && $_FILES['gst_certificate']['error'] == 0) {
        // Define the path where the file will be saved
        $pk = $_POST["id"];
        $upload_dir = wp_upload_dir();
        $target_dir = $upload_dir['basedir'] . '/wp-buyer-onboarding/';
        $filename = $pk . '__' . basename($_FILES['gst_certificate']['name']);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['gst_certificate']['tmp_name'], $target_dir . $filename)) {
            
            $wpdb->update($table_name, ["gst_certificate" => $filename], ['id' => $pk]);
            echo json_encode(["status"=> true, "message"=> "GST File uploaded successfully"]);
        } else {
            echo json_encode(["status"=> false, "message"=> "Something went wrong, file could not upload"]);
        }
    }
    wp_die();
}

function updateProduct(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'product_notification';
    $product_id = $_POST['product_id'];
    $column = $_POST['column'];
    $value = $_POST['value'];
    
    $product=GetWoocommerceProduct($product_id);
    $response = ["status"=> false, "message"=> "Something went wrong"];

    if($product){
        $rec = $wpdb->get_row("select * from $table_name where product_id=$product_id and status=1");
        // Update
        
        if($rec) {
            $data = [
                "product_id" => $product_id,
                "product_name" => $product->product_name,
                "category" => $product->category,
                "brand" => $product->brand,
                $column => $value
            ];
            $updated=$wpdb->update($table_name, $data, ['product_id' => $product_id, "status"=> 1]);
            if($updated !== false){
                $response= ["status"=> true, "message"=> "Updated successfuly"];
            }
            
        }
        //Insert
        else {

            $data = [
                "product_id" => $product_id,
                "product_name" => $product->product_name,
                "category" => $product->category,
                "brand" => $product->brand,
                $column => $value
            ];
            
            $wpdb->insert($table_name, $data);
            if($wpdb->insert_id){
                $response = ["status"=> true, "message"=> "Inserted/Updated successfully"];
            }
        }
    }
    
    echo json_encode($response);
    wp_die();
}

function get_product_cat($category_id){
    global $wpdb;
    $data = $wpdb->get_row("SELECT name FROM wp_terms
    INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
    WHERE wp_term_taxonomy.taxonomy = 'product_cat' AND wp_terms.term_id = $category_id;");
    
    return $data;
}

function filterProducts($product_groups, $sub_category){
    global $wpdb;
    $table_name = $wpdb->prefix . 'product_notification';
    $result=$wpdb->get_results("select * from $table_name where notification=1 and  category in ('$product_groups', '$sub_category', '$sub_category, $product_groups') and status=1");
    return $result;
}



function sendWhatsApMessage($buyer_onboarding_id) {

    global $wpdb;
    $table_name = $wpdb->prefix . 'buyer_onboarding';
    $buyer_onboarding=$wpdb->get_row("select * from $table_name where id=$buyer_onboarding_id limit 1");
    // print_r($buyer_onboarding);
    $product_groups = get_product_cat($buyer_onboarding->product_groups);
    $sub_category = get_product_cat($buyer_onboarding->sub_category);
    $brands__ids = unserialize($buyer_onboarding->brands);
    if($brands__ids && is_array($brands__ids)) $brands__ids = implode(", ", $brands__ids);

    
    $products=$wpdb->get_results("SELECT DISTINCT p.ID AS product_id,
            p.post_title AS product_name,
            GROUP_CONCAT(DISTINCT cat.name SEPARATOR ', ') AS category,
            GROUP_CONCAT(DISTINCT brand.name SEPARATOR ', ') AS brand,
        GROUP_CONCAT(DISTINCT grade.name SEPARATOR ', ') AS grade, 250 as price
        FROM wp_posts AS p
        INNER JOIN wp_term_relationships AS tr ON p.ID = tr.object_id
        INNER JOIN wp_term_taxonomy AS cat_tt ON cat_tt.term_taxonomy_id = tr.term_taxonomy_id
        INNER JOIN wp_terms AS cat ON cat.term_id = cat_tt.term_id AND cat_tt.taxonomy = 'product_cat'

        LEFT JOIN wp_term_relationships AS tr2 ON p.ID = tr2.object_id
        LEFT JOIN wp_term_taxonomy AS brand_tt ON brand_tt.term_taxonomy_id = tr2.term_taxonomy_id
        LEFT JOIN wp_terms AS brand ON brand.term_id = brand_tt.term_id AND brand_tt.taxonomy = 'brand'

        LEFT JOIN wp_term_relationships AS tr3 ON p.ID = tr3.object_id
        LEFT JOIN wp_term_taxonomy AS grade_tt ON grade_tt.term_taxonomy_id = tr3.term_taxonomy_id
        LEFT JOIN wp_terms AS grade ON grade.term_id = grade_tt.term_id AND grade_tt.taxonomy = 'pa_grade'
        
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        and brand.term_id in ($brands__ids) and cat.term_id in ($buyer_onboarding->product_groups, $buyer_onboarding->sub_category) and grade.term_id in ($buyer_onboarding->grades)
        GROUP BY p.ID, product_name
        ORDER BY category ASC, brand ASC, product_name ASC limit 5;");
    
    if($products){
        require_once(ONBOARDING_DIR . '/Curl/autoload.php');
        $curl = new Curl();
        $curl->setHeader('content-type', 'application/json');
        $curl->setHeader('Authorization', "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIyZDM5MTNjYi0yNzBjLTQyOGEtOTdmMi04Yzg2MGZmNWRhMGUiLCJ1bmlxdWVfbmFtZSI6ImhhcnNoc2hhcm1hMjNAbGl2ZS5jb20iLCJuYW1laWQiOiJoYXJzaHNoYXJtYTIzQGxpdmUuY29tIiwiZW1haWwiOiJoYXJzaHNoYXJtYTIzQGxpdmUuY29tIiwiYXV0aF90aW1lIjoiMDQvMTkvMjAyMyAxMToxNzowNiIsImRiX25hbWUiOiIxMDU0MjQiLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.OHjkJroez5XOue226EeSpJmVL5z7Dpv943YmtWc1nJs");

        # add Contacts
        $response=$curl->post("https://live-server-105424.wati.io/api/v1/addContact/91".$buyer_onboarding->whatsapp_no, [
            "name"=> $buyer_onboarding->contact_person,
            "customParams"=> array()
        ]);
        
        # send wa message
        $response=$curl->post("https://live-server-105424.wati.io/api/v1/sendTemplateMessage?whatsappNumber=91".$buyer_onboarding->whatsapp_no, [
            "template_name"=> "buyer_onboarding_1",
            "broadcast_name"=> "product_notification",
            "parameters"=> [
                [
                    "name"=> "1",
                    "value"=> $product_groups->name
                ],
                [
                    "name"=> "2",
                    "value"=> $sub_category->name ?? " "
                ],
                [
                    "name"=> "3",
                    "value"=> isset($products[0]->product_name) ? $products[0]->product_name . " @ INR ". $products[0]->price . "/Kg" : " "
                ],
                [
                    "name"=> "4",
                    "value"=> isset($products[1]->product_name) ? $products[1]->product_name . " @ INR ". $products[1]->price . "/Kg" : " "
                ],
                [
                    "name"=> "5",
                    "value"=> isset($products[2]->product_name) ? $products[2]->product_name . " @ INR ". $products[2]->price . "/Kg" : " "
                ],
                [
                    "name"=> "6",
                    "value"=> isset($products[3]->product_name) ? $products[3]->product_name . " @ INR ". $products[3]->price . "/Kg" : " "
                ],
                [
                    "name"=> "7",
                    "value"=> isset($products[4]->product_name) ? $products[4]->product_name . " @ INR ". $products[4]->price . "/Kg" : " "
                ],
                [
                    "name"=> "8",
                    "value"=> "Amit@9892317426"
                ]
            ]
        ]);

        if($response->result)
        return ["status"=> $response->result, "message"=> "WA message sent"];
        else
        return ["status"=> $response->result, "message"=> $response->info];
    }
    return ["status"=> false, "message"=> "No Products found in this category"];
}


require_once(ONBOARDING_DIR . '/buyer-onboarding-form.php');
require_once(ONBOARDING_DIR . '/buyer-onboarding-list.php');
require_once(ONBOARDING_DIR . '/buyer-onboarding-create.php');
require_once(ONBOARDING_DIR . '/buyer-onboarding-product-list.php');

?>