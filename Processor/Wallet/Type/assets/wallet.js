/**
 * Created with JetBrains PhpStorm.
 * User: Ari
 * Date: 8/21/14
 * Time: 8:40 PM
 * To change this template use File | Settings | File Templates.
 */
(function(){
    var CLS_FIELDSET_WALLET = 'fieldset-wallet';
    var PARAM_WALLET_TYPE = 'wallet-type';

    var ready = function() {

        jQuery('form:has(fieldset.' + CLS_FIELDSET_WALLET + ')').each(function(i, form) {
            if (typeof form.walletInit !== 'undefined')
                return;
            form.walletInit = true;

            var Form = jQuery(form);
            Form.on('input', function(e) {
                var walletType = Form.find('*[name=' + PARAM_WALLET_TYPE + ']').val();
                if(walletType) {
                    var WalletFieldSets = Form.find('.' + CLS_FIELDSET_WALLET);
                    var WalletFieldSet = WalletFieldSets.filter('[data-' + PARAM_WALLET_TYPE + '=' + walletType + ']');
                    WalletFieldSets.not(WalletFieldSet).filter(':enabled').attr('disabled', 'disabled');
                    WalletFieldSet.filter(':disabled').removeAttr('disabled');
                }
            }).trigger('input');

        });
    };
    jQuery(document).ready(function() {
        jQuery('body').on('ready', ready);
        ready();
    });
})();

