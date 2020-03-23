
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/94efe4cd8f0a476196f38594459cfd57)](https://app.codacy.com/manual/LasVegasCoder/API?utm_source=github.com&utm_medium=referral&utm_content=LasVegasCoder/API&utm_campaign=Badge_Grade_Dashboard)

PrinceAPICaller is a private PHP Class written for the simplicity of calling different API

	Support various methods: 
	POST|GET|, (PUT|DEL|) are optional.

You can also encrypt your data before sending it across the protected or non protected connection.

With this Class, you will not find any better API Caller Class.  
Let me demonstrate the usage: 

	Usage:
			Assuming you want to call Amazon;
			create index.php or include this class where you wanted to use it;
		
Step #1 Downoad this class:

	wget https://github.com/LasVegasCoder/API/blob/master/APICall.php
	Include it in your application, for example create emplty test.php
	<?php
	//Include the class
	include_once("APICall.php");
	
	//Instantial the class for new app with the name $my_app
	$my_app = new PrinceAPICaller();  
	
	//Call the Endpoint API
      	$result = $my_app->_sendRequest('http://amazon.com/whatever/path/api/or/webpage');
	
	//Print results.
      	print_r( $result );
		
	?>

Or use this to pass `data` or `parameters` to another resources.
	
	//Create the params to pass.	
        $data = array( 'username' => 'MyUsername', 'password' => 'Mypassword' );
	
	//Send request to endpoint
        $my_app->_sendRequest('http://amazon.com/whatever/path/api/or/webpage', $data );
			
To use get other method other than 'POST', just pass the METHOD into the Constructor
eg. To Call google with GET method, just pass 'GET' into the constructor like this:
	
	//Instantiate the Class with GET Method
        $my_app = new PrinceAPICaller('GET');
	
	// Send request to the endpoint
        $result = $my_app->_sendRequest('http//google.com');
	
	//Print result
        print_r( $result );
		
Added encrypt options, this way you can encrypt your client data from your app, 
browser etc before sending it to server side process.
		
		For example: User sending a form from your app or webpage, for a strong security;
		
		a.) Form data is encrypted:
		b.) Encrypted Form data is sent to your server over non-encrypted or encryped connection.
		c.) Server received and decrypt the encrypted FormData, then process it.
		
With SSL/Ciphers exploits, I decided to do `private` encryption of `Sensitive DATA` 
if any hacker get any sniffed DATA over network or internet due to vulunerability, 
the stolen data will be useless for them, unless they can get the `key to decrypt` that `stolen DATA` 
(Which is only known and randomly changed, updated on auto from the server).
		
Let me demonstrate how to send encrypt sensitive data and send it wire. 

	For example:
	//Data to encrypt is an array of data:
	
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
	
	//Store EncrytedDATA is a variable. That is what is going to be sent to the server.
	// The Server know the key to decpryt it.
      $EncryptedDATA = EncryptIt( $DataToEncrypt, null, 'myGreatSecreteKey' );
      
      //See the encrypted data as it will appear to attacker, if compromised or sniffed.
      print_r($EncryptedDATA);
		

	Now you can send $EncryptedDATA to Server and use 'myGreatSecreteKey' to Decrypt it 
	from the server before processing.
		
		So on your server:
		//receive incoming data;
		$incomingData = $_REQUEST['form_data'];

		//Decrypt the encrypted data
		$Decrypted = DecryptIt( $incomingData, null, 'myGreatSecreteKey' );

		//Process the decrypted data;
		print_r(  $Decrypted );
		  
This is very useful when you are concerned about network sniffing, hacking, etc.  
Even if attacker get your encrypted data, 
it is completely useless unless he/she know your secret key to the data.
