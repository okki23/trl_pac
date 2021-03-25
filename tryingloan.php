<?php
$postBillingId = $_POST["billingId"];
$postNomorHp   = $_POST["nomorHandphone"];
$postChannelId = $_POST["channelId"];
$date = new DateTime();

function callPACPayment($method, $url, $partnerId, $secretKey, $jsonData){
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
   $arr = array("billingId"=>"0009288300532676","nomorHandphone"=>"085710035919","channelId"=>"TRL");
   
   $date = new DateTime();
   $timestamp = $date->getTimestamp();
    
   $encodedPartnerId = base64_encode($partnerId);
    
   $dataToBeSigned = strtoupper(str_replace('"','',json_encode($arr)).':'.$timestamp)
  
   $signature = base64_encode(hash_hmac("sha256", $dataToBeSigned, $secretKey, true));
   
   echo "Authorization: Basic " . $encodedPartnerId  . "\r\n";
   echo "Timestamp: " . $timestamp . "\r\n";
   echo "Data to be signed : ". $dataToBeSigned . "\r\n";
   echo "Signature: " . $signature . "\r\n";
   
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Timestamp: '. $timestamp,
      'Authorization: Basic ' . $encodedPartnerId,
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

 
$timestamp = $date->getTimestamp();
 
$bodyparses = array("billingId"=>"0009288300532676","nomorHandphone"=>"085710035919","channelId"=>"TRL");

//$bodyparse = json_encode($bodyparses);

echo json_encode($bodyparses);

/* $get_data = callPACPayment('POST', 'https://fintech-dev.pactindo.com:6973/api/v1/issuer/inqLoanKS',
   'M-00007286', ' PAC-vKtdhkaDLk128', $bodyparse);
echo $get_data;*/
?>
