(function ( $ ) {

    var body = $('body');
    var loaderAjax = $('.loader-ajax');


    function showAjaxLoader() {        
        loaderAjax.show();
        body.css('opacity', '0.5');
        body.css('background-color', '#ddd');

    };

    function hideAjaxLoader() {
        loaderAjax.hide();
        body.css('opacity', '1');
        body.css('background-color', '#fff');
    };

    function getAllProducts() {
        $.ajax({
            url : storefront_ajax._ajax_url,
            data:{'action': 'get_all_products'},
            type:'POST',
            beforeSend: function( xhr ) {
                showAjaxLoader();
            },
            success:function(response) {                        
                $('.catalog.container .content-products').html(response);             
            },
            complete:function() {
                hideAjaxLoader();
                getAllRowProducts();
            }
        });
    }

    function getAllRowProducts(){        
        $('.catalog .content-products table tbody tr').each(function() {
            let rowProduct = this;            
            let product = $(rowProduct).data('id_product');            
            let selectProduct = $(rowProduct).children('td').find('select.product-talla');                        
            $(selectProduct).on('change', function() {       
                if ($(this).val() != "") {
                    $(rowProduct).find('.variation-price').show();
                    $(rowProduct).find('.quantity').attr('disabled', false);
                    let selected = $(this).find('option:selected');
                    let publicPrice = formatValueWithoutHTML(selected.data('public_price'), '.');
                    let wholesalePrice = formatValueWithoutHTML(selected.data('wholesale_price'), '.');
                    updatePriceAccordToTalla(rowProduct, publicPrice, wholesalePrice);    
                    updateTotalAccordToQuantity(rowProduct, publicPrice, wholesalePrice);                                       
                } else {
                    $(rowProduct).find('.quantity').attr('disabled', true);
                    $(rowProduct).find('.variation-price').hide();
                    $(rowProduct).find('.message-price').show();                   
                }                   
                
            });
        });
    }


    function formatValueWithoutHTML(amount, sep, decimal=0) {
        return parseFloat(amount).toFixed(decimal).replace(/(\d)(?=(\d{3})+\b)/g, "$1"+sep);
    }

    

    /**
     * @param item => current Product ROW    
     * @param publicPrice
     * @param wholesalePrice 
     */

    function updatePriceAccordToTalla(item, publicPrice, wholesalePrice) {
        let productRow = $(item);   
        console.log("item row => ", $(item).data('id_product'));         
        console.log("public price => ", publicPrice); 
        console.log("mayorista price => ", wholesalePrice);
        $(productRow).find('.message-price').hide();
        let publicPriceHtml = "<span>$"+ publicPrice +"</span>";
        let wholesalePriceHtml = "<span>$"+ wholesalePrice +"</span>";
        $(productRow).find('td.public-price span.variation-price').html(publicPriceHtml);
        $(productRow).find('td.wholesale-price span.variation-price').html(wholesalePriceHtml);
    }

    function updateTotalAccordToQuantity(item, publicPrice, wholesalePrice) {
        let productRow = $(item);
        var total_public = 0;
        var total_whosale = 0;
        var total = 0;
        var whosale_price = wholesalePrice.split('.').join("");
        var public_price = publicPrice.split('.').join("");
        $(productRow).find('.quantity').on("change", function() {
            let quantity = $(this).val();                
            if (quantity <= 1) {                    
                total_public = public_price * quantity;       
                total = total_public;
            } else {                    
                total_whosale = whosale_price *  quantity;
                total  = total_whosale;
            }    
            
            total = formatValueWithoutHTML(total, '.', 0);
            $(productRow).find('.total-price').html('$' + total);
            console.log("total price => ", total);
    
        });
             
    }

    $(document).ready(function() {
        getAllProducts();        
    });


}( jQuery ));


