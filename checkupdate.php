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

	if($_POST['version']!=''){

		
        $user_id=$_POST['user_id'];
		$access_token=mysqli_real_escape_string($obj,$_POST["access_token"]);
		$current_version=$_POST['version'];

        // $response['status'] ='200';
        

        // validate access token 
       if(validateToken($access_token,$user_id)=='TRUE'){
				


		        // Fetch All Blog details
			    $select= "SELECT * from business_setting where type='version'";
				$query=mysqli_query($obj,$select);
				while($data=mysqli_fetch_assoc($query)){
				// print_r($data);
				
					if($data['value']==$current_version )
					{
                        $response['status'] =201;
					    $response['msg'] = "NO Update.";
                        $response['isupdate'] = false;
					}
					else
					{   
						$response['status'] =200;
                        $response['msg'] = "Please update your version.";
                        $response['isupdate'] = true;
					}

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


	echo json_encode($response, JSON_PRETTY_PRINT);
}


?>