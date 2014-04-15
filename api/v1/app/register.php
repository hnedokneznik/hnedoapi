<?php

$app->post('/register', function() use ($app, $helper, $mail) {
	
	// vytahnu si email
	$email = $app->request->post('email');
	// vytahnu si password
	$pwd = $app->request->post('pwd');
	
	// kontrola prazdneho pwd
	if(trim($pwd) == '') {
		
		$pwdError = new stdClass();
		$pwdError->type = 'form';
		$pwdError->field = 'pwd';
		$pwdError->message = $GLOBALS['lang']['error_empty_pwd_field'];
		
	} else {
		
		// kontrola delky hesla
		if($helper->validatePwd($pwd) == false) {
			
			$pwdError = new stdClass();
			$pwdError->type = 'form';
			$pwdError->field = 'pwd';
			$pwdError->message = $GLOBALS['lang']['error_short_password'];
			
		} else {
			
			$pwdHash = create_hash($pwd);
			
		}

	}
	
	// kontrola prazdneho emailu
	if(trim($email) == '') {
		
		$emailError = new stdClass();
		$emailError->type = 'form';
		$emailError->field = 'email';
		$emailError->message = $GLOBALS['lang']['error_empty_email_field'];
		
	} else {
		
		// kontrola spravneho formatu emailu
		if($helper->validateEmail($email) == false) {
			
			$emailError = new stdClass();
			$emailError->type = 'form';
			$emailError->field = 'email';
			$emailError->message = $GLOBALS['lang']['error_wrong_email_format'];
			
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
		
		// string pro potvrzeni registrace
		$randomString = substr(str_shuffle("23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 8);
		
		// tady je vsechno v poradku a muzu poslat mail
		$mail->CharSet = 'UTF-8';
		$mail->setFrom($GLOBALS['lang']['email_from_email'], $GLOBALS['lang']['email_from_name']);
		$mail->addAddress($email);
		$mail->Subject = $GLOBALS['lang']['email_registration_subject'];
		
		$htmlBody = $helper->htmlEmailHeader($GLOBALS['lang']['email_registration_subject']);
		$htmlBody .= "{$GLOBALS['lang']['email_registration_text_1']}<br>{$GLOBALS['lang']['email_registration_text_2']} {$GLOBALS['config']['URL']}/reg/{$randomString}<br>{$GLOBALS['lang']['email_registration_text_3']}";
		$htmlBody .= $helper->htmlEmailFooter($GLOBALS['lang']['email_registration_footer']);
		
		$plainBody = $GLOBALS['lang']['email_registration_text_1'];
		
		$mail->Body = $htmlBody;
		//$mail->AddEmbeddedImage('../emails/m_twitter.png', 'twitterimg', 'm_twitter.png');
		
		$mail->AltBody = $plainBody;
 
		
			
		// mrknu se jestli neexistuje user s timhle emailem
		$count = count(RedBean_Facade::find('user', 'email LIKE :email', array(':email' => $email)));
		
		if($count === 0) {
			
			// pokusim se odeslat mail
			if (!$mail->send()) {
				
				// nepovedlo se odeslat mail
			    $response = "Mailer Error: " . $mail->ErrorInfo;
				
				$mailerError = new stdClass();
				$mailerError->type = 'message';
				$mailerError->message = $GLOBALS['lang']['error_mailer_send'];
				
				$response = array(
					'error' => array($mailerError)
				);
				
			} else {
				
				// email odeslan, ted ulozim do DB
				$u = RedBean_Facade::dispense( 'user' );
				$u->email = $email;
				$u->pwd = $pwdHash;
				$u->api_key = $helper->generateApiKey();
				$u->regstring = $randomString;
				
				$id = RedBean_Facade::store($u);
				
				$message = new stdClass();
				$message->type = 'message';
				$message->message = $GLOBALS['lang']['registation_success'];
				
				$response = array(
					'success' => array($message)
				);
				
			}
			
		
		} else {
			
			// user s timhle emailem uz existuje
			$duplicateError = new stdClass();
			$duplicateError->type = 'message';
			$duplicateError->message = $GLOBALS['lang']['error_user_already_exist'];
			
			$response = array(
				'error' => array($duplicateError)
			);
			
		}
			
		
	}
	
	// odeslani jsonu (pan cau)
	$helper->echoResponse(200, $response);
	
});