<?php
function karza_setting()
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'karza_api_setting';

  // Handle delete requests
  karza_delete();

  $rec = $wpdb->get_row("select * from $table_name where id=1 limit 1");
  
?>

  <div class="wrap">
    <div class="wrap nosubsub">
      
      <h1 class="wp-heading-inline">
        Karza API Setting 
        <div id="ajax_status"></div>
      </h1>
      
      <hr class="wp-header-end">
      
      <div id="col-container" class="wp-clearfix">
        <div class="col-wrap">
          <div class="form-wrap">
              <table class="wp-list-table widefat fixed striped">
                <tr>
                  <th>
                      <div class="form-field form-required term-name-wrap">
                          <label for="consolidate">
                              <span class="dashicons dashicons-info-outline" title="Whether the response of this request is to be used for consolidation. If input is true, the response will also be saved at Karza's end for the pre-defined duration"></span> 
                              Consolidate 
                          </label> (true/false)
                          <label>
                              <input name="consolidate" type="checkbox" class="update_api_settingc_css update_api_setting" data-value="true" <?=$rec->consolidate=="true"?"checked":null;?> />
                              <span class="check"></span>
                          </label>
                      </div>
                  </th>
                  <th>
                      <div class="form-field form-required term-name-wrap">
                          <label for="extendedPeriod">
                              <span class="dashicons dashicons-info-outline" title="Report for last 24 months"></span> 
                              extendedPeriod
                          </label> (true/false)
                          <label>
                              <input name="extendedPeriod" type="checkbox" class="update_api_settingc_css update_api_setting" data-value="true" <?=$rec->extendedPeriod=="true"?"checked":null;?> />
                              <span class="check"></span>
                        </label>
                      </div>
                  </th>
                  <th>
                      <div class="form-field form-required term-name-wrap">
                          <label for="consent" class="">
                              <span class="dashicons dashicons-info-outline" title="Consent is required to make the API request"></span> 
                              Consent
                          </label>(Y/N)
                          <label>
                              <input name="consent" type="checkbox" class="update_api_settingc_css update_api_setting" data-value="Y" <?=$rec->consent=="Y"?"checked":null;?> />
                              <span class="check"></span>
                        </label>
                      </div>
                  </th>
                  <th>
                      <div class="form-field form-required term-name-wrap">
                          <label for="pagination" class="">Pagination</label>
                          <input type="number" name="pagination" value="<?=$rec->pagination;?>" data-value="<?=$rec->pagination;?>" onchange="update_key(this);" style="width: 100%; max-width: 200px;"  onclick="showActualData(this)" readonly />
                      </div>
                  </th>
                </tr>
                <tr>
                  <th>
                      <div class="form-field form-required term-name-wrap">
                          <label for="karza_key" class="">x-karza-key</label>
                          <input type="text" name="karza_key" value="<?=str_repeat('*', 16) . substr($rec->karza_key, 16);?>" data-value="<?=$rec->karza_key;?>" onchange="update_key(this);" style="width: 100%; max-width: 200px;" onclick="showActualData(this)" readonly/>
                      </div>
                  </th>
                  <th>
                      <div class="form-field form-required term-name-wrap">
                          <label for="api_url" class="">API URL</label>
                          <input type="text" name="api_url" value="<?=$rec->api_url;?>" data-value="<?=$rec->api_url;?>" onchange="update_key(this);"  onclick="showActualData(this)" readonly/>
                      </div>
                  </th>
                  <th>
                      <div class="form-field form-required term-name-wrap">
                          <label for="gst_validation_endpoint_url" class="">GST Validation Endpoint URL</label>
                          <input type="text" name="gst_validation_endpoint_url" value="<?=$rec->gst_validation_endpoint_url;?>" data-value="<?=$rec->gst_validation_endpoint_url;?>" onchange="update_key(this);"  onclick="showActualData(this)" readonly/>
                      </div>
                  </th>
                  <th>
                      <div class="form-field form-required term-name-wrap">
                          <label for="email_to" class="">Email to</label>
                          <input type="text" name="email_to" value="<?=$rec->email_to;?>" data-value="<?=$rec->email_to;?>" onchange="update_key(this);"  onclick="showActualData(this)" readonly/>
                      </div>
                  </th>
                </tr>
              </table>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php

}


//Delete karza record
function karza_delete()
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'karza_response';
  if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET["id"];
    $result = $wpdb->update($table_name, ["status" => 0], ["id" => $id]);
    wp_redirect(admin_url() . "/admin.php?page=karza-list");
    exit(0);
  }
}
