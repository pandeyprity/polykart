<?php defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );
/**
 * The template for displaying theme footer.
 * Close divs started at the header.
 *
 * @since   1.0.0
 * @version 1.0.1
 */

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
/**
 * Hook: etheme_prefooter.
 *
 * @hooked etheme_prefooter_content - 10
 *
 * @version 1.0.0
 * @since 6.2.12
 *
 */
do_action( 'etheme_prefooter' );

?>

</div> <!-- page wrapper -->

<div class="et-footers-wrapper">
	<?php 
		/**
		 * Hook: etheme_footer.
		 *
		 * @hooked etheme_footer_content - 10
		 * @hooked etheme_copyrights_content - 20
		 *
		 * @version 1.0.0
		 * @since 6.2.12
		 *
		 */
		do_action( 'etheme_footer' );
	 ?>
</div>

</div> <!-- template-content -->

<?php do_action('after_page_wrapper'); ?>
</div> <!-- template-container -->

<?php endif; ?>


<?php
/* Always have wp_footer() just before the closing </body>
 * tag of your theme, or you will break many plugins, which
 * generally use this hook to reference JavaScript files.
 */

wp_footer();
?>

</body>
<style>
.et_b_header-compare.et_element-top-level > a {
    display:none !important;
}
.page-id-1406 .et_element et_b_header-cart  flex align-items-center cart-type1  et-content-right et-off-canvas et-off-canvas-wide et-content_toggle et_element-top-level{
    display: none;
}


/* Notification checkbox css */
.toggle_checkbox {
  -webkit-appearance: none;
  appearance: none;
  visibility: hidden;
  display: none;
}

.check {
  position: relative;
  display: block;
  width: 40px;
  height: 20px;
  background-color: #00000040;
  cursor: pointer;
  border-radius: 20px;
  overflow: hidden;
  transition: all 0.5s;
}

input:checked[type="checkbox"] ~ .check {
  background-color: blue;
  
}

.check:before {
  content: '';
  position: absolute;
  top: 1px;
  left: 2px;
  background-color: #ffffff;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  transition: all 0.5s;
}

input:checked[type="checkbox"] ~ .check:before {
  left : 21px;
}

.check:after {
  content: '';
  position: absolute;
  top: 3px;
  right: 4px;
  background-color: white;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  transform: translateX(50px);
  transition: all 0.5s;
  
}
</style>

<style>
/* Style the modal overlay */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 9999;
 
}

/* Style the modal */
.modal {
  background-color: white;
  border-radius: 5px;
  padding: 40px;
  width: 500px;
  max-width: 100%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

/* Style the modal header */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* Style the close button */
.close-modal-btn {
  background-color: transparent;
  border: none;
  font-size: 24px;
  cursor: pointer;
}

/* Style the modal body */
.modal-body {
  margin-top: 20px;
}
.error{
    color: red;
}
.success {
    color: green;
}
#gst_status{
    font-weight: bolder;
}
</style>
<script>
// Get the modal and close button elements
var modalOverlay = document.querySelector('.modal-overlay');
var modal = document.querySelector('.modal');
var closeBtn = document.querySelector('.close-modal-btn');
var openBtn = document.querySelector('.open-modal-btn');

// Function to open the modal
function openModal() {
  modalOverlay.style.display = 'block';
  modal.style.display = 'block';
}

// Function to close the modal
function closeModal() {
  modalOverlay.style.display = 'none';
  modal.style.display = 'none';
}

// Add event listeners to the open and close buttons
// openBtn.addEventListener('click', openModal);
// closeBtn.addEventListener('click', closeModal);


function ValidatePopupForm(){
    $("#registered_companyError").html(null);
    $("#type_of_businessError").html(null);
    $("#nature_of_businessError").html(null);

    var registered_company = $('input[name="registered_company"]:checked').val();
    if(typeof registered_company === "undefined"){
        $("#registered_companyError").html("Please choose");
        return false;
    }

    if(registered_company=="yes") {
        var type_of_business = $("#type_of_business").val();
        console.log(type_of_business);
        if(type_of_business==""){
            $("#type_of_businessError").html("Please Choose Nature of business");
            return false;
        }

        if(type_of_business=="Other"){
            var nature_of_business = $("#nature_of_business").val();
            if(nature_of_business==""){
                $("#nature_of_businessError").html("Please type your nature of business");
                return false;
            }
            
        }
    }
}

