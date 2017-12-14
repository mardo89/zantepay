<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZANTEPAY</title>

    <style type="text/css">
        h2 {
            color: #333366;
        }

        #message {
            color: #000;
            width: 100%;
        }

        .title-col {
            width: 150px;
        }
    </style>
</head>

<body>
<div class="container">
    <h2>New user registered for  Pre-ICO</h2>

    <br/>

    <table id="message">
        <tbody>
        <tr>
            <td class="title-col">
                <b>EMAIL</b>
            </td>

            <td>
                {{ $email }}
            </td>
        </tr>

        <tr>
            <td class="title-col">
                <b>CURRENCY</b>
            </td>

            <td>
                {{ $currency }}
            </td>
        </tr>

        <tr>
            <td class="title-col">
                <b>AMOUNT</b>
            </td>

            <td>
                {{ $amount }}
            </td>
        </tr>

        </tbody>
    </table>

</div>
</body>
