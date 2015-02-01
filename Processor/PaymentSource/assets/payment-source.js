/**
 * Created with JetBrains PhpStorm.
 * User: Ari
 * Date: 8/21/14
 * Time: 8:40 PM
 * To change this template use File | Settings | File Templates.
 */
(function(){

    var CLS_FIELDSET_PAYMENT_SOURCE = 'fieldset-payment-source';

    var FORM_NAME = 'create-payment-source';

    var PARAM_PRODUCT_STATUS = 'payment-source-status';
    var PARAM_SOURCE_NAME = 'payment-source-name';
    var PARAM_SOURCE_TYPE = 'payment-source-type';

    var ready = function() {

        jQuery('form[name=' + FORM_NAME + ']').each(function(i, form) {
            if (typeof form.createInit !== 'undefined')
                return;
            form.createInit = true;

            console.info("Payment Source Form Found: ", Form);
            var Form = jQuery(form);
            Form.on('input', function(e) {
                var sourceType = Form.find('*[name=' + PARAM_SOURCE_TYPE + ']').val();
                var ProductFieldSets = Form.find('.' + CLS_FIELDSET_PAYMENT_SOURCE);
                var ProductFieldSet = ProductFieldSets.filter('[data-' + PARAM_SOURCE_TYPE + '=' + sourceType + ']');
                ProductFieldSets.not(ProductFieldSet).filter(':enabled').attr('disabled', 'disabled');
                ProductFieldSet.filter(':disabled').removeAttr('disabled');
            }).trigger('input');
        });
    };
    jQuery(document).ready(function() {
        jQuery('body').on('ready', ready);
        ready();
    });
})();

