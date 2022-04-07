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

	if($_POST['type'] == 'announcement'){

		
        $user_id=$_POST['user_id'];
		$access_token=mysqli_real_escape_string($obj,$_POST["access_token"]);

        // $response['status'] ='200';
        

        // validate access token 
       if(validateToken($access_token,$user_id)=='TRUE'){


       	  $response['status'] =200;

         	// Fetch Type blog

				if($_POST['id']){

					$id = $_POST['id'];
					$status = '1';

					$where =" WHERE id='".$id."' AND status = ".$status;
				}

				else{

					$status = '1';
					$where =" WHERE status=".$status;
				}


		        // Fetch All Blog details
				$select= "SELECT * from announcement".$where;
				$query=mysqli_query($obj,$select);
				while($data=mysqli_fetch_assoc($query)){

					
					   $result[] = array(

									"id" => $data['id'], 
									"description" => strip_tags($data['description']),
									"updated_at" => $data['updated_at'],
									
									"status" => $data['status'],
									
								);
					

					
					
				}

				$response['announcement'] = $result;


			

         
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


	echo json_encode($response, JSON_PRETTY_PRINT);
}


?>