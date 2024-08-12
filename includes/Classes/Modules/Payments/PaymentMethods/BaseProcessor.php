<?php

namespace WPTravelManager\Classes\Modules\Payments\PaymentMethods;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use WPTravelManager\Classes\Models\Booking; 
use WPTravelManager\Classes\Models\Order;
use WPTravelManager\Classes\Models\Transaction;
use WPTravelManager\Classes\ArrayHelper;
use WPTravelManager\Classes\Modules\Payments\PaymentHelper;

abstract class BaseProcessor
{
    protected $method;

    protected $booking = null;

    protected $bookingId = null;

    protected $trip = null;

    public function init()
    {
        add_action('trm/process_payment_' . $this->method, array($this, 'handlePaymentAction'), 10, 5);
    }

    public abstract function handlePaymentAction($bookingId, $bookingData, $tripId, $methodSettings, $totalPayable);

    public function setBookingId($bookingId)
    {
        $this->bookingId = $bookingId;
    }

    public function getBookingId()
    {
        return $this->bookingId;
    }

    public function insertTransaction($data)
    {
        if (empty($data['transaction_type'])) {
            $data['transaction_type'] = 'onetime';
        }

        $data = wp_parse_args($data, $this->getTransactionDefaults());

        if (empty($data['transaction_hash'])) {
            $data['transaction_hash'] = md5($data['transaction_type'] . '_payment_' . $data['booking_id'] . '-' . $data['trip_id'] . '_' . $data['created_at'] . '-' . time() . '-' . mt_rand(100, 999));
        }

        // will insert transaction and return transaction ID
        //table('trm_transactions')->insertGetId($data);
    }

    public function insertRefund($data)
    {
        $booking = $this->getBooking();
        $data['created_at'] = current_time('mysql');
        $data['updated_at'] = current_time('mysql');
        $data['trip_id'] = $booking->trip_id;
        $data['booking_id'] = $booking->id;
        $data['payment_method'] = $this->method;
        if (empty($data['transaction_type'])) {
            $data['transaction_type'] = 'refund';
        }

        if ($userId = get_current_user_id()) {
            $data['user_id'] = $userId;
        }

        if (empty($data['transaction_hash'])) {
            $data['transaction_hash'] = md5($data['transaction_type'] . '_payment_' . $data['booking_id'] . '-' . $data['trip_id'] . '_' . $data['created_at'] . '-' . time() . '-' . mt_rand(100, 999));
        }

        // return Transactions->insertGetId($data);
    }

    public function getTransaction($transactionId, $column = 'id')
    {
        // return Transactions->where($column, $transactionId)
        //     ->first();
    }

    public function getRefund($refundId, $column = 'id')
    {
        // return Transactions->where($column, $refundId)
        //     ->where('transaction_type', 'refund')
        //     ->first();
    }

    public function getTransactionByChargeId($chargeId)
    {
        // return Transactions->where('booking_id', $this->bookingId)
        //     ->where('charge_id', $chargeId)
        //     ->first();
    }

