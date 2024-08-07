{{-- <form action="/payment" method="POST">
    @csrf
    <input type="text" name="order_id" placeholder="Order ID" required>
    <input type="number" name="gross_amount" placeholder="Amount" required>
    <input type="text" name="customer_name" placeholder="Customer Name" required>
    <input type="email" name="customer_email" placeholder="Customer Email" required>
    <input type="text" name="customer_phone" placeholder="Customer Phone" required>
    <button type="submit">Pay Now</button>
</form> --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body>
    <h1>Payment</h1>
    <button id="pay-button">Pay Now</button>

    <script type="text/javascript">
        document.getElementById('pay-button').addEventListener('click', function () {
            fetch('/create-transaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    order_id: 'order123', // Ganti dengan ID pesanan yang sesuai
                    amount: 100000, // Ganti dengan jumlah yang sesuai
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            console.log('success:', result);
                            // Kirimkan data pembayaran ke server untuk memproses status pembayaran
                            fetch('/payment-success', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({
                                    order_id: result.order_id,
                                    status: result.transaction_status,
                                }),
                            });
                        },
                        onPending: function (result) {
                            console.log('pending:', result);
                        },
                        onError: function (result) {
                            console.log('error:', result);
                        }
                    });
                } else {
                    console.log('Error:', data.error);
                }
            });
        });
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</body>
</html>
