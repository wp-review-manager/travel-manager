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
                console.log({ response}, {response_order_item});
                window.location.replace(response.data.redirect);
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

export { submissionCheckout };