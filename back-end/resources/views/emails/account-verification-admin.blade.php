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

        #error {
            color: rgba(249,33,18,.6);
        }

        .title-col {
            width: 150px;
        }
    </style>
</head>

<body>
<div class="container">
    <h2>Account verification error: </h2>

    <br/>

    <p id="error"> {{ $error }} </p>

    <table>
        <tbody>

        <tr>
            <td class="title-col">
                <b>Response status:</b>
            </td>

            <td>
                {{ $status }}
            </td>
        </tr>

        <tr>
            <td class="title-col">
                <b>Session ID</b>
            </td>

            <td>
                {{ $response['session_id'] }}
            </td>
        </tr>

        <tr>
            <td class="title-col">
                <b>Verification status:</b>
            </td>

            <td>
                {{ $response['response_status'] }}
            </td>
        </tr>

        <tr>
            <td class="title-col">
                <b>Verification code:</b>
            </td>

            <td>
                {{ $response['response_code'] }}
            </td>
        </tr>

        <tr>
            <td class="title-col">
                <b>Fail reason:</b>
            </td>

            <td>
                {{ $response['fail_reason'] }}
            </td>
        </tr>

        <tr>
            <td class="title-col">
                <b>Acceptance time:</b>
            </td>

            <td>
                {{ $response['acceptance_time'] }}
            </td>
        </tr>

        </tbody>
    </table>

</div>
</body>
