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

	if($_POST['type'] == 'result'){

		
        $user_id=$_POST['user_id'];
		$access_token=mysqli_real_escape_string($obj,$_POST["access_token"]);

         // $response['status'] ='200';
        

        // validate access token 
       if(validateToken($access_token,$user_id)=='TRUE'){

       	$response['status'] =200;

         	// Fetch Type blog

				if($_POST['game_id']){

					$game_id = $_POST['game_id'];

					$where =" WHERE game_id='".$game_id."'";
				}

				elseif($_POST['current_date']==true){

					$current_date=date('Y-m-d');

					$where =" WHERE date='".$current_date."'";
				}

				else{

					$where ="";
				}


		        // Fetch All Blog details
				$select= "SELECT * from tbl_result".$where;

				$query=mysqli_query($obj,$select);
				while($data=mysqli_fetch_assoc($query)){

					if($data['open_number']==''){
                      
                      $open_number= $data['temp_number'];

					}else{
                       
                       $open_number= $data['open_number'];
					}

					


					$sql1= "SELECT * FROM tbl_games where id='".$data['game_id']."'";
                   	$qry1= mysqli_query($obj,$sql1);
                   	$data1= mysqli_fetch_array($qry1);

					
					$result[] = array(

									"result_id" => $data['id'],
									"game_id" => $data['game_id'],
									"game_name" => $data1['game_name'],
									"date" => $data['date'],
									"open_number" => $open_number,
									"updated_at" => date('h:i a',strtotime(substr($data['created_at'],11,5)))
									
								);
				}

				$response['result'] = $result;


			

         
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