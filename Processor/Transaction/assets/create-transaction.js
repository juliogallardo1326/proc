/**
 * Created with JetBrains PhpStorm.
 * User: Ari
 * Date: 8/21/14
 * Time: 8:40 PM
 * To change this template use File | Settings | File Templates.
 */
(function(){
    var CLS_FIELDSET_WALLET = 'fieldset-wallet';
    var CLS_FIELDSET_PRODUCT = 'fieldset-product';
    var CLS_FIELDSET_SHIPPING_PRODUCT = 'fieldset-shipping-product';

    var CLS_FIELDSET_PREFIX = 'wallet-';
    var FORM_NAME = 'create-transaction';

    var PARAM_WALLET_ID = 'wallet-id';
    var PARAM_PRODUCT_ID = 'product-id';

    var ready = function() {

        jQuery('form[name=' + FORM_NAME + ']').each(function(i, form) {
            if (typeof form.createInit !== 'undefined')
                return;
            form.createInit = true;

            var Form = jQuery(form);
            console.info(FORM_NAME + " Form Found: ", form);
            Form.on('input', function(e) {
                var productID = Form.find('*[name=' + PARAM_PRODUCT_ID + ']').val();
                var ProductFieldSets = Form.find('.' + CLS_FIELDSET_PRODUCT);
                var ProductFieldSet = ProductFieldSets.filter('[data-' + PARAM_PRODUCT_ID + '=' + productID + ']');
                ProductFieldSets.not(ProductFieldSet).filter(':enabled').attr('disabled', 'disabled');
                ProductFieldSet.filter(':disabled').removeAttr('disabled');

                var walletType = Form.find('*[name=' + PARAM_WALLET_ID + ']').val();
                var WalletFieldSets = Form.find('.' + CLS_FIELDSET_WALLET);
                var WalletFieldSet = WalletFieldSets.filter('[data-' + PARAM_WALLET_ID + '=' + walletType + ']');
                WalletFieldSets = WalletFieldSets.not(WalletFieldSet);
                WalletFieldSet.filter(':disabled').removeAttr('disabled');
                WalletFieldSets.filter(':enabled').attr('disabled', 'disabled');
            }).trigger('input');
        });
    };
    jQuery(document).ready(function() {
        jQuery('body').on('ready', ready);
        ready();
    });
})();

