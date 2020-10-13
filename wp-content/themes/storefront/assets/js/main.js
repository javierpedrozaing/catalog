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

                $('.catalog .type-filter').on("change", function() {
                    $('.catalog .select-filter').attr('disabled', true);
                    let type = $(this).val();
                    updateFilterOptions(type);
                });                
            }
        });
    }

    function getAllRowProducts() {        
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

    function updateFilterOptions(type) {
        typeSelect = type;        
        select = $('.select-filter');
        options = [];       
        filterType = "";
        switch (typeSelect) {
            case 'collection':
                filterType = "product_tag";
                break;
            case 'category':
                filterType = "product_cat";
                break;        
            default:
                filterType = "";
                break;
        }

        $.ajax({
            url : storefront_ajax._ajax_url,
            data:{'action': 'get_type_filter_values', 'filter_type' : filterType},
            type:'POST',
            beforeSend: function( xhr ) {
                //showAjaxLoader();
            },
            success:function(response) { 
                if (response == 'no_filter') {
                    $(select).attr('disabled', true);   
                    $(select).html("<option>Selecciona el tipo de filtro</option>");
                } else {
                    $(select).attr('disabled', false);                
                    $(select).html(response);
                }                              
                
            },
            complete:function() {
                setFilterProducts();
            }
        });

    }


    function setFilterProducts() {
        let select = $('.catalog .select-filter');
        let typeFilter = $('.catalog .type-filter').val();        
        let value = "";
        $(select).on("change", function() {
            value = $(this).val();            
            $.ajax({
                url : storefront_ajax._ajax_url,
                data:{'action': 'filter_products', 'type' : typeFilter, 'value' : value},
                type:'POST',
                beforeSend: function( xhr ) {
                    //showAjaxLoader();
                },
                success:function(response) { 
                    $('.catalog.container .content-products').html(response);                    
                },
                complete:function() {
                    getAllRowProducts();
                }
            });
        });


    }
    

    /**
     * @param item => current Product ROW    
     * @param publicPrice
     * @param wholesalePrice 
     */

    function updatePriceAccordToTalla(item, publicPrice, wholesalePrice) {
        let productRow = $(item);           
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
            $(productRow).find('.subtotal-price').data('dataprice', total);
            total = formatValueWithoutHTML(total, '.', 0);
            $(productRow).find('.subtotal-price').html('$' + total);
                        
            setTotalPriceAndQuantity();            
           
        });

    }

    function setTotalPriceAndQuantity() {
        let totalprice = 0
        let quantity = 0;
        $('.catalog .content-products table tbody tr').each(function() {
            let subtotal = $(this).find('.subtotal-price').data('dataprice');
            let subquantity = $(this).find('.quantity').val();
            if (subtotal) totalprice += parseFloat(subtotal);
            if (subquantity) quantity += parseInt(subquantity);            
            let totalfinalprice = formatValueWithoutHTML(totalprice, '.', 0);
            $('.content-products .totals-row .total-quantity').val(quantity);            
            $('.content-products .totals-row .total-price').html('$' + totalfinalprice);
        });
        
    }

             


    $(document).ready(function() {
        getAllProducts();        
    });


}( jQuery ));


