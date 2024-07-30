const submissionInquiry = ($) => {
    const $form = $('#tm_submission-inquiry-form');
    const $submit = $('.tm_inquiry_button');

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

        $.post(window.tm_public.ajax_url, {
            action: 'tm_inquiry',
            route: 'submission_inquiry',
            data: formDataObject,
        }).then((response) => {
            console.log(response.success , 'response');
            if (response.success === true ) {
                $form.find('.tm_error').remove();
                $form.find('.tm_success').remove();
                $form.append('<div class="tm_success">Your inquiry has been submitted successfully</div>');
                $form.trigger('reset');
                formDataObject = {};
            } else {
                $form.find('.tm_error').remove();
                $form.find('.tm_success').remove();
                $form.append('<div class="tm_error">Something went wrong. Please try again later</div>');
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
    if (formData.name === '') {
        errors.name = 'Name is required';
    }
    if (formData.email === '') {
        errors.email = 'Email is required';
    }
    if (formData.phone === '') {
        errors.phone = 'Phone is required';
    }
    if (formData.message === '') {
        errors.message = 'Message is required';
    }

    if (formData.email !== '') {
        let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(formData.email)) {
            errors.email = 'Invalid email format';
        }
    }

    if (formData.country == '') {
        errors.country = 'Country is required';
    }

    if (Object.keys(errors).length > 0) {
        return errors;
    }

    return true;
}

export {submissionInquiry};