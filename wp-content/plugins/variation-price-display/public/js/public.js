( function ( $ ) {

    // Receiving object
    vpdPublicObject = vpd_public_object;

    // console.log(vpdPublicObject);

    var singleVariation, priceContainer, initPrice, prevPrice, vpdPublicObject, product_wrapper;

    if( vpdPublicObject.wrapperClass !== "" ){
        product_wrapper = '.product.product-type-variable, ' + vpdPublicObject.wrapperClass;
    }
    else{
        product_wrapper = '.product.product-type-variable';
    }

    // initPrice = prevPrice = $(product_wrapper).find('.price').html();

    // singleVariation = $('.single-product .product-type-variable .single_variation_wrap .single_variation');

    // Triggering the `show_variation` event
    $(product_wrapper).on("show_variation", function (event, variation) {
        // console.log(variation);
        var curPice = $(this).find('.price').html()
        if( !variation ){
            console.info('VPD Info: Variation data not found!');
        }
        else{
            // Getting variation price
            variationPrice = variation.price_html;
            initPrice = variation.vpd_init_price;
            // Checking price_html is Empty string, undefined, null or not
            if( variation.price_html ){
                // Passing variation price to the function
                $(document.body).trigger('vpd_show_variation_price', [ variationPrice, $(this), initPrice, 'show_variation' ]);
                hideDefaultPrice();
            }
        }
    });

    // Triggering `hide_variation` event
    $(product_wrapper).on("hide_variation", function(event, variation) { 
        // changePrice( initPrice, $(this), initPrice );
        $(document.body).trigger('vpd_show_variation_price', [ initPrice, $(this), initPrice, 'hide_variation' ]);
    });

    // Init the vpd_show_variation_price trigger
    $(document.body).on("vpd_show_variation_price", function( event, price, variation_wrapper, init_Price ){
        changePrice( price, variation_wrapper, init_Price );
    });

    // Function to run- on changing the variation dropdown
    function changePrice(variationPrice, priceContainer, init_Price, eventName){
        // console.log(variationPrice);
        var priceContainer2;

        if( vpdPublicObject.changeVariationPrice === "no" ) return;

        if (prevPrice === variationPrice) return;

        if( vpdPublicObject.removePriceClass !== "" ){
            priceContainer2 = priceContainer.find('.price')
                        .not('.related .price, .upsell .price')
                        .not(vpdPublicObject.removePriceClass);
        }
        else{
            priceContainer2 = priceContainer.find('.price')
                        .not('.related .price, .upsell .price');
        }
        
        priceContainer2.fadeOut(200, function () {
            priceContainer2.html(variationPrice).fadeIn(200);
            prevPrice = variationPrice;
        });

        $(document).trigger('vpd_after_price_changed', priceContainer);

        // });
    }

    // Default price hiding function
    function hideDefaultPrice(){
        // Default Price hiding condition
        switch( vpdPublicObject.hideDefaultPrice ){
            case 'no':
                $(product_wrapper).find('.single_variation_wrap .woocommerce-variation-price').removeClass('hide_default_price');
                break;
            default:
                $(product_wrapper).find('.single_variation_wrap .woocommerce-variation-price').addClass('hide_default_price'); 
        }
    }

    // Regenerate variation data
    var quickViewClasses= '.yith-quick-view-overlay,#yith-quick-view-close,.botiga-quick-view-popup-close-button';

    $(document).find( quickViewClasses ).on('click', function(event){
        $('a[class=reset_variations]').click();
    });

    // Botiga Theme quick view overlay clicking event
    $(document).find( '.botiga-quick-view-popup' ).on('click', function(event){
        if (null === event.target.closest('.botiga-quick-view-popup-content-ajax')) {
            $('a[class=reset_variations]').click();
        }
    })


} )( jQuery );