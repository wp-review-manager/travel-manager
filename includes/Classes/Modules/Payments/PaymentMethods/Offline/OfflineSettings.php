<?php
use WPTravelManager\Classes\Modules\Payments\PaymentMethods\BasePaymentMethod;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class OfflineSettings extends BasePaymentMethod
{
    public function __construct()
    {
        parent::__construct('offline');
    }

    public function init()
    {
        add_action('trm/offline_payment_settings', [$this, 'renderSettings']);
    }

    public function getGlobalFields()
    {
        return [
            'offline' => [
                'title' => __('Offline Payment', 'travel-manager'),
                'fields' => [
                    'offline_instructions' => [
                        'type' => 'textarea',
                        'label' => __('Instructions', 'travel-manager'),
                        'placeholder' => __('Instructions for offline payment', 'travel-manager'),
                        'value' => '',
                        'help' => __('Provide instructions for offline payment', 'travel-manager'),
                    ],
                ],
            ],
        ];
    }

    public function getGlobalSettings()
    {
        $defaults = [
            'offline_instructions' => '',
        ];

        $settings = get_option($this->settingsKey, []);

        return $this->mapper($defaults, $settings);
    }

    public function saveSettings($settings)
    {
        update_option($this->settingsKey, $settings);
    }
}