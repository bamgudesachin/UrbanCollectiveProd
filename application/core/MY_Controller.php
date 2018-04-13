<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $data = array();
    
    function __construct ()
    {
        parent::__construct();
       // date_default_timezone_set('Asia/Kolkata');
        if($this->session->userdata('logged_in')!=TRUE)
        {redirect('login/index');}
    	
    }
	
	/*send notification for multiple users*/
   /*send notification for multiple users*/
    public function send_notification_ios($deviceToken, $payload)
    {
        $passphrase = '123456'; // change this to your passphrase(password)

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert','Production_Final.pem');
        
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        //stream_context_set_option($ctx, 'ssl', 'cafile', 'entrust_2048_ca.cer');

        // Open a connection to the APNS server
        // for 
		
        /*$fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx); 
		*/

        $fp = stream_socket_client(
                'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
		
         if (!$fp){
			return false;
			//exit("Failed to connect: $err $errstr" . PHP_EOL);
		}
            
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
		//$result = fwrite($fp, $msg, strlen($msg));
		try {                           
			$result = fwrite($fp, $msg, strlen($msg));
			//socket_close($fp);
			fclose($fp);			
			sleep(2);
		}
		catch (Exception $ex) {
			//socket_close($fp);
			fclose($fp);			
			sleep(2);
		}
		
    }
	
	public function send_multiple_user_notification_ios($deviceToken, $payload)
    {
		foreach($deviceToken as $token)
        {
			$this->send_notification_ios($token,$payload);			
        }
	}
	
}?>