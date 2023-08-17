<?php

function wp_buyer_onboarding_product_list() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'product_notification';

    // Handle delete requests
    if ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) {
        $wpdb->delete(
            $table_name,
            array( 'id' => $_GET['id'] )
        );
    }


    $results = GetAllWoocommerceProducts();
    $products = GetAllProducts();
    
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
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Qunatity (In Kg)</th>
                                        <th>Set Price</th>
                                        <th>Notifications</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i=0;
                                        foreach($results as $row){
                                            ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?php echo esc_html( $row->product_name ); ?></td>
                                                <td><?php echo esc_html( $row->category ); ?></td>
                                                <td><?php echo esc_html( $row->brand ); ?></td>
                                                <td><input name="quantity" value="<?=(GetQuantity($row->product_id, $products));?>" class="update_record allow_decimal" data-product_id="<?=$row->product_id;?>" /></td>
                                                <td><input name="price" value="<?=(Get_Price($row->product_id, $products));?>" class="update_record allow_decimal" data-product_id="<?=$row->product_id;?>" /></td>
                                                <td>
                                                    <label>
                                                        <input name="notification" type="checkbox" class="update_notification" data-product_id="<?=$row->product_id;?>" <?=GetNotification($row->product_id, $products)?"checked":null;?> />
                                                        <span class="check"></span>
                                                    </label>
                                                                                                    
                                                </td>
                                                <td><span id="status_<?=$row->product_id;?>"></span></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
    
    <?php
}
function GetQuantity($product_id, $products){

    $key = array_search($product_id, array_column($products, 'product_id'));
    // Check if the product ID was found
    if ($key !== false) {
        // Product was found, so do something with it
        $product = $products[$key];
        return $product['quantity'];
    }
    return null;
}

function Get_Price($product_id, $products){

    $key = array_search($product_id, array_column($products, 'product_id'));
    // Check if the product ID was found
    if ($key !== false) {
        // Product was found, so do something with it
        $product = $products[$key];
        return $product['price'];
    }
    return null;
}

function GetNotification($product_id, $products){

    $key = array_search($product_id, array_column($products, 'product_id'));
    // Check if the product ID was found
    if ($key !== false) {
        // Product was found, so do something with it
        $product = $products[$key];
        return $product['notification'];
    }
    return 0;
}

function GetAllProducts(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'product_notification';
    $data = $wpdb->get_results("select * from $table_name where status=1", ARRAY_A);
    return $data;
}

function GetAllWoocommerceProducts(){
    global $wpdb;

    $data = $wpdb->get_results("SELECT DISTINCT 
            p.ID AS product_id,
            p.post_title AS product_name,
            GROUP_CONCAT(DISTINCT cat.name SEPARATOR ', ') AS category,
            GROUP_CONCAT(DISTINCT brand.name SEPARATOR ', ') AS brand
        FROM wp_posts AS p
        INNER JOIN wp_term_relationships AS tr ON p.ID = tr.object_id
        INNER JOIN wp_term_taxonomy AS cat_tt ON cat_tt.term_taxonomy_id = tr.term_taxonomy_id
        INNER JOIN wp_terms AS cat ON cat.term_id = cat_tt.term_id AND cat_tt.taxonomy = 'product_cat'
        LEFT JOIN wp_term_relationships AS tr2 ON p.ID = tr2.object_id
        LEFT JOIN wp_term_taxonomy AS brand_tt ON brand_tt.term_taxonomy_id = tr2.term_taxonomy_id
        LEFT JOIN wp_terms AS brand ON brand.term_id = brand_tt.term_id AND brand_tt.taxonomy = 'brand'
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        GROUP BY p.ID, product_name
        ORDER BY category ASC, brand ASC, product_name ASC;");
    return $data;
}

function GetWoocommerceProduct($product_id){
    global $wpdb;

    $data = $wpdb->get_row("SELECT DISTINCT 
            p.ID AS product_id,
            p.post_title AS product_name,
            GROUP_CONCAT(DISTINCT cat.name SEPARATOR ', ') AS category,
            GROUP_CONCAT(DISTINCT brand.name SEPARATOR ', ') AS brand
        FROM wp_posts AS p
        INNER JOIN wp_term_relationships AS tr ON p.ID = tr.object_id
        INNER JOIN wp_term_taxonomy AS cat_tt ON cat_tt.term_taxonomy_id = tr.term_taxonomy_id
        INNER JOIN wp_terms AS cat ON cat.term_id = cat_tt.term_id AND cat_tt.taxonomy = 'product_cat'
        LEFT JOIN wp_term_relationships AS tr2 ON p.ID = tr2.object_id
        LEFT JOIN wp_term_taxonomy AS brand_tt ON brand_tt.term_taxonomy_id = tr2.term_taxonomy_id
        LEFT JOIN wp_terms AS brand ON brand.term_id = brand_tt.term_id AND brand_tt.taxonomy = 'brand'
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish' and p.id=$product_id
        GROUP BY p.ID, product_name
        ORDER BY category ASC, brand ASC, product_name ASC;");
    
    return $data;
}
?>