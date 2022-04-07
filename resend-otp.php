<?php
include('../include/config.php');

$request_method = $_SERVER['REQUEST_METHOD'];
$response=array();



switch($request_method)
{
	
	case "POST":
		response(doPost());
	break;
			
}



function doPost()
{
	global $obj;

	if($_POST){

		
        $mobile=mysqli_real_escape_string($obj,$_POST["mobile"]);
		$otp = mt_rand(100000, 999999);

        
        // check user details
		$data_check= "SELECT * from tbl_users where mobile='".$mobile."'";
        
		$query=mysqli_query($obj,$data_check);
		$data=mysqli_fetch_assoc($query);

		if(($data)==true) {

			$sql1="UPDATE tbl_users set otp='$otp' where id=".$data['id']."";
			$query1=mysqli_query($obj,$sql1);

			if($query1==true)
			{
                    $response['status'] =200;


					$message= "Your one-time verification code for Play Online Faridabad application is ".$otp;

				   $sms_response = send_sms($message, $mobile);

                   if($sms_response['success'] == true){

                   	 $response['msg'] = "OTP Resend Successfully Please Verify OTP...!";

                   }else{

                   		$response['msg'] = "Sorry...!! OTP Not Sent. ";
                   }
 					
			    //	$response['resend']=$response;



			}
			else
			{   
                $response['status'] =201;  
				$response['msg']="Sorry Please try again later...!";

			} 

		}

		else{
			
			$response['status'] =201;	
 			$response['msg']="oops..wrong mobile Number...!";
 		}
	

		

 	}else{
 		$response['status'] =201;
 		$response['msg']="Invalid Parameter.";
 	}

	return $response;
}


// output

function response($response){
	
	//echo json_encode(array("status"=>"200","data"=>$response));
	echo json_encode($response);
}


?>