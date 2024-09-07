const submissionCheckout = ($) => {
    const $form = $('#tm_checkout-form');
    const $submit = $('.tm_checkout_button');

    $submit.on('click', function(e) {
        e.preventDefault();
        let formDataArray = $form.serializeArray();
        let formDataObject = {};
        
        formDataArray.forEach(item => {
            formDataObject[item.name] = item.value;
        });
  
        const validateFormData = validation(formDataObject);
        if (validateFormData !== true) {
            let errors = validateFormData;
            $form.find('.tm_error').remove();
            for (let key in errors) {
                console.log(key, 'key');
                $form.find(`[name="${key}"]`).after(`<div class="tm_error">${errors[key]}</div>`);
            }
            return;
        }
        
        $.post(window.trm_public.ajax_url, {
            action: 'tm_checkout',
            route: 'submission_checkout',
            data: formDataObject,
            couponCode: $('#trm_apply_coupon td').data('coupon_code')
        }).then((response, response_order_item) => {
            if (response.success === true ) {
                $form.find('.tm_error').remove();
                $form.find('.tm_success').remove();
                $form.append('<div class="tm_success">Your checkout has been submitted successfully</div>');
                $form.trigger('reset');
                formDataObject = {};
                if (response?.data?.redirect_url) {
                    window.location.replace(response.data.redirect_url);
                }
            } else {
                $form.find('.tm_error').remove();
                $form.find('.tm_success').remove();
                $form.append('<div  class="tm_error">Something went wrong. Please try again later</div>');
            }
        }).catch((error) => {
            console.log(error, 'error');
            $form.find('.tm_error').remove();
            $form.find('.tm_success').remove();
            $form.append('<div class="tm_error">Something went wrong. Please try again later</div>');
        });
        
    });
};

const validation = (formData) => {
    const errors = {};

    // Helper function to check if a field is empty and assign an error message
    const validateField = (field, fieldName, message) => {
        if (!formData[field]) {
            errors[field] = message;
        }
    };

    // Check required fields
    validateField('traveler_name', 'traveler_name', 'Name is required');
    validateField('traveler_email', 'traveler_email', 'Email is required');
    validateField('traveler_phone', 'traveler_phone', 'Phone is required');
    validateField('address', 'address', 'Address is required');
    validateField('traveler_country', 'traveler_country', 'Country is required');
    validateField('city', 'city', 'City is required');
    validateField('zip_code', 'zip_code', 'Zip is required');
    validateField('state', 'state', 'State is required'); // Assuming there's a 'state' field to check
    validateField('trm_payment_method', 'trm_payment_method', 'Payment method is required');

    // Email format validation
    if (formData.traveler_email) {
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(formData.traveler_email)) {
            errors.traveler_email = 'Invalid email format';
        }
    }

    return Object.keys(errors).length > 0 ? errors : true;
};


//======================================================
const applyCoupon = ($) => {
    const $submit = $('.tm_voucher_button');
    $submit.on('click', function (e) {
        e.preventDefault();

        const $input = $('#coupon_input');
        const customerEmail = $('#traveler_email').val();
        const subtotal = $('#trm_subtotal').data('subtotal');
        const couponCode = $input.val().trim();

        // Clear any previous error/success messages
        $input.siblings('.tm_error, .tm_success').remove();

        if (!couponCode) {
            $input.after('<div class="tm_error">Please enter a coupon code.</div>');
            return;
        }

        $submit.text('Applying...').attr('disabled', true);
        $('#trm_apply_coupon').remove();

        $.post(window.trm_public.ajax_url, {
            action: 'tm_checkout',
            route: 'submission_coupon_code',
            coupon_code: couponCode,
            customer_email: customerEmail,
            subtotal: subtotal
        })
        .done(response => {
            // Handle success response
            if (response.success) {
                $submit.after(`
                    <div class="tm_success_wrap">
                        <p class="tm_success">Apply coupon "${response.data.coupon_code}" successfully</p>
                        <button class="tm_remove_coupon">Remove</button>
                    </div>
                `);
                $('#subtotal').after(`
                    <tr id="trm_apply_coupon">
                        <td data-coupon_code="${response.data.coupon_code}" colspan="2" style="text-align: right; padding-top:20px !important; font-size: 20px; color: #232323; font-weight: 500;">
                            <span style="padding-right: 10px;">Discount :</span>
                            <span class="tm_currency_code">$</span> <span>${response.data.discount}</span>
                        </td>
                    </tr>
                `);
                $input.val(''); // Clear input after applying coupon
            } else {
                // Handle failure response
                $input.after(`<div class="tm_error">${response.data.message}</div>`);
            }
        })
        .fail(error => {
            console.error('Error:', error);
            $input.after('<div class="tm_error">Something went wrong. Please try again later.</div>');
        })
        .always(() => {
            // Reset button state
            $submit.text('Apply').attr('disabled', false);
        });
    });
};

const deleteCoupon = ($) => {
    $(document).on('click', '.tm_remove_coupon', function () {
        $('#trm_apply_coupon').remove();
        $('.tm_success_wrap').remove();
    });
}





export {submissionCheckout,applyCoupon, deleteCoupon};
