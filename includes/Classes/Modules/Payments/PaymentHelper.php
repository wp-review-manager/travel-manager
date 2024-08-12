<?php

namespace WPTravelManager\Classes\Modules\Payments;

if (!defined('ABSPATH')) {
    exit;
}

use WPTravelManager\Classes\ArrayHelper;


class PaymentHelper
{

    /**
     * @var int|mixed
     */
    private static $checkoutCount = 0;

    public static function getCheckoutDynamicClass()
    {
        return 'trm' . '_checkout_' . static::$checkoutCount++;
    }

    public static function getCurrency()
    {
        
        //$settings = someFunctionToGetCurrencySettings();
        //return $settings['currency'];
    }

    public static function getCurrencyConfig($currency = false)
    {
       
        $settings = self::getPaymentSettings();

        if ($currency) {
            $settings['currency'] = $currency;
        }

        $settings = ArrayHelper::only($settings, ['currency', 'currency_sign_position', 'currency_separator', 'decimal_points']);

        $settings['currency_sign'] = self::getCurrencySymbol($settings['currency']);
        return $settings;
    }

    public static function getPaymentSettings()
    {
        static $paymentSettings;
        if ($paymentSettings) {
            return $paymentSettings;
        }

        $paymentSettings = get_option('__trm_payment_module_settings');
        $defaults = [
            'status'                       => 'no',
            'currency'                     => 'USD',
            'currency_sign_position'       => 'left',
            'currency_separator'           => 'dot_comma',
            'decimal_points'               => "2",
            'business_name'                => '',
            'business_logo'                => '',
            'business_address'             => '',
            'debug_log'                    => 'no',
            'all_payments_page_id'         => '',
            'receipt_page_id'              => '',
            'user_can_manage_subscription' => 'yes'
        ];

        $paymentSettings = wp_parse_args($paymentSettings, $defaults);

        return $paymentSettings;
    }

    public static function updatePaymentSettings($data)
    {
        $existingSettings = self::getPaymentSettings();
        $settings = wp_parse_args($data, $existingSettings);
        update_option('__trm_payment_module_settings', $settings, 'yes');

        return self::getPaymentSettings();
    }

    public static function getCustomerEmail($bookingData)
    {
        $user = get_user_by('ID', get_current_user_id());

        if ($user) {
            return $user->user_email;
        }

        // otherwise email will be retrieve from booking data, 
        $email = ArrayHelper::get($bookingData,'traveler_info.email', '');

        return  $email;
    }

    public static function getCustomerName($bookingData)
    {
        $name = ArrayHelper::get($bookingData, 'traveler_info.name', '');
        return $name;
    }

    public static function getCustomerAddress($bookingData)
    {
        $address = ArrayHelper::get($bookingData, 'traveler_info.address', '');
        return $address;
    }

    static function getCustomerPhoneNumber($bookingData) {
        
        $phone = ArrayHelper::get($bookingData, 'traveler_info.phone', '');
        return $phone;
    }

    /**
     * Trim a string and append a suffix.
     *
     * @param string $string String to trim.
     * @param integer $chars Amount of characters.
     *                         Defaults to 200.
     * @param string $suffix Suffix.
     *                         Defaults to '...'.
     * @return string
     */
    public static function formatPaymentItemString($string, $chars = 200, $suffix = '...')
    {
        $string = wp_strip_all_tags($string);
        if (strlen($string) > $chars) {
            if (function_exists('mb_substr')) {
                $string = mb_substr($string, 0, ($chars - mb_strlen($suffix))) . $suffix;
            } else {
                $string = substr($string, 0, ($chars - strlen($suffix))) . $suffix;
            }
        }

        return html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');
    }

    /**
     * Limit length of an arg.
     *
     * @param string $string Argument to limit.
     * @param integer $limit Limit size in characters.
     * @return string
     */
    public static function limitLength($string, $limit = 127)
    {
        $str_limit = $limit - 3;
        if (function_exists('mb_strimwidth')) {
            if (mb_strlen($string) > $limit) {
                $string = mb_strimwidth($string, 0, $str_limit) . '...';
            }
        } else {
            if (strlen($string) > $limit) {
                $string = substr($string, 0, $str_limit) . '...';
            }
        }
        return $string;
    }

    public static function floatToString($float)
    {
        if (!is_float($float)) {
            return $float;
        }

        $locale = localeconv();
        $string = strval($float);
        $string = str_replace($locale['decimal_point'], '.', $string);

        return $string;
    }

