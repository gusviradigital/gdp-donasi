<?php
/**
 * Payment Class
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Classes;

if (!defined('ABSPATH')) {
    exit;
}

class Payment {
    /**
     * Instance
     */
    private static $instance = null;

    /**
     * Table name
     */
    private $table;

    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'gdp_payment_history';
        
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Handle payment gateway callbacks
        add_action('init', [$this, 'handle_payment_callback']);

        // AJAX actions
        add_action('wp_ajax_gdp_create_payment', [$this, 'ajax_create_payment']);
        add_action('wp_ajax_nopriv_gdp_create_payment', [$this, 'ajax_create_payment']);
    }

    /**
     * Create payment via AJAX
     */
    public function ajax_create_payment() {
        try {
            // Verify nonce
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'gdp_payment_nonce')) {
                throw new \Exception(__('Invalid security token sent.', 'gusviradigital'));
            }

            // Validate required fields
            $required_fields = ['donation_id', 'payment_method'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception(sprintf(__('Field %s is required.', 'gusviradigital'), $field));
                }
            }

            // Get donation
            $donation = gdp_donation()->get(absint($_POST['donation_id']));
            if (!$donation) {
                throw new \Exception(__('Invalid donation ID.', 'gusviradigital'));
            }

            // Create payment
            $payment_data = $this->create_payment($donation, sanitize_text_field($_POST['payment_method']));

            // Return success response
            wp_send_json_success([
                'payment_data' => $payment_data,
                'message' => __('Payment created successfully.', 'gusviradigital'),
            ]);

        } catch (\Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create payment
     */
    public function create_payment($donation, $payment_method) {
        // Get active payment gateway
        $gateway = gdp_get_active_payment_gateway();
        if (!$gateway) {
            throw new \Exception(__('No active payment gateway.', 'gusviradigital'));
        }

        // Create payment based on gateway
        switch ($gateway) {
            case 'midtrans':
                return $this->create_midtrans_payment($donation, $payment_method);
            case 'xendit':
                return $this->create_xendit_payment($donation, $payment_method);
            case 'tripay':
                return $this->create_tripay_payment($donation, $payment_method);
            default:
                throw new \Exception(__('Invalid payment gateway.', 'gusviradigital'));
        }
    }

    /**
     * Create Midtrans payment
     */
    private function create_midtrans_payment($donation, $payment_method) {
        // Get settings
        $settings = gdp_get_payment_gateway_settings();
        $midtrans = $settings['midtrans'];

        // Load Midtrans PHP library
        require_once GDP_INC . '/libraries/midtrans/Midtrans.php';

        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = $midtrans['server_key'];
        \Midtrans\Config::$isProduction = !$settings['test_mode'];
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Prepare transaction data
        $transaction_details = [
            'order_id' => 'GDP-' . $donation->id . '-' . time(),
            'gross_amount' => $donation->amount,
        ];

        // Prepare customer details
        $customer_details = [
            'first_name' => $donation->name,
            'email' => $donation->email,
            'phone' => $donation->phone,
        ];

        // Prepare item details
        $program = get_post($donation->program_id);
        $item_details = [
            [
                'id' => $donation->program_id,
                'price' => $donation->amount,
                'quantity' => 1,
                'name' => $program->post_title,
            ],
        ];

        // Prepare transaction data
        $transaction_data = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        ];

        // Add payment method if specified
        if ($payment_method) {
            $transaction_data['enabled_payments'] = [$payment_method];
        }

        try {
            // Create Midtrans Snap token
            $snap_token = \Midtrans\Snap::getSnapToken($transaction_data);

            // Save payment history
            $this->save_payment_history([
                'donation_id' => $donation->id,
                'payment_id' => $transaction_details['order_id'],
                'payment_method' => 'midtrans',
                'amount' => $donation->amount,
                'status' => 'pending',
                'raw_response' => json_encode([
                    'snap_token' => $snap_token,
                    'transaction_data' => $transaction_data,
                ]),
            ]);

            // Update donation payment method
            gdp_donation()->update_status($donation->id, 'pending', [
                'payment_id' => $transaction_details['order_id'],
                'payment_method' => 'midtrans',
                'snap_token' => $snap_token,
            ]);

            return [
                'snap_token' => $snap_token,
                'client_key' => $midtrans['client_key'],
            ];

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Create Xendit payment
     */
    private function create_xendit_payment($donation, $payment_method) {
        // Get settings
        $settings = gdp_get_payment_gateway_settings();
        $xendit = $settings['xendit'];

        // Load Xendit PHP library
        require_once GDP_INC . '/libraries/xendit/XenditPHPClient.php';

        // Initialize Xendit client
        $xendit_client = new \XenditClient($xendit['secret_key']);

        // Prepare external ID
        $external_id = 'GDP-' . $donation->id . '-' . time();

        // Prepare payment data
        $payment_data = [
            'external_id' => $external_id,
            'amount' => $donation->amount,
            'payer_email' => $donation->email,
            'description' => sprintf(
                __('Donation for %s', 'gusviradigital'),
                get_the_title($donation->program_id)
            ),
            'success_redirect_url' => gdp_get_donation_success_page_url(),
            'failure_redirect_url' => gdp_get_donation_failed_page_url(),
        ];

        try {
            // Create invoice
            $invoice = $xendit_client->createInvoice($payment_data);

            // Save payment history
            $this->save_payment_history([
                'donation_id' => $donation->id,
                'payment_id' => $external_id,
                'payment_method' => 'xendit',
                'amount' => $donation->amount,
                'status' => 'pending',
                'raw_response' => json_encode($invoice),
            ]);

            // Update donation payment method
            gdp_donation()->update_status($donation->id, 'pending', [
                'payment_id' => $external_id,
                'payment_method' => 'xendit',
                'invoice_url' => $invoice['invoice_url'],
            ]);

            return [
                'invoice_url' => $invoice['invoice_url'],
            ];

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Create Tripay payment
     */
    private function create_tripay_payment($donation, $payment_method) {
        // Get settings
        $settings = gdp_get_payment_gateway_settings();
        $tripay = $settings['tripay'];

        // Prepare merchant ref
        $merchant_ref = 'GDP-' . $donation->id . '-' . time();

        // Prepare API URL
        $api_url = $settings['test_mode'] 
            ? 'https://tripay.co.id/api-sandbox/transaction/create'
            : 'https://tripay.co.id/api/transaction/create';

        // Prepare customer data
        $customer = [
            'name' => $donation->name,
            'email' => $donation->email,
            'phone' => $donation->phone,
        ];

        // Prepare item details
        $program = get_post($donation->program_id);
        $items = [
            [
                'name' => $program->post_title,
                'price' => $donation->amount,
                'quantity' => 1,
            ],
        ];

        // Prepare request data
        $request_data = [
            'method' => $payment_method,
            'merchant_ref' => $merchant_ref,
            'amount' => $donation->amount,
            'customer_name' => $customer['name'],
            'customer_email' => $customer['email'],
            'customer_phone' => $customer['phone'],
            'order_items' => $items,
            'return_url' => gdp_get_donation_success_page_url(),
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature' => hash_hmac('sha256', $tripay['merchant_code'].$merchant_ref.$donation->amount, $tripay['private_key']),
        ];

        // Send request to Tripay
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.$tripay['api_key'],
                'Content-Type: application/json',
            ],
            CURLOPT_FAILONERROR => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($request_data),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            throw new \Exception($error);
        }

        $result = json_decode($response, true);
        if (!$result['success']) {
            throw new \Exception($result['message']);
        }

        // Save payment history
        $this->save_payment_history([
            'donation_id' => $donation->id,
            'payment_id' => $merchant_ref,
            'payment_method' => 'tripay',
            'amount' => $donation->amount,
            'status' => 'pending',
            'raw_response' => $response,
        ]);

        // Update donation payment method
        gdp_donation()->update_status($donation->id, 'pending', [
            'payment_id' => $merchant_ref,
            'payment_method' => 'tripay',
            'checkout_url' => $result['data']['checkout_url'],
        ]);

        return [
            'checkout_url' => $result['data']['checkout_url'],
        ];
    }

    /**
     * Handle payment callback
     */
    public function handle_payment_callback() {
        $gateway = gdp_get_active_payment_gateway();
        if (!$gateway) {
            return;
        }

        switch ($gateway) {
            case 'midtrans':
                $this->handle_midtrans_callback();
                break;
            case 'xendit':
                $this->handle_xendit_callback();
                break;
            case 'tripay':
                $this->handle_tripay_callback();
                break;
        }
    }

    /**
     * Handle Midtrans callback
     */
    private function handle_midtrans_callback() {
        try {
            $notification = new \Midtrans\Notification();

            $transaction = $notification->transaction_status;
            $fraud = $notification->fraud_status;
            $order_id = $notification->order_id;

            // Get donation ID from order ID
            preg_match('/GDP-(\d+)-/', $order_id, $matches);
            $donation_id = $matches[1];

            // Get donation
            $donation = gdp_donation()->get($donation_id);
            if (!$donation) {
                throw new \Exception('Donation not found');
            }

            // Handle transaction status
            if ($transaction == 'capture') {
                if ($fraud == 'challenge') {
                    $status = 'processing';
                } else if ($fraud == 'accept') {
                    $status = 'completed';
                }
            } else if ($transaction == 'settlement') {
                $status = 'completed';
            } else if ($transaction == 'pending') {
                $status = 'pending';
            } else if ($transaction == 'deny') {
                $status = 'failed';
            } else if ($transaction == 'expire') {
                $status = 'cancelled';
            } else if ($transaction == 'cancel') {
                $status = 'cancelled';
            }

            // Update donation status
            gdp_donation()->update_status($donation_id, $status, [
                'transaction_status' => $transaction,
                'fraud_status' => $fraud,
            ]);

            // Save payment history
            $this->save_payment_history([
                'donation_id' => $donation_id,
                'payment_id' => $order_id,
                'payment_method' => 'midtrans',
                'amount' => $notification->gross_amount,
                'status' => $status,
                'raw_response' => json_encode($_POST),
            ]);

            header('HTTP/1.1 200 OK');
            exit();

        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            exit();
        }
    }

    /**
     * Handle Xendit callback
     */
    private function handle_xendit_callback() {
        try {
            // Verify callback token
            $callback_token = $_SERVER['HTTP_X_CALLBACK_TOKEN'] ?? '';
            $settings = gdp_get_payment_gateway_settings();
            if ($callback_token !== $settings['xendit']['callback_token']) {
                throw new \Exception('Invalid callback token');
            }

            // Get callback data
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                throw new \Exception('Invalid callback data');
            }

            // Get donation ID from external ID
            preg_match('/GDP-(\d+)-/', $data['external_id'], $matches);
            $donation_id = $matches[1];

            // Get donation
            $donation = gdp_donation()->get($donation_id);
            if (!$donation) {
                throw new \Exception('Donation not found');
            }

            // Map status
            $status_map = [
                'PAID' => 'completed',
                'EXPIRED' => 'cancelled',
                'FAILED' => 'failed',
            ];
            $status = $status_map[$data['status']] ?? 'pending';

            // Update donation status
            gdp_donation()->update_status($donation_id, $status, [
                'payment_status' => $data['status'],
                'payment_method' => $data['payment_method'],
            ]);

            // Save payment history
            $this->save_payment_history([
                'donation_id' => $donation_id,
                'payment_id' => $data['external_id'],
                'payment_method' => 'xendit',
                'amount' => $data['amount'],
                'status' => $status,
                'raw_response' => json_encode($data),
            ]);

            header('HTTP/1.1 200 OK');
            exit();

        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            exit();
        }
    }

    /**
     * Handle Tripay callback
     */
    private function handle_tripay_callback() {
        try {
            // Get callback data
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                throw new \Exception('Invalid callback data');
            }

            // Verify callback signature
            $settings = gdp_get_payment_gateway_settings();
            $signature = hash_hmac('sha256', $data['merchant_ref'].$data['status'], $settings['tripay']['private_key']);
            if ($signature !== $data['signature']) {
                throw new \Exception('Invalid signature');
            }

            // Get donation ID from merchant ref
            preg_match('/GDP-(\d+)-/', $data['merchant_ref'], $matches);
            $donation_id = $matches[1];

            // Get donation
            $donation = gdp_donation()->get($donation_id);
            if (!$donation) {
                throw new \Exception('Donation not found');
            }

            // Map status
            $status_map = [
                'PAID' => 'completed',
                'EXPIRED' => 'cancelled',
                'FAILED' => 'failed',
                'REFUND' => 'refunded',
            ];
            $status = $status_map[$data['status']] ?? 'pending';

            // Update donation status
            gdp_donation()->update_status($donation_id, $status, [
                'payment_status' => $data['status'],
                'payment_method' => $data['payment_method'],
            ]);

            // Save payment history
            $this->save_payment_history([
                'donation_id' => $donation_id,
                'payment_id' => $data['merchant_ref'],
                'payment_method' => 'tripay',
                'amount' => $data['amount'],
                'status' => $status,
                'raw_response' => json_encode($data),
            ]);

            header('HTTP/1.1 200 OK');
            exit();

        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            exit();
        }
    }

    /**
     * Save payment history
     */
    private function save_payment_history($data) {
        global $wpdb;

        $result = $wpdb->insert(
            $this->table,
            $data,
            [
                '%d', // donation_id
                '%s', // payment_id
                '%s', // payment_method
                '%f', // amount
                '%s', // status
                '%s', // raw_response
            ]
        );

        if (false === $result) {
            throw new \Exception(__('Failed to save payment history.', 'gusviradigital'));
        }

        return $wpdb->insert_id;
    }
}

// Initialize
Payment::get_instance(); 