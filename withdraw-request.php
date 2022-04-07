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

        $user_id=mysqli_real_escape_string($obj,trim($_POST["user_id"]));
        $access_token=mysqli_real_escape_string($obj,$_POST["access_token"]);

		$wallet_type=mysqli_real_escape_string($obj,trim($_POST["wallet_type"]));
        $send_to_number=mysqli_real_escape_string($obj,trim($_POST["send_to_number"]));
        $transaction_type=mysqli_real_escape_string($obj,trim($_POST["transaction_type"]));		
        $amount=mysqli_real_escape_string($obj,trim($_POST["amount"]));
        $status=mysqli_real_escape_string($obj,trim($_POST["status"]));
        $datetime=date('Y-m-d H:i:s');

    
       //$response['status'] ='200';

	    if(validateToken($access_token,$user_id)=='TRUE'){


	    	if(withdraw_request=='1'){


	    	  // check wallet balance
       	    if(checkBalance($user_id,$amount)=='TRUE'){
	       	   

	       	   $user_balance = walletBalance($user_id)-$amount;

			   $insert="INSERT into tbl_wallet (user_id,user_balance, wallet_type,send_to_number,transaction_type,amount, status,datetime) VALUES('$user_id','$user_balance', '$wallet_type','$send_to_number','$transaction_type','$amount','$status', '$datetime')";

				$run_customer=mysqli_query($obj,$insert);

				if($run_customer==true)
				{
				    $response['status'] =200;
					$response['msg'] = "Withdraw Request successfully..!";


					$updated_balance = subBalance($user_id, $amount);


					$response['wallet_balance']=$updated_balance;


				}
				else
				{
                    $response['status'] =201;
					$response['msg']="Request Failled...!";

				}



			}else{
                $response['status'] =201;
				$response['msg']="Insufficient Balance.";
			}	


			}else{

             $response['status'] =201;
	    	 $response['msg']="This is not the correct time for Withdraw request.";
	    }




			}else{

             $response['status'] =201;
	    	 $response['msg']="Access Token Not Valid.";
	    }

		

 	}else{

        $response['status'] =201;
 		$response['msg']="Invalid Parameter.";
 	}

	return $response;
}


// output

function response($response){

	
	
	//echo json_encode(array("status"=>"200","register"=>$response));
	echo json_encode($response);
}


?>