class PaypalCheckout {
    constructor($form, $response) {
        this.form = $form
        this.data = $response.data
    }

    init() {
        this.form.find('.wpm_submit_button, .trm_pay_method').hide()

        let paypalButtonContainer = jQuery("<div style='padding: 0px;'></div>")
        paypal
            .Buttons({
                fundingSource: paypal.FUNDING.PAYPAL,
                style: {
                    shape: 'pill', layout: 'vertical', label: 'paypal', // tagline: 'false',
                    size: 'responsive', disableMaxWidth: true
                }, createOrder: (data, actions) => {
                    return actions.order.create({
                        purchase_units: [this.data.purchase_units]
                    })
                }, onApprove: (data, actions) => {
                    return actions.order.capture().then((details) => {
                        var transaction = details?.purchase_units[0].payments.captures[0];
                        jQuery.post(window.trm_general.ajax_url, {
                            action: 'trm_payment_confirmation_paypal',
                            hash: this.data.hash,
                            charge_id: transaction.id,
                        })
                            .then(response => {
                                window.location = this.data?.confirmation_url;
                            }).catch(err => {
                                window.location = this.data?.confirmation_url;
                            });
                        });
                }, onError: function (err) {
                    alert('An error occurred: ' + err)
                }
            })
            .render(paypalButtonContainer[0])

        this.form.find('.trm_form_submit_wrapper, .trm_no_signup, .trm_input_content, .trm_payment_input_content').hide();
        this.form.find('.trm_pay_methods')?.parent().append(paypalButtonContainer);
        this.form.prepend("<p class='complete_payment_instruction'>Please complete your donation with PayPal 👇</p>");
    }
}

window.addEventListener('trm_payment_next_action_paypal', function (e) {
    new PaypalCheckout(e.detail.form, e.detail.response).init()
})