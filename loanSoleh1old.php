<?php

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

   // OPTIONS:
   //$date = new DateTime();
   //$timestamp = $date->getTimestamp();
   //$timestamp = time();
   //$encodedPartnerId = base64_encode($partnerId);
   //$dataToBeSigned = $partnerId . ":" . $timestamp . ":" . $secretKey;
   //$signature = base64_encode(hash_hmac("sha256", $dataToBeSigned, $secretKey, true));
   
   $timestamp = '1543566959';
   $authorization = 'Basic TS0wMDAwMDAwMQ==';
   $signature = 'E6OGLmltIXgjukc/V+HPn0yBg6qFMZR4u1Mrlwa3lSQ=';
   
   //echo "Authorization: Basic " . $encodedPartnerId  . "\r\n";
   //echo "Timestamp: " . $timestamp . "\r\n";
   //echo "Data to be signed : ". $dataToBeSigned . "\r\n";
   //echo "Signature: " . $signature . "\r\n";
   
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Timestamp: '. $timestamp,
      'Authorization: ' . $authorization,
      'Signature: ' . $signature,
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}

$bodyparses = array("billingId"=>"0009471167681920",
					"nomorHandphone"=>"6285771209526",
					"channelId"=>"TRL");

$bodyparse = json_encode($bodyparses);

// Contoh function call       

$get_data = callPACPayment('POST', 'https://fintech-dev.pactindo.com:6973/api/v1/issuer/inqLoanKS', $bodyparse);

echo $get_data;
?>