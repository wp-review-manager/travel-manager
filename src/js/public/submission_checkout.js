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
     console.log(formDataArray, 'data');
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

        $.post(window.tm_public.ajax_url, {
            action: 'tm_checkout',
            route: 'submission_checkout',
            data: formDataObject,
        }).then((response, response_order_item) => {
            console.log(response , response_order_item, 'response');
            if (response.success === true ) {
                $form.find('.tm_error').remove();
                $form.find('.tm_success').remove();
                $form.append('<div class="tm_success">Your checkout has been submitted successfully</div>');
                $form.trigger('reset');
                formDataObject = {};
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
    let errors = {};
    if (formData.traveler_name === '') {
        errors.traveler_name = 'Name is required';
    }
    if (formData.traveler_email === '') {
        errors.traveler_email = 'Email is required';
    }
    if (formData.traveler_phone === '') {
        errors.traveler_phone = 'Phone is required';
    }
    if (formData.traveler_address === '') {
        errors.traveler_address = 'Address is required';
    }
    if (formData.traveler_country === '') {
        errors.traveler_country = 'Country is required';
    }

    if (formData.traveler_email !== '') {
        let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(formData.traveler_email)) {
            errors.traveler_email = 'Invalid email format';
        }
    }

    if (Object.keys(errors).length > 0) {
        return errors;
    }

    return true;
}

export {submissionCheckout
};