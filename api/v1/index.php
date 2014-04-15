<?php
/*
 * API
 */
require '../../vendor/autoload.php';

$app = new \Slim\Slim();

include 'helper.php';
$helper = New AppHelper();
$language = $helper->processLanguage();

$GLOBALS['user_id'] = NULL;


include '../include/config.php';
include '../lang/lang.'.$language.'.php';

include '../include/database.php';
include '../include/passwordHash.php';


$mail = new PHPMailer();




include 'app/register.php';
include 'app/login.php';

$app->get('/foo', $helper->autthh($app,$helper), function() use ($helper) {
	
	
	$response['foo'] = 'bar';
	$response['uuid'] = $GLOBALS['user_id'];
	$helper->echoResponse(200, $response);
	
});

/*
 * Return API version
 */
$app->get('/version', function() use ($helper,$mail) {
	

//Set who the message is to be sent from
$mail->setFrom('from@example.com', 'First Last');
//Set an alternative reply-to address
$mail->addReplyTo('replyto@example.com', 'First Last');
//Set who the message is to be sent to
$mail->addAddress('whoto@example.com', 'John Doe');
//Set the subject line
$mail->Subject = 'PHPMailer mail() test';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(file_get_contents('../emails/test.html'), dirname(__FILE__));
$mail->AddEmbeddedImage('../emails/m_twitter.png', 'twitterimg', 'm_twitter.png');
//Replace the plain text body with one created manually
$mail->AltBody = 'Ahoj, nemáš html socko :)';
 
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
	
	$response['error'] = false;
	$response['version'] = '1a';
	//$helper->echoResponse(200, $response);
	
});



// run Slim app
$app->run();

// close DB connection
RedBean_Facade::close();