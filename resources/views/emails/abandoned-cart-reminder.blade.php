<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Items waiting in your cart</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Hi {{ $user->name }},</h2>
        <p>You have left some items in your cart. Don't worry, we've saved them for you.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead style="background-color: #f8f9fa;">
                <tr>
                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Product</th>
                    <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Quantity</th>
                    <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <div style="font-weight: bold;">{{ $item->product->title }}</div>
                            <div style="font-size: 12px; color: #666;">{{ $item->product->sku ?? $item->product->slug }}</div>
                        </td>
                        <td style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">
                            {{ $item->quantity }}
                        </td>
                        <td style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">
                            £{{ number_format($item->price, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('cart') }}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold;">Return to Cart</a>
        </div>
        
        <p style="margin-top: 30px; font-size: 12px; color: #999;">
            This email was sent to {{ $user->email }}. If you didn't add these items, please ignore this email.
        </p>
    </div>
</body>
</html>
