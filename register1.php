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

		// $name=mysqli_real_escape_string($obj,$_POST["name"]);
		// $unique_id=mysqli_real_escape_string($obj,$_POST["mobile"]);
  //       $mobile=mysqli_real_escape_string($obj,$_POST["mobile"]);
  //       $referral_code=mysqli_real_escape_string($obj,$_POST["referral_code"]);		
  //       $password=mysqli_real_escape_string($obj,$_POST["password"]);
  //       $confirm_password=mysqli_real_escape_string($obj,$_POST["confirm_password"]);

		$name=mysqli_real_escape_string($obj,trim($_POST["name"]));
		//$unique_id=unique_id(8);
		$unique_id='WIN'.trim($_POST["mobile"]);
        $mobile=mysqli_real_escape_string($obj,trim($_POST["mobile"]));
        $referral_code=mysqli_real_escape_string($obj,trim($_POST["referral_code"]));		
        $password=mysqli_real_escape_string($obj,trim($_POST["password"]));
        $confirm_password=mysqli_real_escape_string($obj,trim($_POST["confirm_password"]));

    
       

	    // check both password is match or not
	    if($password == $confirm_password)
	    {

			$sel_check="SELECT *from tbl_users where mobile='$mobile'";
			$run_check=mysqli_query($obj,$sel_check);
			
			$check=mysqli_num_rows($run_check);
	        if($check==1)
	            {
				    $response['status'] =201;
	            	$response['msg']="Mobile Number is already register select another number and try again";
				
			   }

	       else
	       {

	       	  $access_token=getToken();	   //generate  access token

			   $insert="INSERT into tbl_users (name,unique_id,password,mobile,referral_code,created_at,access_token,status,type) VALUES('$name','$unique_id','".md5($_POST['password'])."','$mobile','$referral_code',NOW(),'$access_token', '1','user')";

				$run_customer=mysqli_query($obj,$insert);

				if($run_customer==true)
				{
				    $response['status'] =200;
					$response['msg'] = "You are successfull Sign Up with us...!";
 					

					$sql2="SELECT * from tbl_users where mobile='".$mobile."' && password='".md5($password)."'";
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

		}else{
              $response['status'] =201; 
              $response['msg']="Password Does Not Match.";
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