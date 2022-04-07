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
		$otp=mysqli_real_escape_string($obj,$_POST["otp"]);

        
        // check user details
		$data_check= "SELECT * from tbl_users where mobile='".$mobile."' && otp='".$otp."'";
        $access_token=getToken();	   //generate  access token

		$query=mysqli_query($obj,$data_check);
		$data=mysqli_fetch_assoc($query);

		if(($data)==true) {

			$sql1="UPDATE tbl_users set access_token='$access_token',status=1,otp_verify=1, last_login=NOW() where id=".$data['id']."";
			$query1=mysqli_query($obj,$sql1);

			if($query1==true)
			{
               		$response['status'] =200;
					$response['msg'] = "You are successfull Sign Up with us...!";
 					

					$sql2="SELECT * from tbl_users where mobile='".$mobile."' && otp='".$otp."'";
					$query2=mysqli_query($obj,$sql2);
					$data2=mysqli_fetch_assoc($query2);
						
					if($data2['status']=='1' and $data2['type']=='user')
					{

						$result['id']=$data2['id'];

						$result['name']=$data2['name'];

						$result['unique_id']=$data2['unique_id'];

						$result['mobile']=$data2['mobile'];	
						
						$result['access_token']=$data2['access_token']; 


						// insert wallet amount 

						 $current_balance =0.00;
						 $user_id =$result['id'];

						 $insert1="INSERT into tbl_wallet_load(current_balance,user_id) VALUES('$current_balance','$user_id')";
						 $run_wallet_load=mysqli_query($obj,$insert1);


						 $result['wallet_balance'] =$current_balance;

			    	}

			    	$response['register']=$result;



			}
			else
			{   
                $response['status'] =201;  
				$response['msg']="Sorry Please try again later...!";

			} 

		}

		else{
			
			$response['status'] =201;	
 			$response['msg']="oops..wrong otp...!";
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