    public static function formatMoney($amountInCents, $currency)
    {
        $currencySettings = self::getCurrencyConfig(false, $currency);
        $symbol = \html_entity_decode($currencySettings['currency_sign']);
        $position = $currencySettings['currency_sign_position'];
        $decimalSeparator = '.';
        $thousandSeparator = ',';
        if ($currencySettings['currency_separator'] != 'dot_comma') {
            $decimalSeparator = ',';
            $thousandSeparator = '.';
        }
        $decimalPoints = 2;
        if ((int) round($amountInCents) % 100 == 0 && $currencySettings['decimal_points'] == 0) {
            $decimalPoints = 0;
        }

        $amount = number_format($amountInCents / 100, $decimalPoints, $decimalSeparator, $thousandSeparator);

        if ('left' === $position) {
            return $symbol . $amount;
        } elseif ('left_space' === $position) {
            return $symbol . ' ' . $amount;
        } elseif ('right' === $position) {
            return $amount . $symbol;
        } elseif ('right_space' === $position) {
            return $amount . ' ' . $symbol;
        }
        return $amount;
    }

    // All payment related static methods will be here
    public static function getCurrencies()
    {
        $currencies = [
            'AED' => __('United Arab Emirates Dirham', 'travel-manager'),
            'AFN' => __('Afghan Afghani', 'travel-manager'),
            'ALL' => __('Albanian Lek', 'travel-manager'),
            'AMD' => __('Armenian Dram', 'travel-manager'),
            'ANG' => __('Netherlands Antillean Gulden', 'travel-manager'),
            'AOA' => __('Angolan Kwanza', 'travel-manager'),
            'ARS' => __('Argentine Peso','travel-manager'), // non amex
            'AUD' => __('Australian Dollar', 'travel-manager'),
            'AWG' => __('Aruban Florin', 'travel-manager'),
            'AZN' => __('Azerbaijani Manat', 'travel-manager'),
            'BAM' => __('Bosnia & Herzegovina Convertible Mark', 'travel-manager'),
            'BBD' => __('Barbadian Dollar', 'travel-manager'),
            'BDT' => __('Bangladeshi Taka', 'travel-manager'),
            'BIF' => __('Burundian Franc', 'travel-manager'),
            'BGN' => __('Bulgarian Lev', 'travel-manager'),
            'BMD' => __('Bermudian Dollar', 'travel-manager'),
            'BND' => __('Brunei Dollar', 'travel-manager'),
            'BOB' => __('Bolivian Boliviano', 'travel-manager'),
            'BRL' => __('Brazilian Real', 'travel-manager'),
            'BSD' => __('Bahamian Dollar', 'travel-manager'),
            'BWP' => __('Botswana Pula', 'travel-manager'),
            'BZD' => __('Belize Dollar', 'travel-manager'),
            'CAD' => __('Canadian Dollar', 'travel-manager'),
            'CDF' => __('Congolese Franc', 'travel-manager'),
            'CHF' => __('Swiss Franc', 'travel-manager'),
            'CLP' => __('Chilean Peso', 'travel-manager'),
            'CNY' => __('Chinese Renminbi Yuan', 'travel-manager'),
            'COP' => __('Colombian Peso', 'travel-manager'),
            'CRC' => __('Costa Rican Colón', 'travel-manager'),
            'CVE' => __('Cape Verdean Escudo', 'travel-manager'),
            'CZK' => __('Czech Koruna', 'travel-manager'),
            'DJF' => __('Djiboutian Franc', 'travel-manager'),
            'DKK' => __('Danish Krone', 'travel-manager'),
            'DOP' => __('Dominican Peso', 'travel-manager'),
            'DZD' => __('Algerian Dinar', 'travel-manager'),
            'EGP' => __('Egyptian Pound', 'travel-manager'),
            'ETB' => __('Ethiopian Birr', 'travel-manager'),
            'EUR' => __('Euro', 'travel-manager'),
            'FJD' => __('Fijian Dollar', 'travel-manager'),
            'FKP' => __('Falkland Islands Pound', 'travel-manager'),
            'GBP' => __('British Pound', 'travel-manager'),
            'GEL' => __('Georgian Lari', 'travel-manager'),
            'GHS' => __('Ghanaian Cedi', 'travel-manager'),
            'GIP' => __('Gibraltar Pound', 'travel-manager'),
            'GMD' => __('Gambian Dalasi', 'travel-manager'),
            'GNF' => __('Guinean Franc', 'travel-manager'),
            'GTQ' => __('Guatemalan Quetzal', 'travel-manager'),
            'GYD' => __('Guyanese Dollar', 'travel-manager'),
            'HKD' => __('Hong Kong Dollar', 'travel-manager'),
            'HNL' => __('Honduran Lempira', 'travel-manager'),
            'HRK' => __('Croatian Kuna', 'travel-manager'),
            'HTG' => __('Haitian Gourde', 'travel-manager'),
            'HUF' => __('Hungarian Forint', 'travel-manager'),
            'IDR' => __('Indonesian Rupiah', 'travel-manager'),
            'ILS' => __('Israeli New Sheqel', 'travel-manager'),
            'INR' => __('Indian Rupee', 'travel-manager'),
            'ISK' => __('Icelandic Króna', 'travel-manager'),
            'JMD' => __('Jamaican Dollar', 'travel-manager'),
            'JPY' => __('Japanese Yen', 'travel-manager'),
            'KES' => __('Kenyan Shilling', 'travel-manager'),
            'KGS' => __('Kyrgyzstani Som', 'travel-manager'),
            'KHR' => __('Cambodian Riel', 'travel-manager'),
            'KMF' => __('Comorian Franc', 'travel-manager'),
            'KRW' => __('South Korean Won', 'travel-manager'),
            'KYD' => __('Cayman Islands Dollar', 'travel-manager'),
            'KZT' => __('Kazakhstani Tenge', 'travel-manager'),
            'LAK' => __('Lao Kip', 'travel-manager'),
            'LBP' => __('Lebanese Pound', 'travel-manager'),
            'LKR' => __('Sri Lankan Rupee', 'travel-manager'),
            'LRD' => __('Liberian Dollar', 'travel-manager'),
            'LSL' => __('Lesotho Loti', 'travel-manager'),
            'MAD' => __('Moroccan Dirham', 'travel-manager'),
            'MDL' => __('Moldovan Leu', 'travel-manager'),
            'MGA' => __('Malagasy Ariary', 'travel-manager'),
            'MKD' => __('Macedonian Denar', 'travel-manager'),
            'MNT' => __('Mongolian Tögrög', 'travel-manager'),
            'MOP' => __('Macanese Pataca', 'travel-manager'),
            'MRO' => __('Mauritanian Ouguiya', 'travel-manager'),
            'MUR' => __('Mauritian Rupee', 'travel-manager'),
            'MVR' => __('Maldivian Rufiyaa', 'travel-manager'),
            'MWK' => __('Malawian Kwacha', 'travel-manager'),
            'MXN' => __('Mexican Peso', 'travel-manager'),
            'MYR' => __('Malaysian Ringgit', 'travel-manager'),
            'MZN' => __('Mozambican Metical', 'travel-manager'),
            'NAD' => __('Namibian Dollar', 'travel-manager'),
            'NGN' => __('Nigerian Naira', 'travel-manager'),
            'NIO' => __('Nicaraguan Córdoba', 'travel-manager'),
            'NOK' => __('Norwegian Krone', 'travel-manager'),
            'NPR' => __('Nepalese Rupee', 'travel-manager'),
            'NZD' => __('New Zealand Dollar', 'travel-manager'),
            'PAB' => __('Panamanian Balboa', 'travel-manager'),
            'PEN' => __('Peruvian Nuevo Sol', 'travel-manager'),
            'PGK' => __('Papua New Guinean Kina', 'travel-manager'),
            'PHP' => __('Philippine Peso', 'travel-manager'),
            'PKR' => __('Pakistani Rupee', 'travel-manager'),
            'PLN' => __('Polish Złoty', 'travel-manager'),
            'PYG' => __('Paraguayan Guaraní', 'travel-manager'),
            'QAR' => __('Qatari Riyal', 'travel-manager'),
            'RON' => __('Romanian Leu', 'travel-manager'),
            'RSD' => __('Serbian Dinar', 'travel-manager'),
            'RUB' => __('Russian Ruble', 'travel-manager'),
            'RWF' => __('Rwandan Franc', 'travel-manager'),
            'SAR' => __('Saudi Riyal', 'travel-manager'),
            'SBD' => __('Solomon Islands Dollar', 'travel-manager'),
            'SCR' => __('Seychellois Rupee', 'travel-manager'),
            'SEK' => __('Swedish Krona', 'travel-manager'),
            'SGD' => __('Singapore Dollar', 'travel-manager'),
            'SHP' => __('Saint Helenian Pound', 'travel-manager'),
            'SLL' => __('Sierra Leonean Leone', 'travel-manager'),
            'SOS' => __('Somali Shilling', 'travel-manager'),
            'SRD' => __('Surinamese Dollar', 'travel-manager'),
            'STD' => __('São Tomé and Príncipe Dobra', 'travel-manager'),
            'SVC' => __('Salvadoran Colón', 'travel-manager'),
            'SZL' => __('Swazi Lilangeni', 'travel-manager'),
            'THB' => __('Thai Baht', 'travel-manager'),
            'TJS' => __('Tajikistani Somoni', 'travel-manager'),
            'TOP' => __('Tongan Paʻanga', 'travel-manager'),
            'TRY' => __('Turkish Lira', 'travel-manager'),
            'TTD' => __('Trinidad and Tobago Dollar', 'travel-manager'),
            'TWD' => __('New Taiwan Dollar', 'travel-manager'),
            'TZS' => __('Tanzanian Shilling', 'travel-manager'),
            'UAH' => __('Ukrainian Hryvnia', 'travel-manager'),
            'UGX' => __('Ugandan Shilling', 'travel-manager'),
            'USD' => __('United States Dollar', 'travel-manager'),
            'UYU' => __('Uruguayan Peso', 'travel-manager'),
            'UZS' => __('Uzbekistani Som', 'travel-manager'),
            'VND' => __('Vietnamese Đồng', 'travel-manager'),
            'VUV' => __('Vanuatu Vatu', 'travel-manager'),
            'WST' => __('Samoan Tala', 'travel-manager'),
            'XAF' => __('Central African Cfa Franc', 'travel-manager'),
            'XCD' => __('East Caribbean Dollar', 'travel-manager'),
            'XOF' => __('West African Cfa Franc', 'travel-manager'),
            'XPF' => __('Cfp Franc', 'travel-manager'),
            'YER' => __('Yemeni Rial', 'travel-manager'),
            'ZAR' => __('South African Rand', 'travel-manager'),
            'ZMW' => __('Zambian Kwacha', 'travel-manager')
        ];

        return apply_filters('trm/accepted_currencies', $currencies);
    }

