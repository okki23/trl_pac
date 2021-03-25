<?php

$paymentType = $_POST['paymentType']; 
$grossAmount = $_POST['grossAmount'];
$orderId = $_POST['orderId'];
$token = $_POST[token'];

/*
echo $paymentType;
echo "<br>";
echo $grossAmount;
echo "<br>";
echo $orderID;
echo "<br>";

exit();

*/
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
   $timestamp = time();
   $encodedPartnerId = base64_encode($partnerId);
   $dataToBeSigned = $partnerId . ":" . $timestamp . ":" . $secretKey;
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


$phone = '087889677228';
$email = 'okkisetyawan@gmail.com';
$name = 'Okki Setyawan dan Soleh dan Mamad';
//var body here...

//$timestamp = 1545893829;
//$timestamp = time();
//$paymentType = 'bankTransfer';
$customerDetail = array("phone"=>$phone,"email"=>$email,"name"=>$name);
//$grossAmount = "60000";
//$orderId = "211218OLA";
$channelUserId = "TRL";
$channelID= "BNI";


$bodyparses = array("timestamp"=>$timestamp,"paymentType"=>$paymentType,"customerDetail"=>$customerDetail,"grossAmount"=>$grossAmount,"orderId"=>$orderId,"channelUserId"=>$channelUserId,"channelID"=>$channelID, "token"=>$token);

$bodyparse = json_encode($bodyparses);

// Contoh function call       

$get_data = callPACPayment('POST', 'https://fintech-dev.pactindo.com/api/v1/charge','M-00007286', 'PAC-20Y4O8qRgPoB.', $bodyparse);
echo $get_data;

?>
