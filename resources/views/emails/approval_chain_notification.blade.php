<x-mail::message>
# Permit Application Review
Dear {{$user->name}},<br>
The following permit application has been updated and requires reviewing:

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width: 30%; text-align: left; padding: 8px; border: 1px solid #dddddd;">Field</th>
            <th style="width: 70%; text-align: left; padding: 8px; border: 1px solid #dddddd;">Details</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding: 8px; border: 1px solid #dddddd;">Permit ID</td>
            <td style="padding: 8px; border: 1px solid #dddddd;">{{ $permitApplication->id }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #dddddd;">User Account</td>
            <td style="padding: 8px; border: 1px solid #dddddd;">{{ $permitApplication->user->name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #dddddd;">Permit Holder</td>
            <td style="padding: 8px; border: 1px solid #dddddd;">{{ $permitApplication->holder->name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #dddddd;">Account Category</td>
            <td style="padding: 8px; border: 1px solid #dddddd;">{{ $permitApplication->applicantcat->name }}</td>
        </tr>
    </tbody>
</table>

Please review the application at your earliest convenience.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
