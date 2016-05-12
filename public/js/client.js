
jQuery(document).ready(function () {
    
    //обратная связь тема, показ скрытие данных о машине
    jQuery(this).on('change', '.js-feedback-theme', function(){
        if (jQuery(this).val() == 'Запчасти') {
            jQuery('.feedback-car-info').slideDown('fast');
        } else {
            jQuery('.feedback-car-info').slideUp('fast');
        }
    });
    
    //ajax загрузка виджета корзины
    jQuery(".js-ajax-load-cart-content").load(loadCartContent());
    //показ виджета корзины
    jQuery(".js-ajax-load-cart-content").hover(function () {
        if (jQuery(this).attr('sync') == 'false') {
            loadCartContent();
        } 
        jQuery(".js-ajax-load-cart-content .cart-content-block").show();
    }, function () {
        jQuery(".js-ajax-load-cart-content .cart-content-block").hide();
    });
    
    //ajax добавление в корзину
    jQuery(this).on('click', ".js-ajax-addtocart", function () {
        var XY = jQuery(this).offset();
        var clicked = jQuery(this);
        var objId = jQuery(this).attr('objectid');
        var type = jQuery(this).attr('objecttype');
        var count = jQuery(this).attr('count');
        jQuery.ajax({
            type: "POST",
            url: "/cart/index/addtocart",
            data: {
                objectId: objId,
                type: type,
                count: count
            },
            dataType: "json",
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //statusMessage('Во время AJAX запроса произошла ошибка: '+textStatus+'!','show_img');
            },
            success: function (data) {
                jQuery(".js-ajax-load-cart-content").attr('sync', 'false');
                jQuery(".js-ajax-load-cart-content .cart-summ").text(data.cartTotalSumm+' руб.');
                jQuery(".js-ajax-load-cart-content .cart-count").text(data.cartTotalCount);
                /*
                jQuery('.js-cart_container').html(data.html);
                jQuery('.add_to_cart_tooltip .count').html(data.cartItemCount);
                var tooltipW = jQuery('.add_to_cart_tooltip').width();
                var tooltipH = jQuery('.add_to_cart_tooltip').height();
                jQuery('.add_to_cart_tooltip').css({'top': (XY.top - tooltipH - 18) + 'px', 'left': (XY.left - tooltipW + 18) + 'px'}).fadeIn('fast');
                setTimeout("jQuery('.add_to_cart_tooltip').fadeOut('slow');", 1200);
                */
            }
        });
    });
    
    //ajax удаление из корзины
    jQuery(this).on('click', ".js-ajax-removefromcart", function () {
        var XY = jQuery(this).offset();
        var clicked = jQuery(this);
        var varId = jQuery(this).attr('variantid');
        jQuery.ajax({
            type: "POST",
            url: "/cart/index/removefromcart",
            data: {
                variantId: varId
            },
            dataType: "json",
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //statusMessage('Во время AJAX запроса произошла ошибка: '+textStatus+'!','show_img');
            },
            success: function (data) {
                var del = clicked.parents('#cart-item-variant-id-'+varId);
                del.remove();
            }
        });
    });
    
    //ajax обновление количества в корзине
    var ajaxUpdateCartItemCount = null;
    jQuery(this).on('change', ".js-ajax-update-cartitem-count", function () {
        if (ajaxUpdateCartItemCount != null) 
            ajaxUpdateCartItemCount.abort();
        var count = jQuery(this).val();
        var varId = jQuery(this).attr('variantid');
        var cartItemId = jQuery(this).attr('cartitemid');
        ajaxUpdateCartItemCount = jQuery.ajax({
            type: "POST",
            url: "/cart/index/updateitemcount",
            data: {
                count: count,
                variantId: varId,
                cartItemId: cartItemId
            },
            dataType: "json",
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //statusMessage('Во время AJAX запроса произошла ошибка: '+textStatus+'!','show_img');
            },
            success: function (data) {
                if (data.success == true){
                    jQuery('.js-cart-total-summ-block').text(data.cartTotalSumm);
                }
            }
        });
    });
    
    //ajax загрузка моделей бренда
    jQuery(this).on('change', ".js-ajax-selectbrand", function () {
        var brandName = jQuery(this).val();
        var selectModel = jQuery('.js-ajax-selectmodel');
        jQuery.ajax({
            type: "POST",
            url: '/tyres/getbrandmodels',
            data: {
                brandName: brandName,
            },
            dataType: "html",
            error: function (XMLHttpRequest, textStatus, errorThrown) {},
            success: function (data) {
                selectModel.html(data);
            }
        });
        return false;
    });
    
});

//функция загрузки/обновления виджета корзины
var ajaxLoadCartContent = null;
function loadCartContent () {
    if (ajaxLoadCartContent != null) 
        ajaxLoadCartContent.abort();
    var clicked = jQuery(".js-ajax-load-cart-content");
    ajaxLoadCartContent = jQuery.ajax({
        type: "POST",
        url: "/cart/index/index",
        dataType: "json",
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            jQuery(".js-ajax-load-cart-content .cart-content-block").html('Во время AJAX запроса произошла ошибка: '+textStatus+'!');
        },
        success: function (data) {
            if (data.success == true){
                jQuery(".js-ajax-load-cart-content").attr('sync', 'true');
                jQuery(".js-ajax-load-cart-content .cart-summ").text(data.cartTotalSumm+' руб.');
                jQuery(".js-ajax-load-cart-content .cart-count").text(data.cartTotalCount);
                jQuery(".js-ajax-load-cart-content .cart-content-block").html(data.html);
            }
        }
    });
}