<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://sms.truevaluemobi.com/api_v2/message/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "sender_id=pofpof&message=YOUR_MESSAGE&mobile_no=8285484231%2C9625814751",
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer -a0KN0pr_LoCE6STBo9nc3M9mHph3k02hCBEd5-0IkEVdtv0iHsE5FFqC_9J0iiF",
    "cache-control: no-cache",
    "content-type: application/x-www-form-urlencoded"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}


//http://manage.staticking.net/index.php/smsapi/httpapi/?uname=68329626&password=29626&sender=DGSKTI&receiver=9625814751&route=PA&msgtype=1&sms=test%20message

?>