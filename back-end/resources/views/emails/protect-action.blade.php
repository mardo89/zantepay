<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZANTEPAY</title>

    <style type="text/css">

        h2 {
            color: rgba(249, 33, 18, .6);
        }

        #secret-key {
            color: #000;
        }

    </style>
</head>

<body>
<div class="container">
    <h2>You are trying to {{ $action }}</h2>

    <br/>

    <div id="secret-key">
        <p>Use secret key to approve this operation:</p>

        <p><b>{{ $signature }}</b></p>

        <p>This key will be expired in 2 min</p>
    </div>
</div>
</body>
