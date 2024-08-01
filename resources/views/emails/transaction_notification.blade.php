<x-mail::message>
# Transaction Confirmation

Dear {{$permitApplication->user->name}},<br>

Your application is now reviewed and pending for your payment. Please complete the transaction at your earliest convenience.<br>
The following are the details of your application:<br>

@php
    if($permitApplication->transaction_status == 0) {
        $status = "Unpaid";
    } elseif ($permitApplication->transaction_status == 1) {
        $status = "Paid";
    } else {
        $status = "Unknown";
    }
@endphp

@component('mail::table')
| Field              | Details                      |
| :----------------- | :---------------------------- |
| **Application ID** | {{ $permitApplication->id }}  |
| **Amount**         | ${{ $permitApplication->amount }} |
| **Date**           | {{ $permitApplication->created_at->format('Y-m-d H:i:s') }} |
| **Status**         | {{ $status }}{{$permitApplication->transaction_status}}                |
@endcomponent

To view or manage your payment details, please visit the payment page <a href="{{ url('application/'.$permitApplication->id) }}">here</a>.
Thank you for your application. If you have any questions, do not hesitate to <a href="mailto:support@example.com">contact us </a>.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
