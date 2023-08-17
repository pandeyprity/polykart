<?php

function wp_buyer_onboarding_list() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'buyer_onboarding';

    // Handle delete requests
    if ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) {
        $wpdb->delete(
            $table_name,
            array( 'id' => $_GET['id'] )
        );
    }


    $res = GetAllRecords();
    $results = $res["data"];
    $i= $res["start"];
    ?>
    <div class="wrap">
        <div class="wrap nosubsub">
            <h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <hr class="wp-header-end">

            <div id="col-container" class="wp-clearfix">
                    <div class="col-wrap">
                        <div class="form-wrap">
                            <table class="wp-list-table widefat fixed striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th scope="col">Product Groups</th>
                                        <th scope="col">Sub Category</th>
                                        <th scope="col">Brands</th>
                                        <th scope="col">Grades</th>
                                        <th scope="col">Contact Person</th>
                                        <th scope="col">WhatsApp No.</th>
                                        <th scope="col">State</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">TAN No.</th>
                                        <th scope="col">Entry Timestamp</th>
                                        <th scope="col">GST Certificate</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach($results as $row){

                                            $product_groups__id = $row->product_groups;
                                            $sub_category__id = $row->sub_category;
                                            $brands__ids = unserialize($row->brands);
                                            //print_r($brands__ids);
                                            $grades__id = $row->grades;
                                            

                                            if( $term = get_term_by( 'id', $product_groups__id, 'product_cat' ) ){
                                                $product_groups= $term->name;
                                            }
                                            $sub_category=null;
                                            if( $terms = get_term_by( 'id', $sub_category__id, 'product_cat' ) ){
                                                $sub_category= $terms->name;
                                            }
                                            
                                            $brands = [];
                                            if($brands__ids && is_array($brands__ids))
                                            foreach($brands__ids as $key=> $brand__id){
                                                if( $terms = get_term_by('id', $brand__id, 'brand' ) ){
                                                    $brands[]= $terms->name;
                                                }
                                            }
                                            
                                            
                                            if( $terms = get_term_by('id', $grades__id, 'pa_grade' ) ){
                                                $grades= $terms->name;
                                            }


                                            ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?php echo esc_html( $product_groups ); ?></td>
                                                <td><?php echo esc_html( $sub_category ); ?></td>
                                                <td><?php echo implode(", ", $brands); ?></td>
                                                <td><?php echo esc_html( $grades ); ?></td>
                                                <td><?php echo esc_html( $row->contact_person ); ?></td>
                                                <td><?php echo esc_html( $row->whatsapp_no ); ?></td>
                                                <td><?php echo esc_html( $row->state ); ?></td>
                                                <td><?php echo esc_html( $row->email ); ?></td>
                                                <td><?php echo esc_html( $row->tan_no ); ?></td>
                                                <td><?php echo esc_html( $row->timestamp ); ?></td>
                                                <td><a href="<?=home_url();?>/wp-content/uploads/wp-buyer-onboarding/<?=$row->gst_certificate;?>" target="new"><span class="dashicons dashicons-media-document" aria-hidden="true"></span></a></td>
                                                <td>
                                                    <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'id' => $row->id ), admin_url( 'admin.php?page=wp-buyer-onboarding-create' ) ) ); ?>">Edit</a>
                                                    <a class="delete-tag" href="<?php echo esc_url( add_query_arg( array( 'action' => 'delete', 'id' => $row->id ), admin_url( 'admin.php?page=wp-buyer-onboarding-create' ) ) ); ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php echo GetPagination();?>
                    </div>
                
            </div>
        </div>
    </div>
    
    <?php
}


function GetAllRecords(){
    // Retrieve the onboarding data
    global $wpdb;
    $table_name = $wpdb->prefix . 'buyer_onboarding';
    $limit = get_option('posts_per_page');
    $start = 0;
    if(isset($_REQUEST["page_no"]) && $_REQUEST["page_no"]!=NULL && is_numeric($_REQUEST["page_no"]))
    {
        $page = intval($_REQUEST["page_no"]);
        $start = ($page - 1) * $limit;
    }

    $data=$wpdb->get_results( "SELECT * FROM $table_name where status=1 order by id desc limit ".$limit." offset " . $start);

    return ["data"=> $data, "start"=> $start];
}

/**
 * Pagination
 *
 * @param int $totalRecord total number of records.
 * @param int $limit how many record have to display in a page.
 *
 * @return html Returns the pagination html block.
 */
function GetPagination(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'buyer_onboarding';
    $totalRecord=$wpdb->get_results("SELECT count(id) as totalRecord FROM $table_name where status=1");
    $totalRecord = ($totalRecord[0]->totalRecord);
    $limit = get_option('posts_per_page');;

    $pagination = NULL;
    $adjacents = 3;
    $page = 0;
    $counter = 0;

    $targetpage=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $get=$_GET;
    if(isset($get["page_no"]))
    unset($get["page_no"]);
    
    //print_r($get);
    $join='?';
    if(!empty($get))
    foreach($get as $key=>$value)
    $join.=$key.'='.urlencode($value).'&';
    $targetpage.=$join;

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
?>