    /**
     * Get a specific currency symbol
     *
     * https://support.stripe.com/questions/which-currencies-does-stripe-support
     */
    public static function getCurrencySymbol($currency = '')
    {
        if (!$currency) {
            // If no currency is passed then default it to USD
            $currency = 'USD';
        }
        $currency = strtoupper($currency);

        $symbols = self::getCurrencySymbols();
        $currency_symbol = isset($symbols[$currency]) ? $symbols[$currency] : '';

        return apply_filters('trm/currency_symbol', $currency_symbol, $currency);
    }

    /**
     * This method will set key value pair for dynamic bindings
     * @return Default values for save Settings
     */
    public static function mapper($defaults, $settings = [], $get = true) 
    {
        foreach ($defaults as $key => $value) {
            if ($get) {
                if (isset($settings[$key])) {
                    $defaults[$key]['value'] = $settings[$key];
                }
            } else {
                if (isset($settings[$key])) {
                    $defaults[$key] = $settings[$key]['value'];
                }
            }
        }

        return $defaults;
    }

    public static function getCurrencySymbols()
    {
        $symbols = [
            'AED' => '&#x62f;.&#x625;',
            'AFN' => '&#x60b;',
            'ALL' => 'L',
            'AMD' => 'AMD',
            'ANG' => '&fnof;',
            'AOA' => 'Kz',
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&fnof;',
            'AZN' => 'AZN',
            'BAM' => 'KM',
            'BBD' => '&#36;',
            'BDT' => '&#2547;&nbsp;',
            'BGN' => '&#1083;&#1074;.',
            'BHD' => '.&#x62f;.&#x628;',
            'BIF' => 'Fr',
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => 'Bs.',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTC' => '&#3647;',
            'BTN' => 'Nu.',
            'BWP' => 'P',
            'BYR' => 'Br',
            'BZD' => '&#36;',
            'CAD' => '&#36;',
            'CDF' => 'Fr',
            'CHF' => '&#67;&#72;&#70;',
            'CLP' => '&#36;',
            'CNY' => '&yen;',
            'COP' => '&#36;',
            'CRC' => '&#x20a1;',
            'CUC' => '&#36;',
            'CUP' => '&#36;',
            'CVE' => '&#36;',
            'CZK' => '&#75;&#269;',
            'DJF' => 'Fr',
            'DKK' => 'DKK',
            'DOP' => 'RD&#36;',
            'DZD' => '&#x62f;.&#x62c;',
            'EGP' => 'EGP',
            'ERN' => 'Nfk',
            'ETB' => 'Br',
            'EUR' => '&euro;',
            'FJD' => '&#36;',
            'FKP' => '&pound;',
            'GBP' => '&pound;',
            'GEL' => '&#x10da;',
            'GGP' => '&pound;',
            'GHS' => '&#x20b5;',
            'GIP' => '&pound;',
            'GMD' => 'D',
            'GNF' => 'Fr',
            'GTQ' => 'Q',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => 'L',
            'HRK' => 'Kn',
            'HTG' => 'G',
            'HUF' => '&#70;&#116;',
            'IDR' => 'Rp',
            'ILS' => '&#8362;',
            'IMP' => '&pound;',
            'INR' => '&#8377;',
            'IQD' => '&#x639;.&#x62f;',
            'IRR' => '&#xfdfc;',
            'ISK' => 'Kr.',
            'JEP' => '&pound;',
            'JMD' => '&#36;',
            'JOD' => '&#x62f;.&#x627;',
            'JPY' => '&yen;',
            'KES' => 'KSh',
            'KGS' => '&#x43b;&#x432;',
            'KHR' => '&#x17db;',
            'KMF' => 'Fr',
            'KPW' => '&#x20a9;',
            'KRW' => '&#8361;',
            'KWD' => '&#x62f;.&#x643;',
            'KYD' => '&#36;',
            'KZT' => 'KZT',
            'LAK' => '&#8365;',
            'LBP' => '&#x644;.&#x644;',
            'LKR' => '&#xdbb;&#xdd4;',
            'LRD' => '&#36;',
            'LSL' => 'L',
            'LYD' => '&#x644;.&#x62f;',
            'MAD' => '&#x62f;. &#x645;.',
            'MDL' => 'L',
            'MGA' => 'Ar',
            'MKD' => '&#x434;&#x435;&#x43d;',
            'MMK' => 'Ks',
            'MNT' => '&#x20ae;',
            'MOP' => 'P',
            'MRO' => 'UM',
            'MUR' => '&#x20a8;',
            'MVR' => '.&#x783;',
            'MWK' => 'MK',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => 'MT',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => 'C&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#x631;.&#x639;.',
            'PAB' => 'B/.',
            'PEN' => 'S/.',
            'PGK' => 'K',
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PRB' => '&#x440;.',
            'PYG' => '&#8370;',
            'QAR' => '&#x631;.&#x642;',
            'RMB' => '&yen;',
            'RON' => 'lei',
            'RSD' => '&#x434;&#x438;&#x43d;.',
            'RUB' => '&#8381;',
            'RWF' => 'Fr',
            'SAR' => '&#x631;.&#x633;',
            'SBD' => '&#36;',
            'SCR' => '&#x20a8;',
            'SDG' => '&#x62c;.&#x633;.',
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&pound;',
            'SLL' => 'Le',
            'SOS' => 'Sh',
            'SRD' => '&#36;',
            'SSP' => '&pound;',
            'STD' => 'Db',
            'SYP' => '&#x644;.&#x633;',
            'SZL' => 'L',
            'THB' => '&#3647;',
            'TJS' => '&#x405;&#x41c;',
            'TMT' => 'm',
            'TND' => '&#x62f;.&#x62a;',
            'TOP' => 'T&#36;',
            'TRY' => '&#8378;',
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => 'Sh',
            'UAH' => '&#8372;',
            'UGX' => 'UGX',
            'USD' => '&#36;',
            'UYU' => '&#36;',
            'UZS' => 'UZS',
            'VEF' => 'Bs F',
            'VND' => '&#8363;',
            'VUV' => 'Vt',
            'WST' => 'T',
            'XAF' => 'Fr',
            'XCD' => '&#36;',
            'XOF' => 'Fr',
            'XPF' => 'Fr',
            'YER' => '&#xfdfc;',
            'ZAR' => '&#82;',
            'ZMW' => 'ZK',
        ];

        return apply_filters('trm/currencies_symbols', $symbols);
    }

