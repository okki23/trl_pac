<?php

function callPACPayment($method, $url, $jsonData, $test, $timestamp){
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

   //$timestamp = '1543566959';
   //$authorization = 'Basic TS0wMDAwMDAwMQ==';
	$authorization = 'Basic VFJM';
   //$signature = 'E6OGLmltIXgjukc/V+HPn0yBg6qFMZR4u1Mrlwa3lSQ=';
   
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Timestamp: '. $timestamp,
      'Authorization: ' . $authorization,
      //'Signature: ' . $signature,
	  'Signature: ' . $test,
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}

$bodyparses = array("billingId"=>"0009700299113168",
					"nomorHandphone"=>"6285710035919",
					"channelId"=>"TRL");

$body = json_encode($bodyparses);

   $encode = json_encode($bodyparses, JSON_UNESCAPED_SLASHES);
   //$timestamp = 1543566959;
   $date = new DateTime();
   $timestamp = $date->getTimestamp();
	$join = $encode.":".$timestamp;
	
	$bodyparse = str_replace('"','',strtoupper($join));
	$keyserver = "UEvwV+7vVmLYlrdu3mhTRla56AsGP1XxJWMXZpnoh4s="; 
	
	$test = base64_encode(hash_hmac("sha256", $bodyparse, $keyserver, true));

// Contoh function call       

$get_data = callPACPayment('POST', 'https://fintech-dev.pactindo.com:6973/api/v1/issuer/inqLoanKS', $body, $test, $timestamp);

echo $get_data;
?>