<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php echo (get_query_var('et_is_customize_preview', false)) ? 'class="no-scrollbar"' : ''; ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0<?php if ( !get_theme_mod('mobile_scalable', false) ) : ?>, maximum-scale=1.0, user-scalable=0<?php endif; ?>"/>
	<?php wp_head(); ?>
        
        
	<!-- Paste this right before your closing </head> tag -->
<script type="text/javascript">
(function(f,b){if(!b.__SV){var e,g,i,h;window.mixpanel=b;b._i=[];b.init=function(e,f,c){function g(a,d){var b=d.split(".");2==b.length&&(a=a[b[0]],d=b[1]);a[d]=function(){a.push([d].concat(Array.prototype.slice.call(arguments,0)))}}var a=b;"undefined"!==typeof c?a=b[c]=[]:c="mixpanel";a.people=a.people||[];a.toString=function(a){var d="mixpanel";"mixpanel"!==c&&(d+="."+c);a||(d+=" (stub)");return d};a.people.toString=function(){return a.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms track_with_groups add_group set_group remove_group register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking start_batch_senders people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user people.remove".split(" ");
for(h=0;h<i.length;h++)g(a,i[h]);var j="set set_once union unset remove delete".split(" ");a.get_group=function(){function b(c){d[c]=function(){call2_args=arguments;call2=[c].concat(Array.prototype.slice.call(call2_args,0));a.push([e,call2])}}for(var d={},e=["get_group"].concat(Array.prototype.slice.call(arguments,0)),c=0;c<j.length;c++)b(j[c]);return d};b._i.push([e,f,c])};b.__SV=1.2;e=f.createElement("script");e.type="text/javascript";e.async=!0;e.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?
MIXPANEL_CUSTOM_LIB_URL:"file:"===f.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.js";g=f.getElementsByTagName("script")[0];g.parentNode.insertBefore(e,g)}})(document,window.mixpanel||[]);
</script>
</head>
<?php $mode = etheme_get_option('dark_styles', 0) ? 'dark' : 'light'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited valid use case ?>
<body <?php body_class(); ?> data-mode="<?php echo esc_attr( $mode ); ?>">
<?php if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
	} else {
		do_action( 'wp_body_open' );
} ?>

<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) : ?>

<?php do_action( 'et_after_body', true ); ?>

<div class="template-container">

	<?php
		/**
		 * Hook: etheme_header_before_template_content.
		 *
		 * @hooked etheme_top_panel_content - 10
		 * @hooked etheme_mobile_menu_content - 20
		 *
		 * @version 6.0.0 +
		 * @since 6.0.0 +
		 *
		 */
		do_action( 'etheme_header_before_template_content' );
	 ?>
	<div class="template-content">
		<div class="page-wrapper">
			<?php 
			/**
			 * Hook: etheme_header.
			 *
			 * @hooked etheme_header_content - 10
			 *
			 * @version 6.0.0 +
			 * @since 6.0.0 +
			 *
			 */
            do_action( 'etheme_header_start' );
			if ( get_query_var('et_mobile-optimization', false) ) {
			    if ( get_query_var('is_mobile', false) ) {
				    do_action( 'etheme_header_mobile' );
                }
			    else {
				    do_action( 'etheme_header' );
                }
            }
			else {
				do_action( 'etheme_header' );
				do_action( 'etheme_header_mobile' );
			}
            do_action( 'etheme_header_end' );
			
endif;