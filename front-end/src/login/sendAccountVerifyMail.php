<?php
    if(!isset($_SESSION)){
        session_start();
    }

    $sendEmail = $_REQUEST['sendEmail'];

    if(isset($sendEmail) && $sendEmail)
    {
        //recipient
        $sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';
        
        $to = $sessData['email'];
        //sender
        $from = 'sender@example.com';
        $fromName = 'Zantepay';

        //email subject
        $subject = 'Verify Zantepay Account'; 

        //attachment file path
        $file = "codexworld.pdf";
        $domain = $_SERVER['HTTP_HOST'];
        //email body content
        $htmlContent = '<style>a:link, a:visited{background-color: #f44336; color: white; padding: 14px 25px; text-align: center; text-decoration: none; display: inline-block;}a:hover, a:active{background-color: red;}.container{width: 600px;margin: 0 auto;}.center{text-align:center;}p{font-size:20px;}</style><div class="container"><div><h1>Thanks for signing up!</h1><p>Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.</p></div><div><p class="center"><a href="https://'.$domain.'/verify.php">Verify Account</a></p></div><div>';

        //header for sender info
        $headers = "From: $fromName"." <".$from.">";

        //boundary 
        $semi_rand = md5(time()); 
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

        //headers for attachment 
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

        //multipart boundary 
        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
        "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n"; 

        $message .= "--{$mime_boundary}--";
        $returnpath = "-f" . $from;

        //send email
        $mail = @mail($to, $subject, $message, $headers, $returnpath); 

        //email sending status
        // echo $mail?"<h1>Mail Sent</h1>":"<h1>Mail sending failed.</h1>";
        if($mail)
            echo "Email Sent!";
        else
            echo "Sending Email Failed!";
    }
?>