    public static function zeroDecimalCurrencies()
    {
        $zeroDecimalCurrencies = [
            'BIF' => esc_html__('Burundian Franc', 'travel-manager'),
            'CLP' => esc_html__('Chilean Peso', 'travel-manager'),
            'DJF' => esc_html__('Djiboutian Franc', 'travel-manager'),
            'GNF' => esc_html__('Guinean Franc', 'travel-manager'),
            'JPY' => esc_html__('Japanese Yen', 'travel-manager'),
            'KMF' => esc_html__('Comorian Franc', 'travel-manager'),
            'KRW' => esc_html__('South Korean Won', 'travel-manager'),
            'MGA' => esc_html__('Malagasy Ariary', 'travel-manager'),
            'PYG' => esc_html__('Paraguayan Guaraní', 'travel-manager'),
            'RWF' => esc_html__('Rwandan Franc', 'travel-manager'),
            'VND' => esc_html__('Vietnamese Dong', 'travel-manager'),
            'VUV' => esc_html__('Vanuatu Vatu', 'travel-manager'),
            'XAF' => esc_html__('Central African Cfa Franc', 'travel-manager'),
            'XOF' => esc_html__('West African Cfa Franc', 'travel-manager'),
            'XPF' => esc_html__('Cfp Franc', 'travel-manager'),
        ];

        return apply_filters('trm/zero_decimal_currencies', $zeroDecimalCurrencies);
    }

