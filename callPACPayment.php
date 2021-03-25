<?php
$postPayment = $_POST["paymentType"];
$postAmount  = $_POST["grossAmount"];
$postOrderId = $_POST["orderId"];
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
   $date = new DateTime();
   $timestamp = $date->getTimestamp();
   //$timestamp = time();
   $encodedPartnerId = base64_encode($partnerId);
   $dataToBeSigned = $partnerId . ":" . $timestamp . ":" . $secretKey;
   $signature = base64_encode(hash_hmac("sha256", $dataToBeSigned, $secretKey, true));
   
   /*echo "Authorization: Basic " . $encodedPartnerId  . "\r\n";
   echo "Timestamp: " . $timestamp . "\r\n";
   echo "Data to be signed : ". $dataToBeSigned . "\r\n";
   echo "Signature: " . $signature . "\r\n";*/
   
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
		return $result;
	}
   //return $result;
}


//$phone = '087889677228';
//$email = 'okkisetyawan@gmail.com';
//$name = 'Okki Setyawan dan Soleh dan Mamad';
//var body here...

$timestamp = $date->getTimestamp();
//$timestamp = time();
//$paymentType = 'bankTransfer';
$customerDetail = array("phone"=>"","email"=>"","name"=>"");
//$grossAmount = "60000";
//$orderId = "OLAADMIN1245456401";
$channelUserId = "";
$channelID= "";

$paymentType = $postPayment;
$grossAmount = $postAmount;
$orderId = $postOrderId;


$bodyparses = array("timestamp"=>$timestamp,"paymentType"=>$paymentType,"customerDetail"=>$customerDetail,"grossAmount"=>$grossAmount,"orderId"=>$orderId,"channelUserId"=>$channelUserId,"channelID"=>$channelID);

$bodyparse = json_encode($bodyparses);

// Contoh function call       

$get_data = callPACPayment('POST', 'https://fintech-dev.pactindo.com/api/v1/charge','M-00007286', 'PAC-20Y4O8qRgPoB.', $bodyparse);
//echo $get_data;

$data = json_decode($get_data, true);

//print_r($data);

if ($data['transactionId'] != null) {
   $json = array(
	"transactionId" => $data['transactionId'],
	"transactionStatus" => $data['transactionStatus'],
	"paymentType" => $data['paymentType'],
	"orderId" => $data['orderId'],
	"grossAmount" => $data['grossAmount'],
	"billidId" => $data['billingId'],
	"virtualAccount" => $data['virtualAccount'],
	"responseCode" => "00",
	"responseDescription" =>  "Success"
	);

   echo json_encode($json);
}
?>
