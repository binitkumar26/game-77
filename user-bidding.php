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

	if($_POST['type'] == 'user-bidding'){

		
        $user_id=$_POST['user_id'];
		$access_token=mysqli_real_escape_string($obj,$_POST["access_token"]);

        

         // validate access token 
       if(validateToken($access_token,$user_id)=='TRUE'){
            
            $response['status'] =200;
         	// Fetch Type blog

				if($_POST['user_id']){

					$user_id = $_POST['user_id'];

					$where =" WHERE user_id='".$user_id."'";
				}

				else{

					$where ="";
				}


		        // Fetch All Blog details
				$select= "SELECT * from tbl_bidding".$where." order by id desc";

				$query=mysqli_query($obj,$select);
				while($data=mysqli_fetch_assoc($query)){


					$sql1= "SELECT * FROM tbl_games where id='".$data['game_id']."'";
                   	$qry1= mysqli_query($obj,$sql1);
                   	$data1= mysqli_fetch_array($qry1);


					
					$result[] = array(

									"user_id" => $data['user_id'],
									"game_id" => $data['game_id'],
									"game_name" => $data1['game_name'],
									"game_type_id" => $data['game_type_id'],
									"number_type_id" => $data['number_type_id'],
									"amount" => $data['amount'],
									"bid_number" => $data['bid_number'],
									//"date" => $data['date']
									"date" => date("Y-m-d \n H:i:sa",strtotime('+5 hour +30 minutes',
									strtotime($data['updated_at'])))
									
								);
				}

				$response['user-bidding-list'] = $result;


			

         
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