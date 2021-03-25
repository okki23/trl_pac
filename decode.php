<?php

header('Content-Type: application/json');
function parse_to_mobile($data){
	
$parse = json_decode($data);
$parse2 = json_decode($parse->listResponse);
 
foreach($parse2 as $key => $vals){
	$sub_array = array();  
	$sub_array[] = $vals->billingId;
	$sub_array[] = $vals->grossAmount;
	$sub_array[] = $vals->itemDetails;
	$sub_array[] = $vals->transactionTime;
	$sub_array[] = $vals->merchantName;
} 
$arr_response = array("Plafond"=>$parse->Plafond,
			  "Balance"=>$parse->Balance,
			  "responseCode" => $parse->responseCode,
			  "responseDescription" => $parse->responseDescription,
			  "billingId"=>$sub_array[0],
			  "grossAmount"=>$sub_array[1],
			  "itemDetails"=>$sub_array[2],
			  "transactionTime"=>$sub_array[3],
			  "merchantName"=>$sub_array[4]
			  );
echo json_encode($arr_response,TRUE);	
}
 		  
?>