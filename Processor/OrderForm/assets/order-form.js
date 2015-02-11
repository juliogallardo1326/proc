/**
 * Created with JetBrains PhpStorm.
 * User: Ari
 * Date: 8/21/14
 * Time: 8:40 PM
 * To change this template use File | Settings | File Templates.
 */
(function(){
    var FORM_NAME = 'form-order-page';
    var CLS_FIELDSET_WALLET = 'fieldset-wallet';
    var PARAM_WALLET_ID = 'wallet-id';

    var ready = function() {

        jQuery('form[name=' + FORM_NAME + ']').each(function(i, form) {
            if (typeof form.createInit !== 'undefined')
                return;
            form.createInit = true;

            console.info("Order Form Found: ", form);
            var Form = jQuery(form);
            Form.on('input', function(e) {

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

