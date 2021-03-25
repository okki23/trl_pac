<?php
//header("HTTP/1.0 404 Not Found");
include "decode.php";

//set_time_limit(5);

$billingId = $_POST['billingId'];
$nomorHandphone = $_POST['nomorHandphone'];
$channelId = "TRL";

//parameter yang dimasukkan ini, billing id berdasarkan dari call pac payment
$data = array("billingId"=>$billingId,
					"nomorHandphone"=>$nomorHandphone,
					"channelId"=>$channelId);
$jsonData =  json_encode($data);

function callPACPayment($method, $url, $jsonData){
   $curl = curl_init();

   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($jsonData)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($jsonData)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);			 					
         break;
      default:
         if ($jsonData)
            $url = sprintf("%s?json=%s", $url, http_build_query($jsonData));
   }
	 
	//dapetin timestamp
    $timestamp = time();
	//ini authorization nya
    //$authorization = 'Basic TS0wMDAwMDAwMQ==';
	$authorization = 'Basic VFJM';
	//ini key server yang dari mas iqbal
	$keyserver = "UEvwV+7vVmLYlrdu3mhTRla56AsGP1XxJWMXZpnoh4s=";  
	//json nya digabung sama timestamp
	$join = $jsonData.":".$timestamp;
	//bodyparse yang di uppercase sama dihilangkan petiknya sesuai arahan apiary
	$bodyparse = str_replace('"','',strtoupper($join));
	
	//proses encrypt signature, ini sudah sama persis dari apiary dan mas iqbal
    $signature = base64_encode(hash_hmac("sha256", $bodyparse, $keyserver, true));
	  
	curl_setopt($curl, CURLOPT_URL, $url);
	//disinilah header ditempatkan, jadi tidak perlu postman buat naro params
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Timestamp: '. $timestamp,
      'Authorization: ' . $authorization,
      'Signature: ' . $signature
	));
//curl_setopt($curl, CURLOPT_NOSIGNAL, 1);
//curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20000);
//curl_setopt($curl, CURLOPT_TIMEOUT, 60);
//curl_setopt($curl, CURLOPT_TIMEOUT_MS, 60000);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

   // EXECUTE:
   //$result = "";
   //eksekusi nya disini
   $result = curl_exec($curl);
  
   $httCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
   curl_close($curl);
	
	if($httCode == 404){
		$data = array(
			"responseCode" => 50,
			"responseDescription" =>  "request timeout"
		);
		echo json_encode($data);
	}
	else
	{
		parse_to_mobile($result);
	}	
}
  

// hasilnya akan nampil dari skrip dibawah ini, urlnya pun sudah benar 

$get_data = callPACPayment('POST', 'https://fintech-dev.pactindo.com:6973/api/v1/issuer/inqLoanKS', $jsonData);
//$get_data = callPACPayment('POST', 'http://slowwly.robertomurray.co.uk/delay/10000/url/http://www.google.co.uk', $jsonData);

//echo $get_data;
?>