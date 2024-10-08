class StripeCheckout {
    constructor ($form, $response) {
        this.form = $form;
        this.data = $response.data;
        this.intent = $response.data?.intent;
        this.parentWrapper = this.form.parents('.trm_form_preview_wrapper');
    }

    init () {
        this.startPaymentProcessing();
        const payButton = this.generatePayButton();

        var stripe = Stripe(this.data?.order_items?.payment_args?.public_key);
        const elements = stripe.elements({
            clientSecret: this.intent.client_secret
        });
        const paymentElement = elements.create('payment', {});

        const formSelector = '#' + this.form.attr('id') + ' .trm_payment_processor';
        paymentElement.mount(formSelector);

        let self= this;
        paymentElement.on('ready', (event) => {
            this.afterPaymentProcessorReady(payButton);
            this.form.find('#trm_pay_now').on('click', function(e) {
                e.preventDefault()
                jQuery(this).text('Processing...');
                elements.submit().then(result=> {
                    stripe.confirmPayment({
                        elements,
                        confirmParams: {},
                        redirect: 'if_required'
                    }).then((result) => {
                        jQuery(this).text('Redirecting...');
                        self.afterPaymentSuccess();
                        //update payment data into DB
                        if (result?.paymentIntent?.id) {
                            jQuery.post(window.trm_general.ajax_url, {
                                action: 'trm_payment_confirmation_stripe',
                                intentId: result?.paymentIntent?.id,
                            })
                        }
                    })
                }).catch(error => {
                    console.log(error, 'kk')
                    jQuery(this).text(buttonText);
                })
            })
        });
    }
    generatePayButton() {
        let amounPrefix = this.form.find('.wpm_payment_total_amount_prefix').text();
        let buttonText = "Pay " + amounPrefix + (parseInt(this.intent.amount) / 100) + " Now"
        return "<button id='trm_pay_now' style='margin-top: 20px;width: 100%;' type='submit'>" + buttonText + "</button>";
    }
    startPaymentProcessing() {
        this.form.find('.trm_payment_processor').parent().prepend("<p class='trm_loading_processor'>Payment processor loading...<p/>");
        this.parentWrapper.find('.trm_input_content, .trm_pay_methods, .trm_payment_item', '.wpm_submit_button').hide();
    }

    afterPaymentSuccess() {
        const receipt = "<a href='" + this.data?.order_items?.payment_args?.success_url + "'>View Receipt</a>";
        this.parentWrapper.append("<div class='trm_form_receipt'>Thanks for your contribution 🖤<br/>" + receipt + "</div>");
        this.parentWrapper.find('.trm_form').hide();
        this.parentWrapper.find('.trm_form_to').hide();
    }

    afterPaymentProcessorReady(payButton) {
        this.form.prepend("<p class='complete_payment_instruction'>Please complete your donation with Stripe 👇</p>");
        this.form.find('.trm_payment_processor').append(payButton);
        this.form.find('.trm_loading_processor').remove();
    }
  }
  
  window.addEventListener("trm_payment_next_action_stripe", function (e) {
    new StripeCheckout(e.detail.form, e.detail.response).init();
  });