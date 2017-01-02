<head>
<script src="js/jquery-1.12.4.min.js"></script>
<script src="js/script.js"></script>
</head>

<body>
<?php
	
	echo 'Senaste drifttest utfördes den '.date('Y-m-d H:i');
	echo'<hr>';
	
	$url_list = array(
		'www.rabadang.se',
		'www.lennyekberg.se',
		'www.jsb.se',
// 		'www.whuddahuddawuuuh.se',
		'http://www.u8921421.fsdata.se/jsb_webb/'
	);
	
	array_walk($url_list, function(&$value, &$key) {
	    $value = str_replace('dog', 'bobb', $value);
    	?>URL: <?=$value?><br><?php


	
	/*
		echo '<pre>';
		print_r($url);
		echo '</pre>';
	*/

		// CHECK URL
		$url= $value;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, true);   
		curl_setopt($ch, CURLOPT_NOBODY, true);    
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.4");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$output = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
	
		echo 'HTTP response: '.$httpcode;


		// SEND MAIL (OR NOT)
		if($httpcode != 200){
			if($httpcode == 0){
				$status = 'svarade inte (HTTP statuskod '.$httpcode.')';
			} else{
				$status = 'svarade med <a href="https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#'.$httpcode.'">HTTP statuskod '.$httpcode.'</a>';
			}
			
			$to      = 'lennyekberg@gmail.com';
			$subject = 'Felkod '.$httpcode.' på '.$url;
			$message = $url.' '.$status.' den '.date('Y-m-d H:i').'';
			
			
			//$header = "From: info@lennyekberg.se\r\n"; 
			$header.= "From: Rabadang driftnotifikation <info@lennyekberg.se> \r\n";
			$header.= "Reply-To: no-reply@rabadang.se \r\n";
			$header.= "MIME-Version: 1.0\r\n"; 
			$header.= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
// 			$header.= "X-Priority: 1\r\n"; 
	        $headers .= "X-Priority: 1 (Highest)\n";
	        $headers .= "X-MSMail-Priority: High\n";
	        $headers .= "Importance: High\n";
        
			$status = mail($to, $subject, $message, $header);
			
			if($status)
			{ 
			    echo '<p>Your mail has been sent!</p>';
			} else { 
			    echo '<p>Sum ting wong, Please try again!</p>'; 
			}
		} else{
			// DO NOTHING
		}
	
	
		echo '<hr>';

	});

?>


<body>