function validateGST(){

    $("#gst_status").html(null);
    $("#gst_noError").html(null);
    $("#registered_companyError").html(null);
    $("#type_of_businessError").html(null);
    $("#nature_of_businessError").html(null);
    var gst_no = $("#gst_no").val();
    var isValid = true;    
    

    var registered_company = $('input[name="registered_company"]:checked').val();

    if(typeof registered_company === "undefined"){
        isValid = true;
        return isValid;
    }
    
    if(registered_company==="yes") {
        var type_of_business = $("#type_of_business").val();
        console.log(type_of_business);
        if(type_of_business==""){
            $("#type_of_businessError").html("Please Choose Nature of business");
            isValid = false;
        }

        if(type_of_business=="Other"){
            var nature_of_business = $("#nature_of_business").val();
            if(nature_of_business==""){
                $("#nature_of_businessError").html("Please type your nature of business");
                isValid = false;
            }
        }

        if(gst_no == ""){
            $("#gst_noError").html("Please Enter GST No");
            isValid = false;
        }

        if(gst_no.length !== 15){
            $("#gst_noError").html("GST No should be 15 character");
            isValid = false;
        }
    }
    
    if(!isValid){
        return isValid;
    }
    
    if(gst_no){
        $.ajax({
            url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
            type: 'POST',
            dataType: "json",
            data: {
                action: 'validateGSTNo', // this is the function in your functions.php that will be triggered
                gst_no: gst_no,
            },
            success: function(data) {
                // console.log( data );
                // debugger;
                if(data.statusCode==101){
                    $("#gst_noError").removeClass("error").addClass("success").html(data.result.tradeNam);
                    if(data.result.sts=="Active") {
                        $("#gst_status").removeClass("error").addClass("success").html(data.result.sts);
                        isValid = true;
                    }
                    else {
                        $("#gst_status").removeClass("succcess").addClass("error").html(data.result.sts);
                        isValid = false;
                    }

                    
                } else {
                    $("#gst_noError").removeClass("success").addClass("error").html("Invalid GSTIN Number or Combination of Inputs");
                    //$('button[name="save_company_information"]').prop('disabled', false).css({'background-color': 'black'});
                    isValid = false;
                }
            },
            async: false // making the request synchronous to wait for the response
        });
    }
    
    
    return isValid;
}


$('button[name="save_account_details"]').click(function(event) {
    event.preventDefault(); // prevent default form submission
    $(this).prop('style', 'background-color: grey').prop('disabled', true);
    var response = validateGSTAndForm();
    
    $(this).prop('style', 'background-color: black').prop('disabled', false);
    if(response) {
        $(this).closest('form').submit(); // submit the form
    }
});

$(document).on('click', "#place_order", function(event){
    event.preventDefault();
    $(this).prop('style', 'background-color: grey').prop('disabled', true);
    var response = validateGST();

    $(this).prop('style', 'background-color: black').prop('disabled', false);
    if(response) {
        $(this).closest('form').submit(); // submit the form
    }
});

function validateGSTAndForm(){

    $("#gst_status").html(null);
    $(".gst_noError").html(null);
    $("#registered_companyError").html(null);
    $("#type_of_businessError").html(null);
    $("#nature_of_businessError").html(null);
    var gst_no = $("#gst_no").val();
    var isValid = true;    
    

    var registered_company = $('input[name="registered_company"]:checked').val();
    if(typeof registered_company === "undefined"){
        $("#registered_companyError").html("Please choose");
        isValid = false;
    }

    if(registered_company==="yes") {
        var type_of_business = $("#type_of_business").val();
        console.log(type_of_business);
        if(type_of_business==""){
            $("#type_of_businessError").html("Please Choose Nature of business");
            isValid = false;
        }

        if(type_of_business=="Other"){
            var nature_of_business = $("#nature_of_business").val();
            if(nature_of_business==""){
                $("#nature_of_businessError").html("Please type your nature of business");
                isValid = false;
            }
            
        }

        if(gst_no == ""){
            $(".gst_noError").html("Please Enter GST No");
            isValid = false;
        }

        if(gst_no.length !== 15){
            $(".gst_noError").html("GST No should be 15 character");
            isValid = false;
        }
    }

    if(registered_company==="no") {
        return true;
    }
    
    if(!isValid){
        return isValid;
    }
    
    if(gst_no){
        isValid = false;
        $('button[name="save_account_details"]').prop('style', 'background-color: grey;').prop('disabled', true);

        $.ajax({
            url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
            type: 'POST',
            dataType: "json",
            data: {
                action: 'validateGSTNo', // this is the function in your functions.php that will be triggered
                gst_no: gst_no,
            },
            success: function(data) {
                // console.log( data );
                // debugger;
                if(data.statusCode==101){
                    $(".gst_noError").removeClass("error").addClass("success").html(data.result.tradeNam);
                    if(data.result.sts=="Active") {
                        isValid = true;
                    } else {
                        $("#gst_status").removeClass("succcess").addClass("error").html(data.result.sts);
                        isValid = false;
                    }

                    
                } else {
                    $(".gst_noError").removeClass("success").addClass("error").html("Invalid GSTIN Number or Combination of Inputs");
                    isValid = false;
                }
            },
            async: false // making the request synchronous to wait for the response
        });
    }
    
    if(isValid){
        $('button[name="save_account_details"]').prop('style', 'background-color: black').prop('disabled', false);
    }
    
    return isValid;
}

