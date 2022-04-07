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

	if($_POST['type'] == 'withdraw-request-list'){

		
        $user_id=$_POST['user_id'];
		$access_token=mysqli_real_escape_string($obj,$_POST["access_token"]);

         //$response['status'] ='200';
        

        // validate access token 
       if(validateToken($access_token,$user_id)=='TRUE'){
            
            $response['status'] =200;
         	// Fetch Type blog

				if($_POST['user_id']){

					$user_id = $_POST['user_id'];

					$where =" WHERE user_id='".$user_id."' and transaction_type='debit'";
				}

				else{

					$where =" WHERE transaction_type='debit'";
				}


		        // Fetch All Blog details
				$select= "SELECT * from tbl_wallet".$where." order by id desc";

				$query=mysqli_query($obj,$select);
				while($data=mysqli_fetch_assoc($query)){
					
					$result[] = array(

									"user_id" => $data['user_id'],
									"wallet_type" => $data['wallet_type'],
									"amount" => $data['amount'],
									"transaction_id" => $data['transaction_id'],
									"transaction_type" => $data['transaction_type'],
									"status" => $data['status'],
									"datetime" =>date('Y-m-d H:i:sa', strtotime($data['datetime']))
									
								);
				}

				$response['withdraw-request-list'] = $result;


			

         
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
	
	//echo json_encode(array("status"=>"200","data"=>$response));
	echo json_encode($response);
}


?>