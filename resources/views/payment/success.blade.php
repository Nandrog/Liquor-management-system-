<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success">
            <h1>Payment Successful!</h1>
            <p>Thank you for your order, {{ $order->user->name ?? 'Customer' }}.</p>
            <p>Your Order ID is: <strong>{{ $order->id }}</strong></p>
            <p>We have received your payment and your order is now being processed.</p>
            <a href="/" class="btn btn-primary">Go to Homepage</a>
        </div>
    </div>
</body>
</html>
