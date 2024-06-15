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