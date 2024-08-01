<x-mail::message>
# Successful Transaction

Dear {{$permitApplication->user->name}},<br>

We are pleased to inform you that your transaction has been successfully processed. Thank you for your payment.<br>
The following are the details of your application:<br>

@component('mail::table')
| Field              | Details                      |
| :----------------- | :---------------------------- |
| **Application ID** | {{ $permitApplication->id }}  |
| **Amount**         | $ |
| **Date**           | {{ $permitApplication->created_at->format('Y-m-d H:i:s') }} |
| **Status**         | Paid                         |
@endcomponent

If you have any questions or need further assistance, please contact us <a href="mailto:support@example.com">here</a>.
To view the details of your applications, please visit the <a href="{{ url('application/'.$permitApplication->id) }}">application page</a>.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