</script>
<script>

// Replace YOUR_TOKEN with your Project Token
mixpanel.init('d211f4d6996ed393e05f59966ce1f03d', {debug: true});

// Set this to a unique identifier for the user performing the event.
// eg: their ID in your database or their email address.
mixpanel.identify('<?php if ( is_user_logged_in() ) {$current_user = wp_get_current_user();echo $current_user->user_email;}else{echo $_SERVER['REMOTE_ADDR'];}?>')


if (document.body.classList.contains('home')) {
	//event name: viewedHomepage
	window.addEventListener('beforeunload', function() {
        var startTime = performance.now();
		var elapsedTime = performance.now() - startTime;
		var elapsedTimeSeconds = elapsedTime / 1000;
		mixpanel.track("viewedHomepage", {
			"URL": location.origin,
			"Page Title": $('title').text(),
			"Time Spent": elapsedTimeSeconds + ' seconds'
		});
    });

    //event name: clickedOnLinkHomepage
	$('a').on('click', function(event) {
		mixpanel.track("clickedOnLinkHomepage", {
			"Link Text": $(this).text().trim(),
			"Link URL": $(this).attr('href'),
		});
	});
}
if (document.body.classList.contains('product-template-default')) {
    //event name: viewedProduct
    mixpanel.track("viewedProduct", {
        "Product ID": $(".xstore-wishlist-single").attr("data-id"),
        "Product Name": $(".product_title").text(),
        "Product Category": $(".posted_in").text().split(":")[1],
        "Product Price": $(".product_title").siblings('p.price').find('ins .woocommerce-Price-amount bdi').text(),
        "Product Image URL": $(".woocommerce-main-image").attr("href"),
    });

	// event name: sharedOnSocialMediaProduct
    $('.single-product-socials a').on('click', function(event) {
        mixpanel.track("sharedOnSocialMediaProduct", {
            "Product ID": $(".xstore-wishlist-single").attr("data-id"),
            "Product Name": $(".product_title").text(),
            "Social Network": $(this).attr("data-tooltip")
        });
    });

    // event name: addedToCart
    $('.single_add_to_cart_button').on('click', function(event) {
        mixpanel.track("addedToCart", {
            "Product ID": $(".xstore-wishlist-single").attr("data-id"),
            "Product Name": $(".product_title").text(),
            "Product Quantity": $('select[name="quantity"]').val(),
            "Product Price": $(".product_title").siblings('p.price').find('ins .woocommerce-Price-amount bdi').text()
        });
    });
    

    // event name: addedToWishlist
    $('.single-wishlist a').on('click', function(event) {

        mixpanel.track("addedToWishlist", {
            "Product ID": $(".xstore-wishlist-single").attr("data-id"),
            "Product Name": $(".product_title").text()
        });
    });

    
    // event name: readProductReviews
    $('.reviews_tab a').on('click', function(event) {
        mixpanel.track("readProductReviews", {
            "Product ID": $(".xstore-wishlist-single").attr("data-id")
        });
    });
}

if (document.body.classList.contains('woocommerce-account')) {

  $('#sbmt').on('click', function(event) {
        //event name: completedRegistration
        var date = new Date();
        var options = {
            weekday: 'short',
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            hour12: true
        };
        var currentdate = date.toLocaleString('en-US', options)
        mixpanel.track("completedRegistration", {
            "Email Address": $("#reg_email").val(),
            "Registration Date": currentdate,
            "mobile number": $("#billing_phone").val()
        });
    });

    $('.woocommerce-form-login__submit').on('click', function(event) {
        var date = new Date();
        var options = {
            weekday: 'short',
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            hour12: true
        };
        var currentdate = date.toLocaleString('en-US', options)

        var username = $("#username").val();
        var password = $("#password").val();

        if(username!="" && password!=""){
            mixpanel.track("loggedInOut", {
                "Login or Logout Event": "Login",
                "Timestamp": currentdate,
                "Username": username
            });
        }
    });

    $('.woocommerce-MyAccount-navigation-link--customer-logout').on('click', function(event) {
        var date = new Date();
        var options = {
            weekday: 'short',
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            hour12: true
        };
        var currentdate = date.toLocaleString('en-US', options);
        var username = $(".MyAccount-user-name").next("div").text();
        mixpanel.track("loggedInOut", {
            "Login or Logout Event": "Logout",
            "Timestamp": currentdate,
            "Username": username
        });
    });
    
}


