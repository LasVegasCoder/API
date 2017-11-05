		Usage:
		Assuming you want to call Amazon;
		create index.php or include this class where you wanted to use it;
		
      $prince = new PrinceAPICaller();  
      $result = $prince->_sendRequest('http://amazon.com/whatever/path/api/or/webpage');

      print_r( $result );
		
		Or use this to pass `data` or `parameters` to another resources.
		
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
