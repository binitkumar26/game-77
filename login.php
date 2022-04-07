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
		$password=mysqli_real_escape_string($obj,$_POST["password"]);

        
        // check user details
		$data_check= "SELECT * from tbl_users where mobile='".$mobile."' && password='".md5($password)."'";
        $access_token=getToken();	   //generate  access token

		$query=mysqli_query($obj,$data_check);
		$data=mysqli_fetch_assoc($query);

		if(($data)==true) {

			$sql1="UPDATE tbl_users set access_token='$access_token', last_login=NOW() where id=".$data['id']."";
			$query1=mysqli_query($obj,$sql1);

			if($query1==true)
			{
               $response['status'] =200;
               $response['msg'] = "Login successfull.";

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

					$result['wallet_balance']=walletBalance($data2['id']);

                    $response['login']=$result;
		    	}
		    	else
		    	{   
		    		$response['status'] =201;
		    		$response['msg']="Your Account is Deactivate contact to Admin.";
		    	}



			}
			else
			{   
                $response['status'] =201;
				$response['msg']="Token Issue";

			} 

		}

		else{
			
			$response['status'] =201;	
 			$response['msg']="oops..wrong mobile and password...!";
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