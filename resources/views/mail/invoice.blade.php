@extends('layouts.mail')@section('mail-content')
<p>Dear {{ $user }},</p>
<p><br></p>
<p>Thank you for your purchase! Below is a summary of your order placed on <strong>{{ $order_date }}</strong>. We
    appreciate your purchase from <strong>{{ $app_name }}</strong>. All products are delivered digitally and are now
    available in your account or sent via email. Your purchased zip file has been attached to this email. Please
    download and save it securely.</p>
<p><br></p>
<p><strong>Invoice Detail:</strong></p>
<p>{{ $invoice_table }}</p>
<p><br></p>
<p><strong>Billing Information:</strong></p>
<p>• <strong>Full Name:</strong> {{ $user }}</p>
<p>• <strong>Phone Number:</strong> {{ $phone }}</p>
<p>• <strong>Email:</strong> {{ $email }}</p>
<p>• <strong>Country:</strong> {{ $country }}</p>
<p><br></p>
<p>If you have any questions or need help accessing your digital products, just reply to this email — we’re happy to
    help!</p>
<p><br></p>
<p>Thank you again for your trust and support.</p>
<p><br></p>
<p>Need help? Contact us at <a href="mailto:{{ $support_mail }}" rel="noopener noreferrer"
        target="_blank">{{ $support_mail }}</a></p>
<p>© {{ date('Y') }} {{ $app_name }}. All rights reserved.</p>
@endsection
