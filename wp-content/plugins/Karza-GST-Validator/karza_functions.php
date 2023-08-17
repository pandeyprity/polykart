<?php

add_action('wp_enqueue_scripts', 'include_karza_css_and_js');
add_action('admin_enqueue_scripts', 'include_karza_css_and_js');
function include_karza_css_and_js() {
  wp_enqueue_style('karza_custom', plugins_url('/css/karza_custom.css', __FILE__));

}


/* Anirban added for ajax calling */
add_action('admin_enqueue_scripts', 'so_enqueue_ajaxscripts');
add_action('wp_enqueue_scripts', 'so_enqueue_ajaxscripts');
function so_enqueue_ajaxscripts(){
    wp_register_script('karza_ajaxHandle', plugins_url('js/karza_custom.js', __FILE__), array(), false, true);
    wp_enqueue_script('karza_ajaxHandle');
    wp_localize_script('karza_ajaxHandle', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action("wp_ajax_sendOTP", "sendOTP");
add_action("wp_ajax_nopriv_sendOTP", "sendOTP");

add_action("wp_ajax_verifyOTP", "verifyOTP");
add_action("wp_ajax_nopriv_verifyOTP", "verifyOTP");

add_action("wp_ajax_update_karza_api_setting", "update_karza_api_setting");
add_action("wp_ajax_nopriv_update_karza_api_setting", "update_karza_api_setting");

add_action("wp_ajax_validateGSTNo", "validateGSTNo");
add_action("wp_ajax_nopriv_validateGSTNo", "validateGSTNo");

function validateGSTNo()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'karza_api_setting';
    $gstin = sanitize_text_field($_POST["gst_no"]);
    if ($api_setting = validateApiSetting()) {
        require_once(KARZA_DIR . '/Curl/autoload.php');
        $curl = new Curl();
        $curl->setHeader('content-type', 'application/json');
        $curl->setHeader('x-karza-key', $api_setting->karza_key);
        // https://api.karza.in/gst/uat/v2/gst-verification
        // https://mocki.io/v1/29f9d503-4fcf-43b8-9f9e-e9ac7bb67382 -- fail
        // https://mocki.io/v1/c30776ad-8bf6-44dc-9b10-4f7a56b870fe -success
        $curl->post("https://api.karza.in/gst/uat/v2/gst-verification", [
            'gstin' => $gstin,
            'consent' => $api_setting->consent
        ]);
        echo json_encode($curl->response);
        wp_die();
    }
}

function update_karza_api_setting(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'karza_api_setting';
    $column = sanitize_text_field($_POST['column']);
    $value = sanitize_text_field($_POST['value']);

    $api_setting = $wpdb->get_row("select * from $table_name where id=1 limit 1");
    if($api_setting){
        $updated = $wpdb->update($table_name, [$column=> $value], ["id"=> 1]);
        if($updated){
            echo json_encode(["status"=> true, "message"=> "Updated successfully"]);
        }
        else {
            echo json_encode(["status"=> false, "message"=> $wpdb->last_error]);
        }
    }
    else{
        $insert = $wpdb->insert($table_name, ["id"=> 1, $column=> $value]);
        if($insert){
            echo json_encode(["status"=> true, "message"=> "Updated successfully"]);
        }
        else {
            echo json_encode(["status"=> false, "message"=> $wpdb->last_error]);
        }
    }
    wp_die();
}

function verifyOTP(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'karza_api_setting';
    $api_setting = $wpdb->get_row("select * from $table_name where id=1 limit 1");

    $otp = sanitize_text_field($_POST["otp"]);
    $requestId = sanitize_text_field($_POST["requestId"]);

    require_once(KARZA_DIR . '/Curl/autoload.php');
    $curl = new Curl();
    $curl->setHeader('content-type', 'application/json');
    $curl->setHeader('x-karza-key', $api_setting->karza_key);//APT3ftxzs0GZV8Fjy3ku
    $curl->post($api_setting->api_url, [
        'requestId' => $requestId,
        'otp' => $otp
    ]);

    echo json_encode($curl->response);
    wp_die();
}

function sendOTP(){

    global $wpdb;
    $table_name = $wpdb->prefix . 'karza_api_setting';
    $username = sanitize_text_field($_POST["username"]);
    $gstin = sanitize_text_field($_POST["gstin"]);
    $refid = sanitize_text_field($_POST["refid"]);
    
    if($api_setting = validateApiSetting()){

        require_once(KARZA_DIR . '/Curl/autoload.php');
        $curl = new Curl();
        $curl->setHeader('content-type', 'application/json');
        $curl->setHeader('x-karza-key', $api_setting->karza_key);
        
        $curl->post($api_setting->api_url, [
            'username' => $username,
            'gstin' => $gstin,
            'refId'=> $refid,
            'consent'=> $api_setting->consent,
            'consolidate'=> $api_setting->consolidate=="true"?true:false,
            'extendedPeriod'=> $api_setting->extendedPeriod=="true"?true:false
        ]);
        
        echo json_encode($curl->response);
        wp_die();
    }
}

function validateApiSetting(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'karza_api_setting';

    $api_setting = $wpdb->get_row("select * from $table_name where id=1 limit 1");
    if(empty($api_setting)){
        echo json_encode(["statusCode"=> 403, "statusMessage"=> "API Setting is invalid, Contact to admin"]);
        wp_die();
    }
    if($api_setting->karza_key==null || $api_setting->karza_key==""){
        echo json_encode(["statusCode"=> 403, "statusMessage"=> "Invalid API x-karza-key"]);
        wp_die();
    }

    if($api_setting->api_url==null || $api_setting->api_url==""){
        echo json_encode(["statusCode"=> 403, "statusMessage"=> "Invalid API URL Setting"]);
        wp_die();
    }
    return $api_setting;
}

function callback_response_handler($res){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $response = ["status"=> false, "message"=> "Oops! Something went wrong"];
    $res = json_decode(json_encode($res));
    
    if($res->statusCode=="101"){

        $upload_dir = wp_upload_dir();
        $target_dir = $upload_dir['basedir'] . '/karza-gst-document/';
        $pdf_filename = basename($res->result->gstin).".pdf";
        $isPdfDownloaded = false;
        if(!($file_content=@file_get_contents($res->result->pdfDownloadLink)) === false) {
            if(file_exists($target_dir.$pdf_filename)){
                unlink($target_dir.$pdf_filename);
            }
            $isPdfDownloaded=file_put_contents($target_dir.$pdf_filename, $file_content);
        }

        
        
        $gst_no = $res->result->gstin;
        $trade_name = $res->result->profile->tradeNam;
        $address = $res->result->profile->address;
        $pdf_file= $isPdfDownloaded?$pdf_filename:null;
        $status = 1;

        global $wpdb;
        $table_name = $wpdb->prefix . 'karza_response';
        
        $rec = $wpdb->get_row("select * from $table_name where gst_no='$gst_no' order by id asc limit 1");
        if($rec){
            $last_updated_on = current_time('mysql');
            
            $data=compact("gst_no", "trade_name", "address", "pdf_file", "last_updated_on", "status");
            $updated=$wpdb->update($table_name, $data, ['gst_no' => $gst_no]);
            if($updated !== false){
                $response= ["status"=> true, "message"=> "Response synced successfully", "update_id"=> $rec->id];
            }
        }
        else{
            $data=compact("gst_no", "trade_name", "address", "pdf_file", "status");
            $wpdb->insert($table_name, $data);
            if ($wpdb->insert_id) {
                $response = ["status"=> true, "message"=> "Response synced successfully", "insert_id"=> $wpdb->insert_id];
            }
        }
    }
    else {
        $response = ["status"=> false, "message"=> "We could not received api response"];
    }
    if($response["status"]){
        $record = $wpdb->get_row("select * from $table_name where gst_no='$gst_no' and status=1 limit 1");
        $api_setting = $wpdb->get_row("select * from ".$wpdb->prefix."karza_api_setting where id=1 limit 1");
        
        if(KarzaMailer::sendmail($record, $api_setting)){
            $response["mail"]["Status"] = true;
            if($isPdfDownloaded===false){
                $response["mail"]["message"] = "File does not exist";
                $response["mail"]["link"] = $res->result->pdfDownloadLink;
            }
        }
    }

    return $response;
}


require_once(KARZA_DIR . '/karza-login.php');
require_once(KARZA_DIR . '/karza-setting.php');
require_once(KARZA_DIR . '/karza-list.php');
require_once(KARZA_DIR . '/KarzaMailer.php');
?>