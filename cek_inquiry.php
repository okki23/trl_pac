<?php
$orderId = $_POST["order_number"];

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
   $date = new DateTime();
   $timestamp = $date->getTimestamp();
   //$timestamp = time();
   $encodedPartnerId = base64_encode($partnerId);
   $dataToBeSigned = $partnerId . ":" . $timestamp . ":" . $secretKey;
   $signature = base64_encode(hash_hmac("sha256", $dataToBeSigned, $secretKey, true));
   
   //echo "Authorization: Basic " . $encodedPartnerId  . "\r\n";
   //echo "Timestamp: " . $timestamp . "\r\n";
   //echo "Data to be signed : ". $dataToBeSigned . "\r\n";
   //echo "Signature: " . $signature . "\r\n";
   
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
  
$get_data = callPACPayment('default', 'https://fintech-dev.pactindo.com/api/v1/status/' . $orderId ,'M-00007286', 'PAC-20Y4O8qRgPoB.', '');
//print_r($get_data);

$data = json_decode($get_data, true);

$json = array(
	"transactionId" => $data['transactionId'],
	"transactionTime" => $data['transactionTime'],
	"transactionStatus" => $data['transactionStatus'],
	"paymentType" => $data['paymentType'],
	"orderId" => $data['orderId'],
	"grossAmount" => $data['grossAmount']
);

echo json_encode($json);
?>
