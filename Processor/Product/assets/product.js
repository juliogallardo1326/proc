/**
 * Created with JetBrains PhpStorm.
 * User: Ari
 * Date: 8/21/14
 * Time: 8:40 PM
 * To change this template use File | Settings | File Templates.
 */
(function(){
    var PARAM_PRODUCT_TYPE = 'product-type';
    var CLS_FIELDSET_PRODUCT = 'fieldset-product';
    var CLS_FIELDSET_CONFIG = 'fieldset-product-config';

    var ready = function() {

        jQuery('form:has(fieldset.' + CLS_FIELDSET_CONFIG + ')').each(function(i, form) {
            if (typeof form.productInit !== 'undefined')
                return;
            form.productInit = true;

            var Form = jQuery(form);
            console.info("Product Form Found: ", Form);
            Form.on('input', function(e) {
                var productType = Form.find('*[name=' + PARAM_PRODUCT_TYPE + ']').val();
                var WalletFieldSets = Form.find('.' + CLS_FIELDSET_CONFIG);
                if(productType) {
                    var WalletFieldSet = WalletFieldSets.filter('[data-' + PARAM_PRODUCT_TYPE + '=' + productType + ']');
                    WalletFieldSets = WalletFieldSets.not(WalletFieldSet);
                    WalletFieldSet.filter(':disabled').removeAttr('disabled');
                }
                WalletFieldSets.filter(':enabled').attr('disabled', 'disabled');
            }).trigger('input');

        });
    };
    jQuery(document).ready(function() {
        jQuery('body').on('ready', ready);
        ready();
    });
})();

