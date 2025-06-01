{{-- <!DOCTYPE html>
<html>
<head>
    <title>Application Status</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>

    <p>Your application to <strong>{{ $type }}</strong> the property <strong>{{ $property->title }}</strong> has been <strong>{{ $status }}</strong>.</p>

    @if($status === 'Approved')
        <p>Congratulations! Please contact the agent to proceed with the next steps.</p>
    @else
        <p>We're sorry to inform you that your application was not successful. You can try applying for other properties.</p>
    @endif

    <p>Thank you for using our platform.</p>
</body>
</html> --}}


<!DOCTYPE html>
<html>
<head>
    <title>Application Status</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>

    <p>Your application to <strong>{{ ucfirst($type) }}</strong> the property <strong>{{ $property->title }}</strong> has been <strong>{{ ucfirst($status) }}</strong>.</p>

    @if($status === 'Approved')
        <p>Congratulations! You can now confirm your payment to proceed.</p>

        {{-- <p>
            <a href="{{ url('/payment/create?property_id=' . $property->id . '&type=' . lcfirst($type)) }}" 
               style="display:inline-block;padding:10px 20px;background-color:#28a745;color:#fff;text-decoration:none;border-radius:5px;">
                Confirm Payment
            </a>
        </p> --}}
    @else
        <p>We're sorry to inform you that your application was not successful. You can try applying for other properties.</p>
    @endif

    <p>Thank you for using our platform.</p>
</body>
</html>