    public function getLastTransaction($bookingId)
    {
        $transaction = new Transaction();
        return $transaction->where('booking_id', $bookingId)
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function changeBookingPaymentStatus($newStatus)
    {
        do_action('trm/before_payment_status_change', $newStatus, $this->getBooking());

        $booking = new Booking();

        $booking->where('id', $this->bookingId)
            ->update([
                'payment_status' => $newStatus,
                'updated_at'     => current_time('mysql')
            ]);

        $this->booking = null;

        $logData = [
            'parent_source_id' => $this->getTrip()->id,
            'source_type'      => 'booking_item',
            'source_id'        => $this->bookingId,
            'component'        => 'Payment',
            'status'           => 'paid' === $newStatus ? 'success' : $newStatus,
            'title'            => __('Payment Status changed', 'travel-manager'),
            'description'      => __('Payment status changed to ', 'travel-manager') . $newStatus
        ];

        do_action('trm/log_data', $logData);

        do_action(
            'trm/after_payment_status_change',
            [
                $newStatus,
                $this->getBooking()
            ],
        );

        do_action('trm/after_payment_status_change', $newStatus, $this->getBooking());

        return true;
    }

    public function changeSubmissionPaymentStatus($newStatus)
    {
        do_action('fluentform/before_payment_status_change', $newStatus, $this->getBooking());

        $booking = new Booking();
        $booking->where('id', $this->bookingId)
            ->update([
                'payment_status' => $newStatus,
                'updated_at'     => current_time('mysql')
            ]);

        $this->booking = null;

        $logData = [
            'parent_source_id' => $this->getTrip()->id,
            'source_type'      => 'submission_item',
            'source_id'        => $this->bookingId,
            'component'        => 'Payment',
            'status'           => 'paid' === $newStatus ? 'success' : $newStatus,
            'title'            => __('Payment Status changed', 'travel-manager'),
            'description'      => __('Payment status changed to ', 'travel-manager') . $newStatus
        ];

        do_action('trm/log_data', $logData);

        do_action('trm/after_payment_status_change', $newStatus, $this->getBooking());

        return true;
    }

    public function recalculatePaidTotal()
    {
        // $transactions = Transactions->where('booking_id', $this->bookingId)
        //     ->whereIn('status', ['paid', 'requires_capture', 'processing', 'partially-refunded', 'refunded'])
        //     ->get();

        $total = 0;

        $refunds = $this->getRefundTotal();
        if ($refunds) {
            $total = $total - $refunds;
        }

        // Bookings->where('id', $this->bookingId)
        //     ->update([
        //         'total_paid' => $total,
        //         'updated_at' => current_time('mysql')
        //     ]);
    }

    public function getRefundTotal()
    {
        // $refunds = Transactions->where('booking_id', $this->bookingId)
        //     ->where('transaction_type', 'refund')
        //     ->get();

        $total = 0;
        // if ($refunds) {
        //     foreach ($refunds as $refund) {
        //         $total += $refund->payment_total;
        //     }
        // }

        return $total;
    }

    public function changeTransactionStatus($transactionId, $newStatus)
    {
        do_action(
            'trm/before_transaction_status_change',
            [
                $newStatus,
                $this->getBooking(),
                $transactionId
            ],
        );

        // Transactions->where('id', $transactionId)
        //     ->update([
        //         'status'     => $newStatus,
        //         'updated_at' => current_time('mysql')
        //     ]);

        do_action(
            'trm/after_transaction_status_change',
            $newStatus,
            $this->getBooking(),
            $transactionId
        );

        return true;
    }

    public function updateTransaction($transactionId, $data)
    {
        $data['updated_at'] = current_time('mysql');

        // return Transactions->where('id', $transactionId)
        //     ->update($data);
    }

    public function completePaymentBooking($isAjax = true)
    {
        $returnData = $this->getReturnData();
        if ($isAjax) {
            wp_send_json_success($returnData, 200);
        }
        return $returnData;
    }

    public function getReturnData()
    {
        // $booking = $this->getBooking();
        // $returnData = $bookingService->processbookingData(
        //     $this->bookingId, $booking->response
        // ); 
    }

    public function getBooking()
    {
        if (!is_null($this->booking)) {
            return $this->booking;
        }

        // will get booking data

        // $booking = Bookings->where('id', $this->bookingId)
        //     ->first();

        // if (!$booking) {
        //     return false;
        // }

        // $booking->response = json_decode($booking->response, true);

        // $this->booking = $booking;

        // return $this->booking;

    }

    public function getOrderItems()
    {
        $order = new Order();
        return $order->where('booking_id', $this->bookingId)
            ->where('type', '!=', 'discount') // type = single, signup_fee
            ->get();
    }

    public function getDiscountItems()
    {
        $order = new Order();
        return $order->where('booking_id', $this->bookingId)
            ->where('type', 'discount')
            ->get();
    }

    public function setMetaData($name, $value)
    {
        // $value = maybe_serialize($value);

        // return BookingMeta->insertGetId([
        //         'response_id' => $this->getBookingId(),
        //         'trip_id'     => $this->getTrip()->id,
        //         'meta_key'    => $name,
        //         'value'       => $value,
        //         'created_at'  => current_time('mysql'),
        //         'updated_at'  => current_time('mysql')
        //     ]);
    }

    public function getTrip()
    {
        if (!is_null($this->trip)) {
            return $this->trip;
        }

        $booking = $this->getBooking();

        // $this->trip = Trips->where('id', $booking->trip_id)
        //     ->first();

        // return $this->trip;
    }

    public function deleteMetaData($name)
    {
        // return BookingMeta->where('meta_key', $name)
        //     ->where('response_id', $this->getBookingId())
        //     ->delete();
    }

    public function getMetaData($metaKey)
    {
        // $meta = BookingMeta->where('response_id', $this->getBookingId())
        //     ->where('meta_key', $metaKey)
        //     ->first();

        // if ($meta && $meta->value) {
        //     return maybe_unserialize($meta->value);
        // }

        // return false;
    }

    public function showPaymentView($returnData)
    {
        $redirectUrl = ArrayHelper::get($returnData, 'result.redirectUrl');
        if ($redirectUrl) {
            wp_redirect($redirectUrl);
            exit();
        }

        $trip = $this->getTrip();

        if (!empty($returnData['title'])) {
            $title = $returnData['title'];
        } else if ($returnData['type'] == 'success') {
            $title = __('Payment Success', 'travel-manager');
        } else {
            $title = __('Payment Failed', 'travel-manager');
        }

        $message = $returnData['error'];
        if (!$message) {
            $message = $returnData['result']['message'];
        }

        $data = [
            'status'     => $returnData['type'],
            'trip'       => $trip,
            'title'      => $title,
            'booking' => $this->getBooking(),
            'message'    => $message,
            'is_new'     => $returnData['is_new'],
            'data'       => $returnData
        ];

        $data = apply_filters('trm/frameless_page_data', $data);

        add_filter('pre_get_document_title', function ($title) use ($data) {
            return $data['title'] . ' ' . apply_filters('document_title_separator', '-') . ' ' . $data['trip']->title;
        });

        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('fluent-trip-landing', TRM_URL . 'public/css/frameless.css', [], TRM_VERSION);
        });

