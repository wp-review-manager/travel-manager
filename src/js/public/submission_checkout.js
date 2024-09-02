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


    $submit.on('click', function(e) {
        e.preventDefault();

        const $input = $('#coupon_input');
        const customer_email = $('#traveler_email').val();


        let couponCode = $input.val();

        if (!couponCode) {
            $input.after('<div class="tm_error">Please enter a coupon code.</div>');
            return;
        }

        $input.siblings('.tm_error').remove();

        $.post(window.trm_public.ajax_url, {
            action: 'tm_checkout',
            route: 'submission_coupon_code',
            coupon_code: couponCode, // Pass the single data here
            customer_email: customer_email
        }).then((response) => {
            console.log(response, 'response');
            if (response.success === true ) {
                $input.siblings('.tm_error').remove();
                $input.siblings('.tm_success').remove();
                $input.after(`<div class="tm_success">${response.data.message}</div>`);
                $input.val(''); // Clear the input field
            } else {
                $input.siblings('.tm_error').remove();
                $input.siblings('.tm_success').remove();
                $input.after(`<div class="tm_error">${response.data.message}</div>`);
            }
        }).catch((error) => {
            console.log(error, 'error');
            $input.siblings('.tm_error').remove();
            $input.siblings('.tm_success').remove();
            $input.after('<div class="tm_error">Something went wrong. Please try again later</div>');
        });
    });
};


export {submissionCheckout,applyCoupon};
