<section class="advnaced" id="vpd-advanced-section">

<h3><?php esc_attr_e('Advanced Settings', 'variation-price-display'); ?></h3>
<p><?php _e('The advanced options to control your price format', 'variation-price-display') ?></p>

<table class="widefat wpx-table">

    <?php 

        // Display Condition
        WPXtension_Setting_Fields::select(
            $options = array(
                'tr_class' => 'alternate',
                'label' => esc_attr__('Display Condition', 'variation-price-display'),
                'ele_class' => ' display_condition',
                'value' => Variation_Price_Display::get_options()->display_condition,
                'name' => 'variation_price_display_option_advanced[display_condition]',
                'option' => apply_filters('vpd_display_conditio_html', array(
                    'option_1' => array(
                        'name' => __( 'Shop/Archive Page ', 'variation-price-display' ),
                        'value' => 'shop',
                        'need_pro' => true,
                    ),
                    'option_2' => array(
                        'name' => __( 'Single Product/Product Description Page ', 'variation-price-display' ),
                        'value' => 'single',
                        'need_pro' => true,
                    ),
                    'option_3' => array(
                        'name' => __( 'Both Shop and Single Product Page ', 'variation-price-display' ),
                        'value' => 'both',
                        'need_pro' => true,
                    ),
                )),
                'note' => '',
                'need_pro' => true,
                'pro_exists' => Variation_Price_Display::check_plugin_state('variation-price-display-pro'),
            )
        ); 

         // Exclude/Include Condition
        WPXtension_Setting_Fields::select(
            $options = array(
                'tr_class' => 'new',
                'label' => esc_attr__('Exclude/Include Condition', 'variation-price-display'),
                'ele_class' => ' exin_condition',
                'value' => Variation_Price_Display::get_options()->exin_condition,
                'name' => 'variation_price_display_option_advanced[exin_condition]',
                'option' => apply_filters('vpd_display_conditio_html', array(
                    'option_1' => array(
                        'name' => __( 'None ', 'variation-price-display' ),
                        'value' => 'none',
                        'need_pro' => true,
                    ),
                    'option_2' => array(
                        'name' => __( 'Exclude Categories ', 'variation-price-display' ),
                        'value' => 'exclude',
                        'need_pro' => true,
                    ),
                    'option_3' => array(
                        'name' => __( 'Include Categories ', 'variation-price-display' ),
                        'value' => 'include',
                        'need_pro' => true,
                    ),
                )),
                'note' => '',
                'need_pro' => true,
                'tag' => esc_attr__('New', 'variation-price-display'),
                'pro_exists' => Variation_Price_Display::check_plugin_state('variation-price-display-pro'),
            )
        ); 

        //============== Select Categories ==============

        // Disable VPD based on categories
        
        $cat_options = array();
        $i = 0;
        
        foreach( Variation_Price_Display::get_categories() as $cat ){ $i++;
            $cat_options += array(
                'option_'.$i => array(
                    'name' => $cat->name,
                    'value' => $cat->term_id,
                    'need_pro' => true,
                )
            );
        }
        // print_r($cat_options);

        WPXtension_Setting_Fields::multiselect(
            $options = array(
                'tr_class' => 'alternate',
                'label' => esc_attr__('Select Categories', 'variation-price-display'),
                'ele_class' => ' categories wpx-multiselect',
                'value' => Variation_Price_Display::get_options()->categories,
                'name' => 'variation_price_display_option_advanced[categories][]',
                'option' => apply_filters('vpd_categories', $cat_options),
                'note' => '',
                'need_pro' => true,
                'pro_exists' => Variation_Price_Display::check_plugin_state('variation-price-display-pro'),
            )
        ); 
        //============== Select Categories ==============

        // SKU with variation name
        WPXtension_Setting_Fields::checkbox(
            $options = array(
                'tr_class' => '',
                'label' => esc_attr__('SKU with variation name', 'variation-price-display'),
                'ele_class' => 'display_variation_sku',
                'value' => !empty( get_option('variation_price_display_option_advanced') ) ? Variation_Price_Display::get_options()->display_variation_sku : 'no',
                'name' => 'variation_price_display_option_advanced[display_variation_sku]',
                'default_value' => 'yes', //true or checked
                'checkbox_label' => __('Enable it to display <b><u>SKU</u></b>, if <b>Price Types:</b> <i>List all variation price</i>.', 'variation-price-display'),
                'note' => '',
                'note_info' => __('<b>For Example:</b> <code>Hoodie – Blue, Yes (woo-hoodie-blue-logo) – <del>$40.00</del> $38.00</code>.', 'variation-price-display'),
                'need_pro' => true,
                'pro_exists' => Variation_Price_Display::check_plugin_state('variation-price-display-pro'),
            )
        ); 

        // Display discount badge
        WPXtension_Setting_Fields::checkbox(
            $options = array(
                'tr_class' => 'alternate',
                'label' => esc_attr__('Display discount badge', 'variation-price-display'),
                'ele_class' => 'display_discount_badge',
                'value' => !empty( get_option('variation_price_display_option_advanced') ) ? Variation_Price_Display::get_options()->display_discount_badge : 'yes',
                'name' => 'variation_price_display_option_advanced[display_discount_badge]',
                'default_value' => 'yes', //true or checked
                'checkbox_label' => __('Enable it to display <b><u>Discount Badge</u></b>'),
                'note' => '',
                'note_info' => __('<b>Note:</b> To get it to work with <b>[Minimum/Maximum Price]</b>, please enable <b>Format Sale Price</b> option from <b>General Tab</b>. This option will also work with <i>List all variation price</i>', 'variation-price-display'),
                'need_pro' => true,
                'pro_exists' => Variation_Price_Display::check_plugin_state('variation-price-display-pro'),
            )
        ); 


        // Disable Price for Admin
        WPXtension_Setting_Fields::checkbox(
            $options = array(
                'tr_class' => '',
                'label' => esc_attr__('Disable Price for Admin', 'variation-price-display'),
                'ele_class' => 'disable_price_format_for_admin',
                'value' => !empty( get_option('variation_price_display_option_advanced') ) ? Variation_Price_Display::get_options()->disable_price_format_for_admin : 'no',
                'name' => 'variation_price_display_option_advanced[disable_price_format_for_admin]',
                'default_value' => 'yes', //true or checked
                'checkbox_label' => __('Disable Price Format for the Admin.'),
                'note' => '',
                'note_info' => __('<b>Note:</b> By enabling this option, Admin can see the default price range while logged in.', 'variation-price-display'),
                'need_pro' => true,
                'pro_exists' => Variation_Price_Display::check_plugin_state('variation-price-display-pro'),
            )
        ); 

        


    ?>

</table>


</section>