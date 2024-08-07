<?php

namespace App\Http\Controllers;

// use App\Services\MidtransService;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Transaction;
use Midtrans\Snap;

class PaymentController extends Controller
{


    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.environment') === 'production';
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        $transactionDetails = [
            'order_id' => $request->order_id,
            'gross_amount' => $request->amount,
        ];

        $itemDetails = [
            [
                'id' => $request->order_id,
                'price' => $request->amount,
                'quantity' => 1,
                'name' => 'Order Payment',
            ],
        ];

        $transaction = [
            'payment_type' => 'bank_transfer',
            'bank_transfer' => [
                'bank' => 'bca', // Ubah sesuai dengan bank yang Anda inginkan
            ],
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($transaction);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function success(Request $request)
    {
        $orderId = $request->input('order_id');
        $status = $request->input('status');

        // Implement logic to handle successful payment
        // Update order status in your database, etc.

        return response()->json(['status' => 'Payment successful', 'order_id' => $orderId, 'status' => $status]);
    }


    // protected $midtransService;

    // public function __construct(MidtransService $midtransService)
    // {
    //     $this->midtransService = $midtransService;
    // }

    // public function createPayment(Request $request)
    // {
    //     $validated = $request->validate([
    //         'order_id' => 'required|string',
    //         'gross_amount' => 'required|numeric',
    //         'customer_name' => 'required|string',
    //         'customer_email' => 'required|email',
    //         'customer_phone' => 'required|string',
    //     ]);

    //     $redirectUrl = $this->midtransService->createTransaction(
    //         $validated['order_id'],
    //         $validated['gross_amount'],
    //         $validated['customer_name'],
    //         $validated['customer_email'],
    //         $validated['customer_phone']
    //     );

    //     return redirect()->away($redirectUrl);
    // }

    // public function paymentSuccess(Request $request)
    // {
    //     $phone = $request->input('phone');
    //     $message = "Terima kasih, pembayaran Anda telah berhasil. Order ID: {$request->input('order_id')}. Detail lebih lanjut akan dikirimkan via email.";

    //     $whatsappUrl = "https://wa.me/+6282138539895{$phone}?text=" . urlencode($message);

    //     return redirect()->away($whatsappUrl);
    // }


    // public function showPaymentDetails(Request $request)
    // {
    //     return view('payment.details', [
    //         'order_id' => $request->input('order_id'),
    //         'gross_amount' => $request->input('gross_amount'),
    //         'customer_name' => $request->input('customer_name'),
    //         'customer_email' => $request->input('customer_email'),
    //         'customer_phone' => $request->input('customer_phone'),
    //     ]);
    // }
}
