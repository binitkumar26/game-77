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

		
        $id=$_POST['id'];
		$password=md5(mysqli_real_escape_string($obj,$_POST["password"]));
		$new_password=mysqli_real_escape_string($obj,$_POST["new_password"]);
		$confirm_password=mysqli_real_escape_string($obj,$_POST["confirm_password"]);
		$access_token=mysqli_real_escape_string($obj,$_POST["access_token"]);

        // $response['status'] ='200';
        

        // validate access token 
       if(validateToken($access_token,$id)=='TRUE'){

         	$sql= "SELECT * FROM tbl_users where id='$id'";

          	$qry= mysqli_query($obj,$sql);

          	$data= mysqli_fetch_array($qry);

          	$old_password=$data['password'];  

          	// match old password with post by user
 
	          if($old_password == $password )
	          {

	               if($confirm_password==$new_password)
	               {
	                 
	                 $update="UPDATE tbl_users SET password='".md5($confirm_password)."' where id=$id";

	                 $run_customer=mysqli_query($obj,$update);

		                if($run_customer)
		                {
		               $response['status'] =200;
		                $response['msg']='You are successfull updated your Password';

		                }
		                  else
		                {
		                    $response['status'] =201;
		                    $response['msg']='Sorry Please try again later...!';

		                }

	               }
	              else
	              {     
	              	    $response['status'] =201;
	                    $response['msg']='Sorry enter password doesn\'t match...!';
	              }

	          }
	          else
	          {     
	          	    $response['status'] =201;
	                $response['msg']='Sorry Please enter correct password...!';
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
	
	//echo json_encode(array("status"=>"200","data"=>$response));
	echo json_encode($response);
}


?>