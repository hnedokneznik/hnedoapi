<?php

// abych to nemusel psat dvakrat
function errorLogin() {
			
	$loginError = new stdClass();
	$loginError->type = 'message';
	$loginError->message = $GLOBALS['lang']['error_login_login'];
	
	$returnArray = array(
		'error' => array()
	);
	array_push($returnArray['error'], $loginError);
	
	return $returnArray;
}

$app->post('/login', function() use ($app, $helper) {
	
	// vytahnu si email
	$email = $app->request->post('email');
	// vytahnu si password
	$pwd = $app->request->post('pwd');
	
	// kontrola prazdneho pwd
	if(trim($pwd) == '') {
		
		$pwdError = new stdClass();
		$pwdError->type = 'form';
		$pwdError->field = 'pwd';
		$pwdError->message = $GLOBALS['lang']['error_login_empty_pwd_field'];
		
	}
	
	// kontrola prazdneho emailu
	if(trim($email) == '') {
		
		$emailError = new stdClass();
		$emailError->type = 'form';
		$emailError->field = 'email';
		$emailError->message = $GLOBALS['lang']['error_login_empty_email_field'];
		
	} else {
		
		// kontrola spravneho formatu emailu
		if($helper->validateEmail($email) == false) {
			
			$emailError = new stdClass();
			$emailError->type = 'form';
			$emailError->field = 'email';
			$emailError->message = $GLOBALS['lang']['error_login_email_wrong'];
			
		}
	
	}
	
	// kdyz je neco spatne, tak to vyhodim
	if(isset($pwdError) || isset($emailError)) {
			
		$response = array(
			'error' => array()
		);
		
		if(isset($pwdError)) {
			array_push($response['error'], $pwdError);
		}
		
		if(isset($emailError)) {
			array_push($response['error'], $emailError);
		}
		
	} else {
		
		// vytahnu si udaje z DB na zaklade emailu
		$b = RedBean_Facade::findOne( 'user', ' email = ?', array($email));
		
		if($b) {
			
			// mam to, ted overim heslo
			if(validate_password( $pwd , $b->pwd )) {
				
				// vse v poradku
				$message = new stdClass();
				$message->type = 'action';
				$message->email = $b->email;
				$message->api_key = $b->api_key;
				
				$response = array(
					'success' => array($message)
				);
				
			} else {
				
				// blbe heslo
				$response = errorLogin();
				
			}
				
		} else {
			
			// nenasel jsem email v DB
			$response = errorLogin();
			
		}
		
	}
	
	// odeslani jsonu (pan cau)
	$helper->echoResponse(200, $response);
	
});
	