<?php
function karza_list()
{
  global $wpdb;

  $table_name = $wpdb->prefix . 'karza_reponse';

  $res = GetAllRecords_karza();
  $results = $res["data"];
  $i = $res["start"];
  ?>
  <div class="wrap">
    <div class="wrap nosubsub">
      <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>
      <hr class="wp-header-end">

      <div id="col-container" class="wp-clearfix">
        <div class="col-wrap">
          <div class="form-wrap">
            <table class="wp-list-table widefat fixed striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>GST No</th>
                  <th>Trade Name</th>
                  <th>Address</th>
                  <th>File</th>
                  <!-- <th>Synced On</th> -->
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($results as $row) {

                ?>
                  <tr>
                    <td><?= ++$i; ?></td>
                    <td><?php echo esc_html($row->gst_no); ?></td>
                    <td><?php echo esc_html($row->trade_name); ?></td>
                    <td><?php echo esc_html($row->address); ?></td>
                    <td><a href="<?= home_url(); ?>/wp-content/uploads/karza-gst-document/<?= $row->pdf_file; ?>" target="new"><span class="dashicons dashicons-media-document" aria-hidden="true"></span></a></td>
                    <!-- <td></td> -->
                    <td>
                      <a class="delete-tag" href="<?php echo esc_url( add_query_arg( array( 'action' => 'delete', 'id' => $row->id ), admin_url( 'admin.php?page=karza-setting' ) ) ); ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                    </td>
                   </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
          <?php echo GetPagination_karza(); ?>
        </div>

      </div>
    </div>
  </div>

  <?php
}


function GetAllRecords_karza()
{
  // Retrieve the onboarding data
  global $wpdb;
  $table_name = $wpdb->prefix . 'karza_response';
  $limit = GetPaginationLimit();
  $start = 0;
  if (isset($_REQUEST["page_no"]) && $_REQUEST["page_no"] != NULL && is_numeric($_REQUEST["page_no"])) {
    $page = intval($_REQUEST["page_no"]);
    $start = ($page - 1) * $limit;
  }

  $data = $wpdb->get_results("SELECT id, gst_no, trade_name, address, pdf_file, last_updated_on, coalesce(last_updated_on, timestamp) as timestamp, status FROM $table_name where status=1 order by id desc limit " . $limit . " offset " . $start);

  return ["data" => $data, "start" => $start];
}


/**
 * Pagination
 *
 * @param int $totalRecord total number of records.
 * @param int $limit how many record have to display in a page.
 *
 * @return html Returns the pagination html block.
 */
function GetPagination_karza()
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'karza_response';
  $totalRecord = $wpdb->get_results("SELECT count(id) as totalRecord FROM $table_name where status=1");
  $totalRecord = ($totalRecord[0]->totalRecord);
  $limit = GetPaginationLimit();

  $pagination = NULL;
  $adjacents = 3;
  $page = 0;
  $counter = 0;

  $targetpage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $get = $_GET;
  if (isset($get["page_no"]))
    unset($get["page_no"]);

  //print_r($get);
  $join = '?';
  if (!empty($get))
    foreach ($get as $key => $value)
      $join .= $key . '=' . urlencode($value) . '&';
  $targetpage .= $join;

  if (isset($_REQUEST["page_no"]) && $_REQUEST["page_no"] != NULL && is_numeric($_REQUEST["page_no"]))
    $page = intval($_REQUEST["page_no"]);
  if ($page == 0)
    $page = 1;
  $prev = $page - 1;
  $next = $page + 1;
  $lastpage = ceil($totalRecord / $limit);
  $lpm1 = $lastpage - 1;
  if ($lastpage > 1) {
    $pagination .= '
      <ul class="pagination pagination-sm no-margin pull-right">';
    if ($page > 1)
      $pagination .= "<li><a href=\"$targetpage" . "page_no=$prev\">Previous</a></li>";
    else
      $pagination .= "<li class='disabled'><a href='#'>Previous </a></li>";
    if ($lastpage < 7 + ($adjacents * 2)) {
      for ($counter = 1; $counter <= $lastpage; $counter++) {
        if ($counter == $page)
          $pagination .= '<li class="active"><a href="#"><span>' . $counter . '</span></a></li>';
        else
          $pagination .= "<li><a href=\"$targetpage" . "page_no=$counter\">$counter</a></li>";
      }
    } else if ($lastpage > 5 + ($adjacents * 2)) {
      if ($page < 1 + ($adjacents * 2)) {
        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
          if ($counter == $page)
            $pagination .= '<li class="active"><a href="#">' . $counter . '  </a></li>';
          else
            $pagination .= "<li><a href=\"$targetpage" . "page_no=$counter\">$counter</a></li>";
        }
        $pagination .= "<li class='disabled'><a href='#'>... </a></li>";
        $pagination .= "<li><a href=\"$targetpage" . "page_no=$lpm1\">$lpm1</a></li>";
        $pagination .= "<li><a href=\"$targetpage" . "page_no=$lastpage\">$lastpage</a></li>";
      } else if ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
        $pagination .= "<li><a href=\"$targetpage" . "page_no=1\">1</a></li>";
        $pagination .= "<li><a href=\"$targetpage" . "page_no=2\">2</a></li>";

        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
          if ($counter == $page)
            $pagination .= '<li class="active"><a href="#">' . $counter . '  </a></li>';
          else
            $pagination .= "<li><a href=\"$targetpage" . "page_no=$counter\">$counter</a></li>";
        }
        $pagination .= "<li><a href=\"$targetpage" . "page_no=$lpm1\">$lpm1</a></li>";
        $pagination .= "<li><a href=\"$targetpage" . "page_no=$lastpage\">$lastpage</a></li>";
      } else {
        $pagination .= "<li><a href=\"$targetpage" . "page_no=1\">1</a></li>";
        $pagination .= "<li><a href=\"$targetpage" . "page_no=2\">2</a></li>";
        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
          if ($counter == $page)
            $pagination .= '<li class="active"><a href="#">' . $counter . '  </a></li>';
          else
            $pagination .= "<li><a href=\"$targetpage" . "page_no=$counter\">$counter</a></li>";
        }
      }
    }
    if ($page < $counter - 1)
      $pagination .= "<li><a href=\"$targetpage" . "page_no=$next\">Next</a></li>";
    else
      $pagination .= "<li class='disabled'><a href='#'>Next</a></li>";
    $pagination .= "</ul>";
  }
  return $pagination;
}

function GetPaginationLimit(){
  global $wpdb;
  $setting_table = $wpdb->prefix .'karza_api_setting';
  $record=$wpdb->get_row("select pagination from $setting_table where id=1");
  
  return $record->pagination ?? get_option('posts_per_page');
}
?>