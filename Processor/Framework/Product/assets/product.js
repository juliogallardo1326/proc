/**
 * Created with JetBrains PhpStorm.
 * User: Ari
 * Date: 8/21/14
 * Time: 8:40 PM
 * To change this template use File | Settings | File Templates.
 */
(function(){
    var CLS_FIELDSET_WALLET = 'fieldset-wallet';
    
    var ready = function() {

        jQuery('fieldset.' + CLS_FIELDSET_WALLET).each(function(i, fieldset) {
            if (typeof fieldset.walletInit !== 'undefined')
                return;
            fieldset.walletInit = true;
            
            
        });
    };
    jQuery(document).ready(function() {
        jQuery('body').on('ready', ready);
        ready();
    });
})();

