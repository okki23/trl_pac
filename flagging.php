<?php
$transactionId   = $_POST["transactionId"];
$transactionTime = $_POST["transactionTime"];
$transactionStatus  = $_POST["transactionStatus"];
$paymentType = $_POST["paymentType"];
$channelId = $_POST["channelId"];
$orderId = $_POST["orderId"];
$grossAmount = $_POST["grossAmount"];

function postIt($url, $orderId, $transactionStatus, $transactionTime) {
	$ch = curl_init();
	
	$fields = array(
		"order_number" => urlencode($orderId),
		"status" => urlencode($transactionStatus),
		"transaction_time" => urlencode($transactionTime)
	);

	//url-ify the data for the POST
	foreach($fields as $key=>$value) { 
		$fields_string .= $key.'='.$value.'&'; 
	}
	rtrim($fields_string, '&');
	

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

	//execute post
	$result = curl_exec($ch);
	return $result;
	//close connection
	curl_close($ch);
}

$data = array(
	"order_number" => $orderId,
	"status" => $transactionStatus,
	"transaction_time" => $transactionTime
);

$get_data = postIt('http://180.250.96.154/trl-webs/index.php/Android_payment/moveSuccessOrder', $orderId, $transactionStatus, $transactionTime);
?>
