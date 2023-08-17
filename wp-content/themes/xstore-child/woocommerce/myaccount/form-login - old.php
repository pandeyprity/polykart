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
function woocommerce_save_register_fields($customer_id)
{
    $error = false;

    if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['first_name']));
    } else {
        $error = true;
    }
    
    if (isset($_POST['billing_phone']) && !empty($_POST['billing_phone'])) {
        update_user_meta($customer_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
    } else {
        $error = true;
    }

    if ($error) {
        wc_add_notice(__('Please fill in all required fields.'), 'error');
    }
}
add_action('woocommerce_created_customer', 'woocommerce_save_register_fields');

do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="u-columns col2-set" id="customer_login">

	<div class="u-column1 col-1">

<?php endif; ?>

		<h2><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

		<form class="woocommerce-form woocommerce-form-login login custom-login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
				</label>
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
			</p>
			<p class="woocommerce-LostPassword lost_password">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	</div>
<style>

/* Sign up css */
.loginwc-header{
	text-align: center;
}
.loginwc-textbox {
	height: 42px !important;
    border: 1px solid #EEEEEE !important;
    border-radius: 4px !important;
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
				<input type="text" class="input-text loginwc-textbox" name="first_name" id="first_name" value="<?php if (!empty($_POST['first_name'])) esc_attr_e($_POST['first_name']); ?>" placeholder="Name"/>
			</p>

			
			<span class="form-row form-row-wide">
				<div class="input-group">
					<span class="input-group-addon">+91</span>
					<input type="text" class="form-control loginwc-textbox" placeholder="" pattern="[0-9]{10}" value="<?php if (!empty($_POST['billing_phone'])) esc_attr_e($_POST['billing_phone']); ?>" maxlength="10" />
				</div>
			</span>

			
			<p class="form-row form-row-wide">
				<input type="email" class="input-text loginwc-textbox" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" placeholder="Email ID"/><?php // @codingStandardsIgnoreLine ?>
			</p>


			<p class="woocommerce-form-row form-row" style="text-align: center;">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button type="submit" class="myinput" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Continue', 'woocommerce' ); ?></button>
			</p>


		</form>

	</div>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>