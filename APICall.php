<?php

	/*
		Name:		Simple API Caller 
		Desc:		API Caller Utility allows to make call to any api
		Version:	V1.0
		Date:		3/11/2017
		Author:		Prince Adeyemi
		Contact:	prince@vegasnewspaper.com
		Facebook:	fb.com/YourVegasPrince
		
		Usage:
		Assuming you want to call Amazon;
		create index.php or include this class where you wanted to use it;
		
		$prince = new PrinceAPICaller();  
		$result = $prince->_sendRequest('http://amazon.com/whatever/path/api/or/webpage');
		
		print_r( $result );
		
		Or use this to pass data to another resources.
		
			$data = array( 'username' => 'MyUsername', 'password' => 'Mypassword' );
		
			$prince->_sendRequest('http://amazon.com/whatever/path/api/or/webpage', $data );
			
		To use get method, just pass 'GET' into it.
			$prince = new PrinceAPICaller('GET');
			$result = $prince->_sendRequest('http//google.com');
			
			print_r( $result );
		
		Added encrypt options, this way you can encrypt your data from your app before sending it to server side process.
		For example: User filled a form from your app or webpage, for a strong security,
		we can now encrypt the whole form data, or part thereof before sending it to our server for processing.
		
		Server received the data, and use our known secret to decrypt it, then process it fast.  
		Server side can also encrypt the data and send it down to your app or webpage, of course the app will decrypt the data
		process it and displays it to users.
		
		This is a form of additional security to ensure that data is totally encrypted, sent over tcp either using ssl for additional 
		security and to ensure the integrity of the data passed.
		
		For example:
		$DataToEncrypt = array(
			'username' => $username,
			'password' => $password,
			'email' => $email,
			'payment' => array(
				'paymentid' => $paymentID,
				'creditcard' => $creditcard,
				'expiredate' => $expire,
				'billing' => $billing
			),
			'status' => $status
		);
		
		$EncryptedDATA = EncryptIt( $DataToEncrypt, null, 'myGreatSecreteKey' );
		print_r($EncryptedDATA);
		
		Now you can send $EncryptedDATA to Server and use 'myGreatSecreteKey' to Decrypt it from the server before processing.
		
		So on your server:
		  $incomingData = $_REQUEST['form_data'];
		  
		  $Decrypted = DecryptIt( $incomingData, null, 'myGreatSecreteKey' );
		  print_r(  $Decrypted );
		  
	This is very useful when you are concerned about network sniffing, hacking, etc.  Even if attacker get your encrypted data, 
	it is completely useless unless he/she know your secret key to the data.
	*/

	
	if( !class_exists( 'PrinceAPICaller' ) )
	{
		class PrinceAPICaller
		{
			private $_ch;
			private $_error;
			private $_result;
			private $_cookieFile;
			private $_method;
			
			private $_ENC_METHOD 	= "AES-256-CBC";
			private $_ENC_KEY 	= "MySecretKey12345";
			private $_ENC_IV	= "mySecretemySecre";
			
			//Begins
			
			public function __Construct( $reqType='post' )
			{
				$this->_error = array( 
					'code' => '0', 
					'errorMessage' => '');
					
				$this->_ch = null;
				$this->_result = '';	
				$this->_cookieFile = 'supCookies.txt';
				
				$this->_method  = ( isset( $reqType ) && !empty( $reqType ) ) ? strtolower( $reqType ) : 'post' ;
			}
			
			public function _sendRequest( $endpoint, $data=null )
			{
				// safe check
				$endpoint = ( isset( $endpoint ) && !empty( $endpoint ) ) ? $endpoint : '';
				$postData = ( isset( $data ) && !empty( $data ) ) ? http_build_query( $data ) : null;
				
				
				if( empty( $endpoint ) )
				{
					$this->_error = array(
						'code' => 1 ,
						'errorMessage' => "Uh oh, I don't have telepathy to know your endpoint!");
						
					return $this->_error;
				}
				
				// check if curl is installed
				if( !function_exists( 'curl_init' ) )
				{
					$this->_error = array(
						'code' => 2,
						'errorMessage' => 'Curl not installed, please install curl');
					
					return $this->_error;
				}
				
				$this->_ch = curl_init();
				
				if( $this->_method == 'post' )
				{
					curl_setopt( $this->_ch, CURLOPT_URL, $endpoint );
					curl_setopt( $this->_ch, CURLOPT_COOKIEJAR, $this->_cookieFile);
					curl_setopt( $this->_ch, CURLOPT_FOLLOWLOCATION, true ) ;
					curl_setopt( $this->_ch, CURLOPT_RETURNTRANSFER, true );
					curl_setopt( $this->_ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)" );
					curl_setopt( $this->_ch, CURLOPT_POST, true );
					curl_setopt( $this->_ch, CURLOPT_POSTFIELDS, $postData );
					curl_setopt( $this->_ch, CURLOPT_SSL_VERIFYHOST, FALSE );
					curl_setopt( $this->_ch, CURLOPT_SSL_VERIFYPEER, FALSE );
						
					$this->_result	= curl_exec( $this->_ch );
					
					if( curl_error( $this->_ch ) )
					{
						return curl_error( $this->_ch );
					}
					curl_close( $this->_ch );
					
						return $this->_result;
					
				} else
				
				if( $this->_method == 'get' )
				{
					curl_setopt( $this->_ch, CURLOPT_URL, $endpoint );
					curl_setopt( $this->_ch, CURLOPT_COOKIEJAR, $this->_cookieFile);
					curl_setopt( $this->_ch, CURLOPT_FOLLOWLOCATION, true );
					curl_setopt( $this->_ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt( $this->_ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)");
					curl_setopt( $this->_ch, CURLOPT_SSL_VERIFYHOST, FALSE );
					curl_setopt( $this->_ch, CURLOPT_SSL_VERIFYPEER, FALSE );
						
					$this->_result	= curl_exec( $this->_ch );
					
					if( curl_error( $this->_ch ) )
					{
						return curl_error( $this->_ch );
					}
					curl_close( $this->_ch);
					
					return $this->_result ;
				}
				else {
					$this->_error = array(
						'code' => 3,
						'errorMessage' => 'Invalid request, POST | GET allowed ');
					
					return $this->_error;
				}
				
			}// end of _sendRequest
			
			public function EncryptIt( $DataToEncrypt, $Method = null, $SecretKey=null, $iv = "mySecretemySecre" )
			{
				$Method = ( $Method !== null )  ? $Method : $this->_ENC_METHOD;
				$SecretKey = ( $SecretKey !== null )  ? $SecretKey : $this->_ENC_KEY;
				$iv = ( $iv !== null )  ? $iv : $this->_ENC_IV;
				
				$Encrypted = openssl_encrypt( $DataToEncrypt, $Method, $SecretKey, 0, $iv ); 
				if( $Encrypted )
				{
					return $Encrypted;
				}
			}


			public function DecryptIt( $EncryptedData, $Method = null, $SecretKey=null, $iv = null )
			{
				$Method = ( $Method !== null )  ? $Method : $this->_ENC_METHOD;
				$SecretKey = ( $SecretKey !== null )  ? $SecretKey : $this->_ENC_KEY;
				$iv = ( $iv !== null )  ? $iv : $this->_ENC_IV;
				
				$Decrypted = openssl_decrypt( $EncryptedData, $Method, $SecretKey, 0, $iv );
				
				if( $Decrypted )
				{
					return $Decrypted;
				}
			}		
			
		} // end of class
	} //end of class checking
?>
