<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="u-columns col2-set" id="customer_login" style="margin-top: 20px;">


	<div class="u-column1 col-1">

<?php endif; ?>

		<h2 style="text-align: center;"><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

		<form class="woocommerce-form woocommerce-form-login login custom-login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="loginwc-textbox woocommerce-Input woocommerce-Input--text input-text form-control " name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input class="loginwc-textbox woocommerce-Input woocommerce-Input--text input-text form-control " type="password" name="password" id="password" autocomplete="current-password" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row form-row-wide">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
				</label>
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="elementor-button elementor-custom-button-otp woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
			</p>
			<p class="woocommerce-LostPassword lost_password">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	</div>
<style>
.elementor-custom-button-login-des {
   font-style: normal;
    font-weight: 400 !important;
    font-size: 14px !important;
    line-height: 17px !important;
    background-color: #EEEEEE !important;
    color: #D4D4D4 !important;
    border-radius: 6px;
    margin-top: 20px;
    border: none !important;
    padding: 13px 0px 13px 0px !important;
}

.elementor-custom-button-login-enable {
   font-style: normal;
    font-weight: 400 !important;
    font-size: 14px !important;
    line-height: 17px !important;
    background-color: #37414F !important;
    color: white !important;
    border-radius: 6px;
    margin-top: 20px;
    border: none !important;
    padding: 13px 0px 13px 0px !important;
}
/* Sign up css */
.loginwc-header{
	text-align: center;
}
.loginwc-textbox {
	height: 42px !important;
    border: 1px solid #EEEEEE !important;
    border-radius: 4px !important;
}
.loginwc-number{
    height: 42px !important;
    border: 1px solid #EEEEEE !important;
    border-radius: 0px 4px 4px 0px !important;
} 



.registerbtn {
    font-style: normal;
    font-weight: 400 !important;
    font-size: 16px !important;
    line-height: 19px !important;
    background-color: #37414F !important;
    color: #FFFFFF !important;
    border-radius: 6px;
    margin-top: 20px;
	height: 42px;
	width: 100%;
}

.input-group {
  display: flex;
  align-items: center;
  margin-bottom: 1.43rem;
}

.input-group .input-group-addon {
	background-color: #ffffff;
    border: 1px solid #EEEEEE;
    padding: 10px;
    border-right: none;
}

.input-group .form-control {
  border: 1px solid #EEEEEE;
  border-left: none;
  padding: 10px;
}

.input-group .form-control:focus {
  border-color: #EEEEEE;
  outline: none;
}

.input-group-addon {
  min-width: 60px;
    height: 42px;
    border-radius: 4px 0px 0px 4px;
    color:#0D0F13;
   font-size: 14px
line-height: 17px
}
.myinput {
    width: 100%;
    padding: 12px 20px;
    /* margin: 8px 0; */
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
</style>
	<div class="u-column2 col-2">

		<h2 class="loginwc-header"><?php esc_html_e( 'Signup', 'woocommerce' ); ?></h2>

		<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >


			<p class="form-row form-row-wide">
				<input type="text" class="input-text loginwc-textbox" name="first_name" id="first_name" value="<?php if (!empty($_POST['first_name'])) esc_attr_e($_POST['first_name']); ?>" placeholder="Name" required/>
			</p>

			
			<span class="form-row form-row-wide">
				<div class="input-group">
					<span class="input-group-addon">+91</span>
					<input type="text" name="billing_phone" id="billing_phone" class="form-control loginwc-number" placeholder="" pattern="[0-9]{10}" value="<?php if (!empty($_POST['billing_phone'])) esc_attr_e($_POST['billing_phone']); ?>" maxlength="10" required />
				</div>
			</span>

			
			<p class="form-row form-row-wide">
				<input type="email" class="input-text loginwc-textbox" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" placeholder="Email ID" required /><?php // @codingStandardsIgnoreLine ?>
			</p>

			<span class="form-row form-row-wide" style= "display:none;" id="otpdiv">
				<input type="text" class="input-text loginwc-textbox" name="otp" id="otp" autocomplete="Enter OTP" value="<?php echo ( ! empty( $_POST['otp'] ) ) ? esc_attr( wp_unslash( $_POST['otp'] ) ) : ''; ?>" placeholder="Enter OTP" maxlength="6" minlength="6" required />
            	<p id="label_otp" style="text-align:center; color:#0D0F13 !important; font-size:16px !important; margin-top:7px;font-weight: 400 !important;">OTP sent on +91 <span id="otp_sent_on">xxxxxxx99</span></p>
			</span>

			<p class="form-row form-row-wide" style="text-align: center;">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button style="text-transform:none;" type="button" class="elementor-button elementor-custom-button-login-des" id='sbmt' name="register" onclick="OnClickContinue(event);" value="<?php esc_attr_e( 'Continue', 'woocommerce' ); ?>" disabled=""><?php esc_attr_e( 'Continue', 'woocommerce' ); ?></button>
			</p>


		</form>

	</div>

</div>

<script>
jQuery( document ).ready(function() {
	
	
	$('input').on('input mouseenter', function() {
		var first_name = jQuery("#first_name").val();
		var billing_phone = jQuery("#billing_phone").val();
		var reg_email = jQuery("#reg_email").val();
		var otp = jQuery("#otp").val();
		
		console.log("Hello");
		// Enable Continue Btn
		if(first_name !="" && billing_phone != "" && reg_email != ""){
			jQuery("button[name='register']").prop("class", "elementor-button elementor-custom-button-login-enable").prop("disabled", false);
            
		}else{
			jQuery("button[name='register']").prop("class", "elementor-button elementor-custom-button-login-des").prop("disabled", true);
		}

		// Enable Submit Btn
		if(first_name !="" && billing_phone != "" && reg_email != "" && otp !=""){
			jQuery("button[name='register']").prop("class", "elementor-button elementor-custom-button-login-enable").prop("onclick", "").prop("disabled", false);
		}
	});
});
function OnClickContinue(e){
	var billing_phone = jQuery("#billing_phone").val();
	var maskedNumber = "X".repeat(8) + billing_phone.slice(8).padEnd(2, "X");

	jQuery("button[name='register']").prop("type", "submit").val("Submit").html("Submit");
	jQuery("button[name='register']").prop("class", "elementor-button elementor-custom-button-login-des").prop("disabled", true);

	jQuery("#otp_sent_on").html(maskedNumber);
	jQuery("#otpdiv").show();
	e.preventDefault();
}
</script>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>