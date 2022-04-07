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
        $transaction_id=mysqli_real_escape_string($obj,trim($_POST["transaction_id"]));
        $transaction_type=mysqli_real_escape_string($obj,trim($_POST["transaction_type"]));		
        $amount=mysqli_real_escape_string($obj,trim($_POST["amount"]));
        $status=mysqli_real_escape_string($obj,trim($_POST["status"]));
         $datetime=date('Y-m-d H:i:s');

    
      // $response['status'] ='200';

	    if(validateToken($access_token,$user_id)=='TRUE'){

	    	if(money_add_request==1){
	       	 
               $user_balance = walletBalance($user_id)+$amount;

			   $insert="INSERT into tbl_wallet (user_id,user_balance, wallet_type,transaction_id, transaction_type,amount, status,datetime) VALUES('$user_id', '$user_balance', '$wallet_type','$transaction_id','$transaction_type','$amount','$status', '$datetime')";

				$run_customer=mysqli_query($obj,$insert);

				if($run_customer==true)
				{
					$response['status'] =200;	
					$response['msg'] = "Add Money successfully..!";

					$updated_balance = addBalance($user_id, $amount);


					$sql2="SELECT * from tbl_wallet where transaction_id='".$transaction_id."' && user_id='$user_id'";
					$query2=mysqli_query($obj,$sql2);
					$data2=mysqli_fetch_assoc($query2);
						
					if($data2==true)
					{

						$result['user_id']=$data2['user_id'];

						$result['wallet_type']=$data2['wallet_type'];

						$result['transaction_id']=$data2['transaction_id'];

						$result['transaction_type']=$data2['transaction_type'];	
						
						$result['amount']=$data2['amount']; 

						$result['status']=$data2['status']; 

						$result['wallet_balance'] =$updated_balance;



			    	}

			    	$response['add_money']=$result;




				}
				else
				{
					$response['status'] =201;
					$response['msg']="Transaction Failled...!";

				}

				}else{

		             $response['status'] =201;
			    	 $response['msg']="This is not the correct time for Money Add request.";
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