if (document.body.classList.contains('post-type-archive-product')) {

    //event name: addedToCart
    $('.product-details a.add_to_cart_button').on('click', function(event) {
        mixpanel.track("addedToCart", {
            "Product ID": $(this).attr("data-product_id"),
            "Product Name": $(this).attr("data-product_name"),
            "Product Quantity": 1,
            "Product Price": $(this).siblings('span').find('ins').text()
        });
    });

    //event name: addedToCart
    $('.footer-product a.add_to_cart_button').on('click', function(event) {
        mixpanel.track("addedToCart", {
            "Product ID": $(this).attr("data-product_id"),
            "Product Name": $(this).attr("data-product_name"),
            "Product Quantity": 1,
            "Product Price": $(this).parent("footer").parent("div").siblings("div.product-details").find("span.price ins").text()
        });
    });

    //event name: addedToWishlist
    $('.footer-product a.xstore-wishlist-icon').on('click', function(event) {
        mixpanel.track("addedToWishlist", {
            "Product ID": $(this).attr("data-id"),
            "Product Name": $(this).parent("footer").parent("div").siblings("div.product-details").find("h2.product-title").text().trim()
        });
    });
}


if (document.body.classList.contains('woocommerce-cart')) {
    
    $(document).ready(function() {
        // event name: startedShoppingCart
        var cart_contents = [];
        var cartItems = $(".woocommerce-cart-form__cart-item");
        cartItems.each(function() {
            var productTitle = $(this).find(".product-details .product-title").text().trim();
            cart_contents.push(productTitle);
        });
        var cart_contents = [...new Set(cart_contents)];
        var cart_contents = cart_contents.join(', ');
        //console.log(cart_contents);

        var cart_total= $(".cart-subtotal").find(".woocommerce-Price-amount").text().trim();
        mixpanel.track("startedShoppingCart", {
            "Cart ID": null,
            "Cart Contents": cart_contents,
            "Cart Total": cart_total
        });
    });
    
    //event name: removedItemFromCart
    $('.cart-item-details a.remove-item').on('click', function(event) {
        
        mixpanel.track("removedItemFromCart", {
            "Removed Product ID": 'N/A',
            "Removed Product Name": $(this).siblings("a.product-title").text().trim(),
            "Removed Product Quantity": $(this).siblings("span.mobile-price").text().trim().split(" x ")[0],
            "Removed Product Price": $(this).siblings("span.mobile-price").text().trim().split(" x ")[1]
        });
    });
    
    // event name: addedPromotionCodeAtCheckout
    $("button[name = 'apply_coupon']").on('click', function(event) {
        mixpanel.track("addedPromotionCodeAtCheckout", {
            "Promotion ID": null,
            "Promotion Name": null,
            "Promotion Code": $("#coupon_code").val()
        });
    });
    
    // event name: updatedCartContents
    $(document).on('click', ".quantity .minus, .quantity .plus", function(event) {
        var cart_contents = [];
        var cartItems = $(".woocommerce-cart-form__cart-item");
        cartItems.each(function() {
            var productTitle = $(this).find(".product-details .product-title").text().trim();
            cart_contents.push(productTitle);
        });
        var cart_contents = [...new Set(cart_contents)];
        var cart_contents = cart_contents.join(', ');

        mixpanel.track("updatedCartContents", {
            "Cart ID": null,
            "Cart Contents": cart_contents
        });
    });
}


if (document.body.classList.contains('woocommerce-checkout')) {
    // event name: addedPromotionCodeAtCheckout
    $("button[name = 'apply_coupon']").on('click', function(event) {
        alert("addedPromotionCodeAtCheckout");
        mixpanel.track("addedPromotionCodeAtCheckout", {
            "Promotion ID": null,
            "Promotion Name": null,
            "Promotion Code": $("#coupon_code").val()
        });
    });
    
    // event name: selectedPaymentMethod
    $(document).on('click', "button#place_order", function(event) {
        mixpanel.track("selectedPaymentMethod", {
            "Payment Method": $("input[name='payment_method']:checked").val(),
            "Order Location": $("input#billing_city").val(),
            "Shipping Location": $("input#billing_city").val()
        });
    });

}

