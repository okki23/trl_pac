<?php
	$sign = array("billingId"=>"0009471167681920",
					"nomorHandphone"=>"6285771209526",
					"channelId"=>"TRL");
 
	$encode = json_encode($sign,TRUE);
	$join = $encode.":1543566959";
 
	$bodyparse = str_replace('"','',strtoupper($join));
	$keyserver = "UEvwV+7vVmLYlrdu3mhTRla56AsGP1XxJWMXZpnoh4s="; 
	 
    $curl = curl_init();
   
    $url = 'https://fintech-dev.pactindo.com:6973/api/v1/issuer/inqLoanKS';
    
    $timestamp = '1543566959';
    $authorization = 'Basic TS0wMDAwMDAwMQ==';
    $content_type = 'application/json';
    $signature = base64_encode(hash_hmac("sha256", $bodyparse, $keyserver, true));
	 
	//CURL FUNCTION
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'TimeStamp : '. $timestamp,
      'Authorization : '.$authorization,
      'Signature : ' . $signature,
      'Content-Type : '.$content_type
    ));

    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($sign));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){
		die("Connection Failure");
	}
    curl_close($curl);
	echo $result;
	