    public static function isZeroDecimal($currencyCode)
    {
        $currencyCode = strtoupper($currencyCode);
        $zeroDecimals = self::zeroDecimalCurrencies();
        return isset($zeroDecimals[$currencyCode]);
    }

    public static function getPaymentStatuses()
    {
        $paymentStatuses = [
            'paid'               => __('Paid', 'travel-manager'),
            'processing'         => __('Processing', 'travel-manager'),
            'pending'            => __('Pending', 'travel-manager'),
            'failed'             => __('Failed', 'travel-manager'),
            'refunded'           => __('Refunded', 'travel-manager'),
            'partially-refunded' => __('Partial Refunded', 'travel-manager'),
            'cancelled'          => __('Cancelled', 'travel-manager')
        ];

        return apply_filters('trm/available_payment_statuses', $paymentStatuses);
    }

    public static function encryptKey($value)
    {
        if(!$value) {
            return $value;
        }

        if ( ! extension_loaded( 'openssl' ) ) {
            return $value;
        }

        $salt = (defined( 'LOGGED_IN_SALT' ) && '' !== LOGGED_IN_SALT) ? LOGGED_IN_SALT : 'this-is-a-fallback-salt-but-not-secure';
        $key = ( defined( 'LOGGED_IN_KEY' ) && '' !== LOGGED_IN_KEY ) ? LOGGED_IN_KEY : 'this-is-a-fallback-key-but-not-secure';

        $method = 'aes-256-ctr';
        $ivlen  = openssl_cipher_iv_length( $method );
        $iv     = openssl_random_pseudo_bytes( $ivlen );

        $raw_value = openssl_encrypt( $value . $salt, $method, $key, 0, $iv );
        if ( ! $raw_value ) {
            return false;
        }

        return base64_encode( $iv . $raw_value ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
    }

    public static function decryptKey( $raw_value ) {

        if(!$raw_value) {
            return $raw_value;
        }

        if ( ! extension_loaded( 'openssl' ) ) {
            return $raw_value;
        }

        $raw_value = base64_decode( $raw_value, true ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode

        $method = 'aes-256-ctr';
        $ivlen  = openssl_cipher_iv_length( $method );
        $iv     = substr( $raw_value, 0, $ivlen );

        $raw_value = substr( $raw_value, $ivlen );

        $salt = (defined( 'LOGGED_IN_SALT' ) && '' !== LOGGED_IN_SALT) ? LOGGED_IN_SALT : 'this-is-a-fallback-salt-but-not-secure';
        $key = ( defined( 'LOGGED_IN_KEY' ) && '' !== LOGGED_IN_KEY ) ? LOGGED_IN_KEY : 'this-is-a-fallback-key-but-not-secure';

        $value = openssl_decrypt( $raw_value, $method, $key, 0, $iv );
        if ( ! $value || substr( $value, - strlen( $salt ) ) !== $salt ) {
            return false;
        }

        return substr( $value, 0, - strlen( $salt ) );
    }

}