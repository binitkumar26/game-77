<?php

// require_once('genericLib.php');

function get_curl($url){
//$url="http://tnwebservices-test.ticketnetwork.com/tnwebservice/v3.2/tnwebservicestringinputs.asmx/GetEventPerformers?websiteConfigID=23730";
// $ch=curl_init();
// curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
// curl_setopt($ch,CURLOPT_URL,$url);
// curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);

// echo curl_exec($ch);
// curl_close($ch);



//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$xml_string=curl_exec($ch);
// Closing
curl_close($ch);

//$result= simplexml_load_string($xml_string);
//return $result;
}


function get_curl1($url1){

//  Initiate curl
$ch1 = curl_init();
// Disable SSL verification
curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch1, CURLOPT_URL,$url1);
// Execute
$xml_string1=curl_exec($ch1);
// Closing
curl_close($ch1);

//$result1= simplexml_load_string($xml_string1);
//return $result1;
}


?>