if (document.body.classList.contains('woocommerce-order-received')) {
    mixpanel.track("placedOrder", {
        "Order ID": $(".woocommerce-thankyou-order-details").find("li.order span").text().trim(),
        "Order Contents": $(".woocommerce-table__product-name").text().trim(),
        "Order Total": $(".woocommerce-thankyou-order-details").find("li.total span bdi").text().trim(),
        "Order Date": $(".woocommerce-thankyou-order-details").find("li.date span").text().trim(),
        "Order Location": $(".woocommerce-column--shipping-address").find("address").text(),
        "Shipping Location": $(".woocommerce-column--shipping-address").find("address").text()
    });
    
    mixpanel.track("submittedPayment", {
        "Payment Method": $('.woocommerce-table.shop_table.order_details tfoot tr:nth-child(3) td').text().trim(),
        "Payment Amount": $('.woocommerce-table.shop_table.order_details tfoot tr:nth-child(4) td').text().trim(),
        "Order Location": $(".woocommerce-column--shipping-address").find("address").text(),
        "Shipping Location": $(".woocommerce-column--shipping-address").find("address").text()
    });
};

$(document).on('click', "p.mini-cart-buttons a.btn-checkout", function(event) {
    // Get all the cart item elements
    var cartItems = $(".cart-widget-products li.woocommerce-mini-cart-item");
    var cart_contents = [];
    cartItems.each(function() {
        var productTitle = $(this).find(".product-title a").text().trim();
        cart_contents.push(productTitle);
    });
    var cart_contents = [...new Set(cart_contents)];
    var cart_contents = cart_contents.join(', ');
    //console.log(cart_contents);
    var cart_total=$(this).siblings("div.cart-popup-footer").find("div.woocommerce-mini-cart__total span.big-coast").text().trim();
    //console.log(cart_total);

    // Get cart total amount
    var cart_total = $(this).closest('.product_list-popup-footer-inner').find('.cart-widget-subtotal .woocommerce-Price-amount bdi').text().trim();

    mixpanel.track("startedShoppingCart", {
        "Cart ID": null,
        "Cart Contents": cart_contents,
        "Cart Total": cart_total
    });
});


$(document).on('click', ".et-trash-wrap img", function(event){
    var product_id = $(this).closest("a.remove_from_cart_button").attr("data-product_id");
    var product = $(this).closest("li.woocommerce-mini-cart-item").find("h4.product-title a").text().trim();
    var qtyPrice = $(this).closest("li.woocommerce-mini-cart-item").find("div.descr-box span.quantity").text().trim();
    var qtyArr = qtyPrice.split(" × "); // Split the string by " x " delimiter
    var quantity = qtyArr[0];
    var price = qtyArr[1];
    
    mixpanel.track("removedItemFromCart", {
        "Removed Product ID": product_id,
        "Removed Product Name": product,
        "Removed Product Quantity": quantity,
        "Removed Product Price": price
    });
});

$(document).on('click', "#onboardingForm button[type='submit']", function(event){

    var product_groups = $("#product_groups :selected").text();
    var sub_category = $("#sub_category :selected").text();
    var brands = $("#brands :selected").text();
    var grades = $("#grades :selected").text();
    var contact_person = $("#contact_person").val();
    var whatsapp_no = $("#whatsapp_no").val();
    var email = $("#email").val();
    var tan_no = $("#tan_no").val();

    if(product_groups &&  sub_category && brands && grades && contact_person && email){
        
        mixpanel.track("buyeronboardingfilled", {
            "Product Group": product_groups,
            "Category": sub_category,
            "Brands": brands,
            "Grades": grades,
            "Name": contact_person,
            "Email": email,
            "TAN No": tan_no
        });
    }
});



</script>

   <script>
    jQuery(document).ready(function($) {
      
  
});
    window.onload = function() {
     var gst_no = jQuery("#gst_no").val();
        
    console.log("All resources have loaded");
  //jQuery('#place_order').prop('disabled', true);

       if(gst_no=="")
       {
        console.log('test');
        console.log(gst_no);
         jQuery("#place_order").css("background-color", "red !important").prop('disabled', true);
       }
      
    };
  </script>

</html>