<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZANTEPAY</title>
</head>

<body>
<div class="container">
    <h2>Contributions contain incorrect data</h2>

    <br/>

    <table id="message">
        <thead>
            <tr>
                <th>
                    User ID
                </th>
                <th>
                    Proxy
                </th>
                <th>
                    Date
                </th>
                <th>
                    API Amount
                </th>
                <th>
                    DB Amount
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach($contributionsList as $contribution)
            <tr>
                <td>
                    {{$contribution['user_id']}}
                </td>

                <td>
                    {{$contribution['proxy']}}
                </td>

                <td>
                    {{$contribution['date']}}
                </td>

                <td>
                    {{$contribution['api_amount']}}
                </td>

                <td>
                    {{$contribution['db_amount']}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
</body>