        status_header(200);
        echo $this->loadView('frameless_view', $data);
        exit(200);
    }

    public function loadView($view, $data = [])
    {
        $file = TRM_DIR . 'src/views/' . $view . '.php';
        extract($data);
        ob_start();
        include($file);
        return ob_get_clean();
    }

    public function refund($refund_amount, $transaction, $booking, $method = '', $refundId = '', $refundNote = 'Refunded')
    {
        $this->setBookingId($booking->id);
        $status = 'refunded';

        $alreadyRefunded = $this->getRefundTotal();
        $totalRefund = intval($refund_amount + $alreadyRefunded);

        if ($totalRefund < $transaction->payment_total) {
            $status = 'partially-refunded';
        }

        $this->changeTransactionStatus($transaction->id, $status);
        $this->changeBookingPaymentStatus($status);
        $uniqueHash = md5('refund_' . $booking->id . '-' . $booking->trip_id . '-' . time() . '-' . mt_rand(100, 999));

        $refundData = [
            'trip_id'          => $booking->trip_id,
            'booking_id'    => $booking->id,
            'transaction_hash' => $uniqueHash,
            'payment_method'   => $transaction->payment_method,
            'charge_id'        => $refundId,
            'payment_note'     => $refundNote,
            'payment_total'    => $refund_amount,
            'currency'         => $transaction->currency,
            'payment_mode'     => $transaction->payment_mode,
            'created_at'       => current_time('mysql'),
            'updated_at'       => current_time('mysql'),
            'status'           => 'refunded',
            'transaction_type' => 'refund'
        ];

        $refundId = $this->insertRefund($refundData);

        $logData = [
            'parent_source_id' => $booking->trip_id,
            'source_type'      => 'booking_item',
            'source_id'        => $booking->id,
            'component'        => 'Payment',
            'status'           => 'info',
            'title'            => __('Refund issued', 'travel-manager'),
            'description'      => __('Refund issued and refund amount: ', 'travel-manager') . number_format($refund_amount / 100, 2)
        ];

        do_action('trm/log_data', $logData);

        $this->recalculatePaidTotal();
        $refund = $this->getRefund($refundId);

        do_action('trm/payment_' . $status . '_' . $method, $refund, $transaction, $booking);
        do_action('trm/payment_' . $status, $refund, $transaction, $booking);
    }

    public function updateRefund($totalRefund, $transaction, $booking, $method = '', $refundId = '', $refundNote = 'Refunded')
    {
        if(!$totalRefund) {
            return;
        }

        $this->setBookingId($booking->id);
        // $existingRefund = Transactions->where('booking_id', $booking->id)
        //     ->where('transaction_type', 'refund')
        //     ->first();

        // if ($existingRefund) {

        //     if ($existingRefund->payment_total == $totalRefund) {
        //         return;
        //     }

        //     $status = 'refunded';
        //     if ($totalRefund < $transaction->payment_total) {
        //         $status = 'partially-refunded';
        //     }
        //     $updateData = [
        //         'trip_id'          => $booking->trip_id,
        //         'booking_id'    => $booking->id,
        //         'payment_method'   => $transaction->payment_method,
        //         'charge_id'        => $refundId,
        //         'payment_note'     => $refundNote,
        //         'payment_total'    => $totalRefund,
        //         'payment_mode'     => $transaction->payment_mode,
        //         'created_at'       => current_time('mysql'),
        //         'updated_at'       => current_time('mysql'),
        //         'status'           => 'refunded',
        //         'transaction_type' => 'refund'
        //     ];

        //     Transactions->where('id', $existingRefund->id)
        //         ->update($updateData);

        //     $existingRefund = Transactions
        //         ->where('booking_id', $booking->id)
        //         ->where('transaction_type', 'refund')
        //         ->first();

        //     if ($transaction->status != $status) {
        //         $this->changeTransactionStatus($transaction->id, $status);
        //     }

        //     do_action('trm/payment_refund_updated_' . $method, $existingRefund, $existingRefund->trip_id);

        //     do_action('trm/payment_refund_updated', $existingRefund, $existingRefund->trip_id);

        // } else {
        //     $this->refund($totalRefund, $transaction, $booking, $method, $refundId, $refundNote);
        // }
    }

    private function maybeAutoLogin($loginId, $booking)
    {
        if (is_user_logged_in() || !$loginId) {
            return;
        }
        if ($loginId != $booking->user_id) {
            return;
        }

        wp_clear_auth_cookie();
        wp_set_current_user($loginId);
        wp_set_auth_cookie($loginId);
        $this->deleteMetaData('_make_auto_login');
    }

    public function getAmountTotal()
    {
        $orderItems = $this->getOrderItems();

        $amountTotal = 0;
        foreach ($orderItems as $item) {
            $amountTotal += $item->line_total;
        }

        $discountItems = $this->getDiscountItems();
        foreach ($discountItems as $discountItem) {
            $amountTotal -= $discountItem->line_total;
        }

        return $amountTotal;
    }

    public function handleSessionRedirectBack($data)
    {
        $bookingId = intval($data['trm_payment']);
        $this->setBookingId($bookingId);

        $booking = $this->getBooking();

        $transactionHash = sanitize_text_field($data['transaction_hash']);
        $transaction = $this->getTransaction($transactionHash, 'transaction_hash');

        if (!$transaction || !$booking) {
            return;
        }

        // $type = $transaction->status;
        // $trip = $this->getTrip();

        // if ($type == 'paid') {
        //     $returnData = $this->getReturnData();
        // } else {
        //     $returnData = [
        //         'insert_id' => $booking->id,
        //         'title'     => __('Payment was not marked as paid', 'travel-manager'),
        //         'result'    => false,
        //         'error'     => __('Looks like you have is still on pending status', 'travel-manager')
        //     ];
        // }

        // $returnData['type'] = 'success';
        // $returnData['is_new'] = false;

        // $this->showPaymentView($returnData);
    }

    public function updateBooking($id, $data)
    {
        // $data['updated_at'] = current_time('mysql');

        // return Bookings->where('id', $id)
        //     ->update($data);
    }

    public function limitLength($string, $limit = 127)
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

    public function getTransactionDefaults()
    {
        $booking = $this->getBooking();
        if (!$booking) {
            return [];
        }

        $data = [];

        if ($customerEmail = PaymentHelper::getCustomerEmail($booking, $this->getTrip())) {
            $data['payer_email'] = $customerEmail;
        }

        if ($customerName = PaymentHelper::getCustomerName($booking, $this->getTrip())) {
            $data['payer_name'] = $customerName;
        }

        if ($booking->user_id) {
            $data['user_id'] = $booking->user_id;
        } else if ($user = get_user_by('ID', get_current_user_id())) {
            $data['user_id'] = $user->ID;
        }

        if (!$booking->user_id && !empty($data['payer_email'])) {
            $email = $data['payer_email'];
            $maybeUser = get_user_by('email', $email);
            if ($maybeUser) {

                $this->updateBooking($booking->id, [
                    'user_id' => $maybeUser->ID
                ]);

                if (empty($data['user_id'])) {
                    $data['user_id'] = $maybeUser->ID;
                }
            }
        }

        $address = PaymentHelper::getCustomerAddress($booking);
        if (!$address) {
            $address = ArrayHelper::get($booking->response, 'address_1');
        }
        if ($address) {
            $address = array_filter($address);
            if ($address) {
                $data['billing_address'] = implode(', ', $address);
            }
        }

        $data['created_at'] = current_time('mysql');
        $data['updated_at'] = current_time('mysql');
        $data['trip_id'] = $booking->trip_id;
        $data['booking_id'] = $booking->id;
        $data['payment_method'] = $this->method;

        return $data;
    }

    abstract public function getPaymentMode();

    public function createInitialPendingTransaction($booking = false)
    {
        if (!$booking) {
            $booking = $this->getBooking();
        }

        $trip = $this->getTrip();

        $uniqueHash = md5($booking->id . '-' . $trip->id . '-' . time() . '-' . mt_rand(100, 999));

        $transactionData = [
            'transaction_type' => 'onetime',
            'transaction_hash' => $uniqueHash,
            'payment_total'    => $this->getAmountTotal(),
            'status'           => 'pending',
            'currency'         => strtoupper($booking->currency),
            'payment_mode'     => $this->getPaymentMode()
        ];

        $transactionId = $this->insertTransaction($transactionData);

        $this->updateBooking($booking->id, [
            'payment_total' => $transactionData['payment_total']
        ]);

        return $this->getTransaction($transactionId);

    }
}
