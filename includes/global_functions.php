<?php
// global functions here

function TM_getAvatar($email, $size)
{
    $hash = md5(strtolower(trim($email)));

    /**
     * Gravatar URL by Email
     *
     * @return HTML $gravatar img attributes of the gravatar image
     */
    return apply_filters('wppayform_get_avatar',
        "https://www.gravatar.com/avatar/${hash}?s=${size}&d=mm&r=g",
        $email
    );
}

function TMDBModel($tableName = false)
{
    return new \WPTravelManager\Classes\Models\Model($tableName);
}

function tmValidateNonce($key = 'tm_admin_nonce')
{
    $nonce = \WPTravelManager\Classes\ArrayHelper::get($_REQUEST, $key);
    $shouldVerify = apply_filters('tm_nonce_verify', true);

    if ($shouldVerify && !wp_verify_nonce($nonce, $key)) {
        $errors = apply_filters('azp_nonce_error', [
            'error' => [
                __('Nonce verification failed, please try again.', 'azp_app')
            ]
        ]);

        wp_send_json($errors['error'], 422);
    }
}