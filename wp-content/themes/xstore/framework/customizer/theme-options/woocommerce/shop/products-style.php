<?php
/**
 * The template created for displaying products style options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'products-style' => array(
			'name'       => 'products-style',
			'title'      => esc_html__( 'Products design', 'xstore' ),
			'panel'      => 'shop',
			'icon'       => 'dashicons-admin-customizer',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/products-style' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $product_settings, $sep, $sep_style ) {
	
	$product_templates = et_customizer_get_posts(
		array(
			'posts_per_page'   => - 1,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'ASC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'vc_grid_item',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'author'           => '',
			'author_name'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => true
		)
	);
	
	$product_templates['default'] = esc_html__( 'Inherit', 'xstore' );
	
	$attributes = wc_get_attribute_taxonomies();
	
	$attributes_to_show = array(
		'et_none' => esc_html__( 'None', 'xstore' ),
	);
	
	if ( is_array( $attributes ) ) {
		foreach ( $attributes as $attribute ) {
			$attributes_to_show[ $attribute->attribute_name ] = $attribute->attribute_label;
		}
	}

    $wpbakery_builder = defined('WPB_VC_VERSION');
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		'product_view' => array(
			'name'        => 'product_view',
			'type'        => 'select',
			'settings'    => 'product_view',
			'label'       => esc_html__( 'Design type', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the design type for the products on the product archive pages. Give them a try and see the difference for yourself.', 'xstore') . ($wpbakery_builder ?
                ' ' . sprintf(esc_html__( 'Info: custom types allow you to choose your own design created using "%1s". Additionally, you will be able to create separate custom designs for the grid and list view modes.', 'xstore' ), '<a href="https://kb.wpbakery.com/docs/learning-more/grid-builder/" target="blank" rel="nofollow">' . esc_html__( 'WPBakery Grid builder', 'xstore' ) . '</a>') :
                ''),
			'section'     => 'products-style',
			'default'     => 'disable',
			'choices'     => $product_settings['view'],
			'priority'    => 1,
		),

        'custom_product_template' => array(
            'name'            => 'custom_product_template',
            'type'            => 'select',
            'settings'        => 'custom_product_template',
            'label'           => esc_html__( 'Custom design type (grid view)', 'xstore' ),
            'tooltip'     => sprintf( esc_html__( 'Choose the custom design for products on the "Grid View" created using the "%1s". Find the video tutorials for the builder\'s usage %2s.', 'xstore' ), '<a href="https://wpbakery.com/video-academy/category/grid/" target="_blank">' . esc_html__( 'WPBakery Grid builder', 'xstore' ) . '</a>', '<a href="https://wpbakery.com/video-academy/category/grid/" target="_blank">' . esc_html__( 'here', 'xstore' ) . '</a>' ),
            'section'         => 'products-style',
            'default'         => 'default',
            'choices'         => $product_templates,
            'active_callback' => array(
                array(
                    'setting'  => 'product_view',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'priority'        => 2,
        ),

        'custom_product_template_list' => array(
            'name'            => 'custom_product_template_list',
            'type'            => 'select',
            'settings'        => 'custom_product_template_list',
            'label'           => esc_html__( 'Custom design type (list view)', 'xstore' ),
            'tooltip'     => sprintf( esc_html__( 'Choose the custom design for products on the "List View" created using the "%1s". Find the video tutorials for the builder\'s usage %2s.', 'xstore' ), '<a href="https://wpbakery.com/video-academy/category/grid/" target="_blank">' . esc_html__( 'WPBakery Grid builder', 'xstore' ) . '</a>', '<a href="https://wpbakery.com/video-academy/category/grid/" target="_blank">' . esc_html__( 'here', 'xstore' ) . '</a>' ),
            'section'         => 'products-style',
            'default'         => 'default',
            'choices'         => $product_templates,
            'active_callback' => array(
                array(
                    'setting'  => 'product_view',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
                array(
                    'setting'  => 'view_mode',
                    'operator' => '!=',
                    'value'    => 'grid',
                ),
            ),
            'priority'        => 3,
        ),
		
		'product_bordered_layout' => array(
			'name'        => 'product_bordered_layout',
			'type'        => 'toggle',
			'settings'    => 'product_bordered_layout',
			'label'       => esc_html__( 'Bordered layout', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to add aesthetically pleasing borders around the product wrapper and between products. Note: borders will only be added on product archive pages.', 'xstore' ),
			'section'     => 'products-style',
			'default'     => 0,
			'priority'    => 6,
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.main-products-loop .products-loop',
					'function' => 'toggleClass',
					'class'    => 'products-bordered-layout',
					'value'    => true
				),
			),
		),
		
		'product_no_space' => array(
			'name'        => 'product_no_space',
			'type'        => 'toggle',
			'settings'    => 'product_no_space',
			'label'       => esc_html__( 'Products no space', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to remove default spaces between products. Note: spaces will be removed only on product archive pages.', 'xstore' ),
			'section'     => 'products-style',
			'default'     => 0,
			'priority'    => 6,
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.main-products-loop .products-loop',
					'function' => 'toggleClass',
					'class'    => 'products-no-space',
					'value'    => true
				),
			),
		),
		
		'product_view_color' => array(
			'name'            => 'product_view_color',
			'type'            => 'select',
			'settings'        => 'product_view_color',
			'label'           => esc_html__( 'Hover Color Scheme', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the color scheme for the product design when the mouse hovers over the buttons.', 'xstore' ),
			'section'         => 'products-style',
			'default'         => 'white',
			'choices'         => $product_settings['view_color'],
			'active_callback' => array(
				array(
					'setting'  => 'product_view',
					'operator' => 'in',
					'value'    => array( 'default', 'overlay', 'info', 'mask', 'mask2', 'mask3' ),
				),
			),
			'priority'        => 4,
		),
		
		'product_img_hover' => array(
			'name'            => 'product_img_hover',
			'type'            => 'select',
			'settings'        => 'product_img_hover',
			'label'           => esc_html__( 'Image hover effect', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the type of effect for the product image when hovering over it, or disable the effect entirely.', 'xstore' ),
			'section'         => 'products-style',
			'default'         => 'slider',
			'choices'         => $product_settings['img_hover'],
			'active_callback' => array(
				array(
					'setting'  => 'product_view',
					'operator' => '!=',
					'value'    => 'custom',
				),
				array(
					'setting'  => 'product_view',
					'operator' => '!=',
					'value'    => 'overlay',
				),
			),
			'priority'        => 5,
		),
		
		'product_stretch_img' => array(
			'name'        => 'product_stretch_img',
			'type'        => 'toggle',
			'settings'    => 'product_stretch_img',
			'label'       => esc_html__( 'Stretch image', 'xstore' ),
			'tooltip' => esc_html__( 'Make the product image occupy 100% of the width of the column in which it is placed. You can disable this option if your images appear blurry.', 'xstore' ),
			'section'     => 'products-style',
			'default'     => 1,
			'priority'    => 6,
		),
		
		'product_title_limit_type' => array(
			'name'        => 'product_title_limit_type',
			'type'        => 'select',
			'settings'    => 'product_title_limit_type',
			'label'       => esc_html__( 'Title limitation type', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the way in which each product title will be limited.', 'xstore' ),
			'section'     => 'products-style',
			'default'     => 'chars',
			'choices'     => array(
				'chars' => esc_html__( 'Chars', 'xstore' ),
				'lines' => esc_html__( 'Lines', 'xstore' ),
			),
			'priority'    => 7,
		),
		
		'product_title_limit' => array(
			'name'            => 'product_title_limit',
			'type'            => 'slider',
			'settings'        => 'product_title_limit',
			'label'           => esc_html__( 'Chars limitation', 'xstore' ),
			'tooltip'     => esc_html__( 'This controls the character length of each product title. Such limitations will be applied to products on product archive pages, related products, cross-sells, and up-sells.', 'xstore' ),
			'section'         => 'products-style',
			'default'         => 0,
			'choices'         => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_title_limit_type',
					'operator' => '==',
					'value'    => 'chars',
				),
			),
			'priority'        => 8,
		),
		
		'product_title_limit_lines' => array(
			'name'            => 'product_title_limit_lines',
			'type'            => 'slider',
			'settings'        => 'product_title_limit_lines',
			'label'           => esc_html__( 'Lines number', 'xstore' ),
			'tooltip'     => esc_html__( 'This sets the number of lines for each product title. These restrictions will be applied to products on product archive pages, related products, cross-sells, and up-sells.', 'xstore' ),
			'section'         => 'products-style',
			'default'         => 2,
			'choices'         => array(
				'min'  => 1,
				'max'  => 5,
				'step' => 1,
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--product-title-lines',
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => 'body',
					'property'      => '--product-title-line-height',
					'value_pattern' => 'calc(3ex + ($px - $px))'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_title_limit_type',
					'operator' => '==',
					'value'    => 'lines',
				),
			),
			'priority'        => 9,
		),
		
		'star-rating-color' => array(
			'name'        => 'star-rating-color',
			'type'        => 'color',
			'settings'    => 'star-rating-color',
			'label'       => esc_html__( 'Rating stars color', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the color for the product rating stars.', 'xstore' ),
			'section'     => 'products-style',
			'default'     => '#fdd835',
			'transport'   => 'postMessage',
			'choices'     => array(
				'alpha' => true,
			),
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.star-rating, #review_form .stars',
					'property' => '--et_yellow-color'
				)
			),
			'priority'    => 10,
		),
		
		'product_page_switchers' => array(
			'name'            => 'product_page_switchers',
			'type'            => 'multicheck',
			'settings'        => 'product_page_switchers',
			'label'           => esc_html__( 'Product content elements', 'xstore' ),
			'tooltip'     => esc_html__( 'Revamp the contents of the product easily by turning on or off the necessary elements.', 'xstore' ),
			'section'         => 'products-style',
			'default'         => array(
				'product_page_productname',
				'product_page_cats',
				'product_page_price',
				'product_page_addtocart',
				'product_page_productrating',
				'hide_buttons_mobile'
			),
			'choices'         => array(
				'product_page_productname'   => esc_html__( 'Product name', 'xstore' ),
				'product_page_cats'          => esc_html__( 'Product categories', 'xstore' ),
				'product_page_price'         => esc_html__( 'Price', 'xstore' ),
				'product_page_addtocart'     => esc_html__( 'Add to cart button', 'xstore' ),
				'product_page_productrating' => esc_html__( 'Rating', 'xstore' ),
				'product_page_product_sku'   => esc_html__( 'SKU', 'xstore' ),
				'hide_buttons_mobile'        => esc_html__( 'Hover buttons on mobile', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_view',
					'operator' => '!=',
					'value'    => 'custom',
				),
			),
			'priority'        => 11,
		),
		
		'product_page_excerpt' => array(
			'name'     => 'product_page_excerpt',
			'type'     => 'toggle',
			'settings' => 'product_page_excerpt',
			'label'    => esc_html__( 'Show excerpt', 'xstore' ),
            'tooltip' => esc_html__('If you want to display an excerpt of the product in its content, enable this option.', 'xstore'),
			'section'  => 'products-style',
			'default'  => false,
//			'active_callback' => array(
//				array(
//					'setting'  => 'product_view',
//					'operator' => 'in',
//					'value'    => array('default', 'overlay', 'mask3', 'mask', 'mask2')
//				),
//			),
			'priority' => 12,
		),
		
		'product_page_excerpt_length' => array(
			'name'            => 'product_page_excerpt_length',
			'type'            => 'slider',
			'settings'        => 'product_page_excerpt_length',
			'label'           => esc_html__( 'Excerpt length (symbols)', 'xstore' ),
			'tooltip'     => esc_html__( 'This controls the character length of each product excerpt.', 'xstore') . ($wpbakery_builder ?
            ' ' . sprintf(esc_html__('Important: This option does not apply to custom designs for products created using the %1s.', 'xstore' ),
                '<a href="https://wpbakery.com/video-academy/category/grid/" target="_blank">' . esc_html__( 'WPBakery Grid builder', 'xstore' ) . '</a>') : ''),
			'section'         => 'products-style',
			'default'         => 120,
			'choices'         => array(
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_page_excerpt',
					'operator' => '==',
					'value'    => '1'
				),
			),
			'priority'        => 13,
		),
		
		'product_page_smart_addtocart' => array(
			'name'            => 'product_page_smart_addtocart',
			'type'            => 'toggle',
			'settings'        => 'product_page_smart_addtocart',
			'label'           => esc_html__( 'Add to cart with quantity', 'xstore' ),
            'tooltip'         => esc_html__( 'If you want to replace your simple "Add to Cart" button with an advanced type that includes both a quantity and "Add to Cart" button, enable this option.', 'xstore') . '<br/>' .
                esc_html__('Tip: This may help your customers save time when buying a product with the desired quantity directly from the product archive page, without needing to go to the product page.', 'xstore' ),
			'section'         => 'products-style',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'product_page_switchers',
					'operator' => 'in',
					'value'    => 'product_page_addtocart'
				),
			),
			'priority'        => 14,
		),
		
		'product_with_box_shadow_hover' => array(
			'name'     => 'product_with_box_shadow_hover',
			'type'     => 'toggle',
			'settings' => 'product_with_box_shadow_hover',
			'label'    => esc_html__( 'Box shadow on hover', 'xstore' ),
            'tooltip'  => esc_html__( 'Enable this option to add an attractive box-shadow to your products when hovering over them.', 'xstore'),
			'section'  => 'products-style',
			'default'  => 0,
			'priority' => 14,
		),
		
		'separator_of_sku_style' => array(
			'name'     => 'separator_of_sku_style',
			'type'     => 'custom',
			'settings' => 'separator_of_sku_style',
			'section'  => 'products-style',
			'default'  => '<div style="' . $sep_style . '">' . esc_html__( 'SKU settings', 'xstore' ) . '</div>',
			'priority' => 15,
		),
		
		// product_sku_locations
		'product_sku_locations'  => array(
			'name'        => 'product_sku_locations',
			'type'        => 'select',
			'settings'    => 'product_sku_locations',
			'label'       => esc_html__( 'Product SKU locations', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the locations for the product SKU.', 'xstore' ),
			'section'     => 'products-style',
			'placeholder' => esc_html__( 'Show product sku in ...', 'xstore' ),
			'priority'    => 16,
			'multiple'    => 7,
			'default'     => array(
				'cart',
				'popup_added_to_cart',
				'mini-cart',
			),
			'choices'     => array(
				'cart'                => esc_html__( 'Cart page', 'xstore' ),
				'popup_added_to_cart' => esc_html__( 'Popup added to cart', 'xstore' ),
				'mini-cart'           => esc_html__( 'Mini-cart / Cart Off-canvas', 'xstore' ),
                'mini-wishlist'           => esc_html__( 'Mini-wishlist / Wishlist Off-canvas', 'xstore' ),
                'mini-compare'           => esc_html__( 'Mini-compare / Compare Off-canvas', 'xstore' ),
				'order-email'         => esc_html__( 'Order Email', 'xstore' ),
				'ajax-search-results' => esc_html__( 'Ajax search results', 'xstore' ),
			),
		),
		
		'separator_of_light_style' => array(
			'name'     => 'separator_of_light_style',
			'type'     => 'custom',
			'settings' => 'separator_of_light_style',
			'section'  => 'products-style',
			'default'  => '<div style="' . $sep_style . '">' . esc_html__( 'Variable products settings', 'xstore' ) . '</div>',
			'priority' => 17,
		),
		
		'product_variable_price_from' => array(
			'name'     => 'product_variable_price_from',
			'type'     => 'toggle',
			'settings' => 'product_variable_price_from',
			'label'    => esc_html__( 'Minimum price only', 'xstore' ),
            'tooltip' => esc_html__('Only display the minimum price for products with variations. This can encourage customers to explore the product\'s variations and purchase it if the minimum price is appealing.', 'xstore'),
			'section'  => 'products-style',
			'default'  => 0,
			'priority' => 18,
		),
		
		'variable_products_detach' => array(
			'name'        => 'variable_products_detach',
			'type'        => 'toggle',
			'settings'    => 'variable_products_detach',
			'label'       => esc_html__( 'Variations as simple products (beta)', 'xstore' ),
			'tooltip' => esc_html__( 'Use this option to display product variations individually as separate, simple products', 'xstore' ) . '<br/>' .
                esc_html__('Note: this option works only on product archive pages.', 'xstore'),
			'section'     => 'products-style',
			'default'     => 0,
			'priority'    => 19,
		),
		
		'variation_product_parent_hidden' => array(
			'name'            => 'variation_product_parent_hidden',
			'type'            => 'toggle',
			'settings'        => 'variation_product_parent_hidden',
			'label'           => esc_html__( 'Hide variable products', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to hide the parent product of the variations.', 'xstore' ) . '<br/>' .
                esc_html__('Note: this option works only on product archive pages.', 'xstore'),
			'section'         => 'products-style',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'variable_products_detach',
					'operator' => '==',
					'value'    => true
				),
			),
			'priority'        => 20,
		),
		
		'variation_product_primary_attribute' => array(
			'name'            => 'variation_product_primary_attribute',
			'type'            => 'select',
			'settings'        => 'variation_product_primary_attribute',
			'label'           => esc_html__( 'Primary attribute', 'xstore' ),
            'tooltip' => sprintf(esc_html__('Choose the primary attribute by which you want to display the variations separately. Don\'t forget to click the "%1s" button to recalculate the variations so they are displayed correctly.', 'xstore'),
                '<a href="'.admin_url('admin.php?page=wc-status&tab=tools').'" target="_blank">'.esc_html__('Recount terms', 'xstore').'</a>'),
			'section'         => 'products-style',
			'default'         => 'et_none',
			'choices'         => $attributes_to_show,
			'active_callback' => array(
				array(
					'setting'  => 'variable_products_detach',
					'operator' => '==',
					'value'    => true
				),
			),
			'priority'        => 21,
		),
		
		'variation_product_name_attributes' => array(
			'name'            => 'variation_product_name_attributes',
			'type'            => 'toggle',
			'settings'        => 'variation_product_name_attributes',
			'label'           => esc_html__( 'Titles with attributes', 'xstore' ),
            'tooltip' => esc_html__('Show attributes as suffixes in product variation names.', 'xstore'),
			'section'         => 'products-style',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'variable_products_detach',
					'operator' => '==',
					'value'    => true
				),
			),
			'priority'        => 22,
		),
		
		'variation_product_widgets_recount' => array(
			'name'            => 'variation_product_widgets_recount',
			'type'            => 'toggle',
			'settings'        => 'variation_product_widgets_recount',
			'label'           => esc_html__( 'Recount layered widgets counts', 'xstore' ),
			'tooltip'     => esc_html__( 'By default, most widgets do not use the values of product variations, but instead use the values of the parent product. If you enable this option, the values will be modified with the values of product variations. Note: This may add a few more requests and may slightly decrease the loading speed of the site.', 'xstore' ),
			'section'         => 'products-style',
			'default'         => false,
			'active_callback' => array(
				array(
					'setting'  => 'variable_products_detach',
					'operator' => '==',
					'value'    => true
				),
			),
			'priority'        => 23,
		),
	);
	
	return array_merge( $fields, $args );
	
} );
