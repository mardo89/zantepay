<?php
//start session
if(!isset($_SESSION)){
    session_start();
}
//load and initialize user class
include 'user.php';
$user = new User();

$submitStr = !empty($_REQUEST['submitStr'])?$_REQUEST['submitStr']:'';
$email = !empty($_REQUEST['email'])?$_REQUEST['email']:'';
$password = !empty($_REQUEST['password'])?$_REQUEST['password']:'';
$cnf_password = !empty($_REQUEST['cnf_password'])?$_REQUEST['cnf_password']:'';

if(empty($submitStr))
{
    exit();
}

if($submitStr == 'signupSubmit'){
	//check whether user details are empty
    if(!empty($email) && !empty($password) && !empty($cnf_password)){
		//password and confirm password comparison
        if( $password !== $cnf_password ){
            $sessData['status']['type'] = 'Error';
            $sessData['status']['msg'] = 'Confirm password must match with the password.'; 
        }else{
			//check whether user exists in the database
            $prevCon['where'] = array('email'=>$email);
            $prevCon['return_type'] = 'count';
            $prevUser = $user->getRows($prevCon);
            if($prevUser > 0){
                $sessData['status']['type'] = 'Error';
                $sessData['status']['msg'] = 'Email already exists, please use another email.';
            }else{
				//insert user data in the database
                $userData = array(
                    'email' => $email,
                    'password' => md5($password)
                );
                $insert = $user->insert($userData);
				//set status based on data insert
                if($insert){
                    $sessData['status']['type'] = 'success';
                    $sessData['email'] = $email;
                    $sessData['status']['msg'] = 'You have registered successfully, log in with your credentials.';
                }else{
                    $sessData['status']['type'] = 'Error';
                    $sessData['status']['msg'] = 'Some problem occurred, please try again.';
                }
            }
        }
    }else{
        $sessData['status']['type'] = 'Error';
        $sessData['status']['msg'] = 'All fields are mandatory, please fill all the fields.'; 
    }
	returnResult($sessData);
}elseif($submitStr == 'loginSubmit'){
	//check whether login details are empty
    if(!empty($email) && !empty($password)){
		//get user data from user class
        $conditions['where'] = array(
            'email' => $email,
            'password' => md5($password),
            'status' => '1'
        );
        $conditions['return_type'] = 'single';
        $userData = $user->getRows($conditions);
		//set user data and status based on login credentials
        if($userData){
            $sessData['userLoggedIn'] = TRUE;
            $sessData['userID'] = $userData['id'];
            $sessData['status']['type'] = 'success';
            $sessData['status']['msg'] = 'Welcome !';
        }else{
            $sessData['status']['type'] = 'Error';
            $sessData['status']['msg'] = 'Wrong email or password, please try again.'; 
        }
    }else{
        $sessData['status']['type'] = 'Error';
        $sessData['status']['msg'] = 'Enter email and password.'; 
    }

	returnResult($sessData);
}elseif($submitStr == 'logoutSubmit'){
	//remove session data
    unset($_SESSION['sessData']);
    session_destroy();
	//store logout status into the ession
    $sessData['status']['type'] = 'success';
    $sessData['status']['msg'] = 'You have logout successfully from your account.';
    returnResult($sessData);
	//redirect to the home page
    // header("Location:index.php");
}else{
	//redirect to the home page
    header("Location:index.php");
}

function returnResult($sessData){
    //store signup status into the session
    $_SESSION['sessData'] = $sessData;
    
    $resultObj = new \stdClass();
    $resultObj->type = $sessData['status']['type'];
    $resultObj->msg = $sessData['status']['msg'];

    $result = "";
    $result = json_encode($resultObj);
    
    echo $result;
    exit();
}