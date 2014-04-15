<?php
class AppHelper {
	
	// formatuje vystup na json, odesila stavovy kod
	public function echoResponse($status_code=200, $response) {
		$app = \Slim\Slim::getInstance();
		
		// nastavuje HTTP kod
		$app->status($status_code);
		
		// nastavuje content type na json
		$app->contentType('application/json;charset=utf-8');
		
		// odesle json s datama
		echo json_encode($response);
	}
	
	// validace formatu emailu pomoci vnitrni php funkce
	public function validateEmail($email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
	}
	
	// validace minimalni delky hesla
	public function validatePwd($pwd) {
		if(strlen(trim($pwd)) >= 6 ) {
			return true;
		}
	}
	
	// vybere jazyk aplikace
	public function processLanguage() {
		$app = \Slim\Slim::getInstance();
		
		$lang = $app->request->headers->get('API-Language');
		
		$possibleLanguages = array("cs");
		
		if(!in_array($lang,$possibleLanguages)) {
			$lang = 'cs';
		}
		
		return $lang;
	}
	
	// vykresluje zacatek emailu
	public function htmlEmailHeader($title) {
		
		return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns=\"http://www.w3.org/1999/xhtml\" style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\">
		<head>
			<meta name=\"viewport\" content=\"width=device-width\" />
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
			<title>{$title}</title>
		</head>
		<body bgcolor=\"#f6f6f6\" 
			style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; margin: 0; padding: 0;\">&#13;
		<table style=\"width: 100%; margin: 0; padding: 20px;\">
			<tr style=\"margin: 0; padding: 0;\">
		  		<td style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\"></td>&#13;
				<td bgcolor=\"#FFFFFF\" style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 20px; border: 1px solid #f0f0f0;\">
					&#13;
					&#13;
					&#13;
					<div style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; max-width: 600px; display: block; margin: 0 auto; padding: 0;\">&#13;
					<table style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 100%; margin: 0; padding: 0;\">
						<tr style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\">
							<td style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\">&#13;";
				
	}

	// vykresluje konec emailu
	public function htmlEmailFooter($text) {
		
		return "	</td>&#13;
						</tr>
					</table>
					</div>&#13;
					&#13;
					&#13;
				</td>&#13;
			</tr>
		</table>
		<table style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 100%; clear: both !important; margin: 0; padding: 0;\">
			<tr style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\">
				<td style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\"></td>&#13;
				<td style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;\">&#13;
					&#13;
					&#13;
					<div style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; max-width: 600px; display: block; margin: 0 auto; padding: 0;\">&#13;
						<table style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 100%; margin: 0; padding: 0;\">
							<tr style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\">
								<td align=\"center\" style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\">&#13;
									<p style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 12px; line-height: 1.6; color: #666; font-weight: normal; margin: 0 0 10px; padding: 0;\">
										{$text}
									</p>&#13;
								</td>&#13;
							</tr>
						</table>
					</div>&#13;
					&#13;
					&#13;
				</td>&#13;
				<td style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\"></td>&#13;
			</tr>
		</table>
		</body>
		</html>";
		
	}
	
	// generuje API klic
	public function generateApiKey() {
		return md5(uniqid(rand(), true));
	}
	
	// overeni API klice a predani id usera, kteremu patri
	private function isValidApiKey($key) {
		
		$b = RedBean_Facade::findOne( 'user', ' api_key = ?', array($key));
		
		if($b) {
			return $b->id;
		} else {
			return false;
		}
		
	}
	
	// middleware autentifikacni funkce
	public function autthh($app,$helper) {
		
		return function() use ($app,$helper) {
			
			$headers = apache_request_headers();
			$response = array();
			
			// overim si jestli je v hlavicce API-Key
			if (isset($headers['API-Key'])) {
				
				// namrdnu si ho do promeny
				$apiKey = $headers['API-Key'];
				
				if(!$userId = $this->isValidApiKey($apiKey)) {
					
					// klic je na hovno
					$loginError = new stdClass();
					$loginError->type = 'autentification';
					$loginError->message = $GLOBALS['lang']['error_autentification'];
					
					$returnArray = array(
						'error' => array()
					);
					array_push($returnArray['error'], $loginError);
					
					$helper->echoResponse(401, $returnArray);
					
					$app->stop();
					
				} else {
					
					// platny klic
					
					$GLOBALS['user_id'] = $userId;
						
				}
			
			} else {
				
				// klic neni v hlavicce, doporucime userovi se lognout
				$loginError = new stdClass();
				$loginError->type = 'autentification';
				$loginError->message = $GLOBALS['lang']['error_autentification'];
				
				$returnArray = array(
					'error' => array()
				);
				array_push($returnArray['error'], $loginError);
				$helper->echoResponse(401, $returnArray);
				$app->stop();
			
			}
			
		};
		
	}

	
	
}
