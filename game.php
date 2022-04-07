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

	if($_POST['type'] == 'games'){

		
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
				$select= "SELECT * from tbl_games".$where;
				$query=mysqli_query($obj,$select);
				while($data=mysqli_fetch_assoc($query)){

					$current_time = date('H:i');
					$start_date_time = $data['start_date_time'];
					$expire_date_time = $data['expire_date_time'];

					$date1 = DateTime::createFromFormat('H:i', $current_time);
					$date2 = DateTime::createFromFormat('Y-m-d H:i', $start_date_time);
					$date3 = DateTime::createFromFormat('Y-m-d H:i', $expire_date_time);

					if ($date1 > $date2 && $date1 < $date3)
					{
					   $result[] = array(

									"id" => $data['id'], 
									"game_name" => $data['game_name'],
									"start_time" => date('h:i a',strtotime($data['start_time'])),
									"expire_time" => date('h:i a',strtotime($data['expire_time'])),
									"round" => $data['round'],
									"game_status" => 'OPEN'
									
								);
					}
					else
					{
						$result[] = array(

									"id" => $data['id'], 
									"game_name" => $data['game_name'],
									"start_time" => date('h:i a',strtotime($data['start_time'])),
									"expire_time" => date('h:i a',strtotime($data['expire_time'])),
									"round" => $data['round'],
									"game_status" => 'CLOSED'
									
								);
					}

					
					
				}

				$response['games'] = $result;


			

         
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