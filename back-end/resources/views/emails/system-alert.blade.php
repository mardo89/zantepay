<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZANTEPAY</title>

    <style type="text/css">

        h2 {
            color: rgba(249,33,18,.6);
        }

        #alert-message {
            color: #000;
        }

    </style>
</head>

<body>
<div class="container">
    <h2>System ALERT !!!</h2>

    <br/>

    <h4>{{ $event }}</h4>

    <br/>

    <span id="alert-message">
        {{ $alertMessage }}
    </span>
</div>
</body>
