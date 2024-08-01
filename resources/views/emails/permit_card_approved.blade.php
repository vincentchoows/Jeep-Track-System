<x-mail::message>
# Your Permit Card is Fully Approved and Activated

Dear {{ $permitApplication->user->name }},

Congratulations! Your permit card has been fully approved and activated.

Here are the details:

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width: 30%; text-align: left; padding: 8px; border: 1px solid #dddddd;">Field</th>
            <th style="width: 70%; text-align: left; padding: 8px; border: 1px solid #dddddd;">Details</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding: 8px; border: 1px solid #dddddd;">Card ID</td>
            <td style="padding: 8px; border: 1px solid #dddddd;">{{ $card->id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #dddddd;">Cardholder Name</td>
            <td style="padding: 8px; border: 1px solid #dddddd;">{{ $card->holder_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #dddddd;">Expiry Date</td>
            <td style="padding: 8px; border: 1px solid #dddddd;">{{ $card->expiry_date ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #dddddd;">Status</td>
            <td style="padding: 8px; border: 1px solid #dddddd;">{{ $card->status ?? 'N/A' }}</td>
        </tr>
    </tbody>
</table>
<br>


If you have any questions, please contact our support team.

To view your application details, please visit the <a href="{{ url('mypermit/'.$permitApplication->id) }}">permit page</a>.
Thank you for your application. If you have any questions, do not hesitate to <a href="mailto:support@example.com">contact us </a>.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
