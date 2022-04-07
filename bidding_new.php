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
    
      $someJSON = file_get_contents('php://input');

      //$someJSON1=$someJSON['bidding_list'];

	  $someArray = json_decode($someJSON, true);
	 // echo count($someArray);
	 //print_r($someArray);        // Dump all data of the Array
	   $someArray1 = $someArray['bidding_list']; // Access Array data

    foreach($someArray1 as $value){

    	// print_r($value);
    	// die();

    	 $user_id        =   $value['user_id'];
		 $access_token   =   $value["access_token"];

		 $game_id        =   $value['game_id'];
		 $game_type_id   =   $value["game_type_id"];
		 $number_type_id =   $value['number_type_id'];
		 $bid_number     =   $value["bid_number"];
		 $amount         =   $value['amount'];

		 $type           =   $value['type'];
		 $date           =   date('Y-m-d');
		 $created_at     =   $default_datetime;



	// if($_POST['type'] == 'bidding'){
   if($type == 'bidding'){
		
        // $user_id=$_POST['user_id'];
		// $access_token=mysqli_real_escape_string($obj,$_POST["access_token"]);

		//  $game_id=$_POST['game_id'];
		//  $game_type_id=mysqli_real_escape_string($obj,$_POST["game_type_id"]);
		//  $number_type_id=$_POST['number_type_id'];
		//  $bid_number=mysqli_real_escape_string($obj,$_POST["bid_number"]);
		//  $amount=$_POST['amount'];
		//  $date=date('Y-m-d');
		//  $created_at=$default_datetime;
		

         //$response['status'] ='200';
        

        // validate access token 
       if(validateToken($access_token,$user_id)=='TRUE'){
             

             // check wallet balance
       	    if(checkBalance($user_id,$amount)=='TRUE'){


       	    	// check game is open on not
       	    	$select1= "SELECT * from tbl_games where id='$game_id'";
				$query1=mysqli_query($obj,$select1);
				$data=mysqli_fetch_assoc($query1);

					$current_time = date('H:i');
					$start_date_time = $data['start_date_time'];
					$expire_date_time = $data['expire_date_time'];

					$date1 = DateTime::createFromFormat('H:i', $current_time);
					$date2 = DateTime::createFromFormat('Y-m-d H:i', $start_date_time);
					$date3 = DateTime::createFromFormat('Y-m-d H:i', $expire_date_time);

					if ($date1 > $date2 && $date1 < $date3)
					{
					  // when game is open 
                       
                       $round = gameRound($game_id);// get current round

						 // Insert details
						$insert="INSERT into tbl_bidding (user_id, game_id, round, game_type_id, number_type_id, amount, bid_number, date,created_at) VALUES('$user_id','$game_id','$round','$game_type_id','$number_type_id','$amount','$bid_number', '$date', '$created_at')";

						$query=mysqli_query($obj,$insert);

						if($query==true)
						{
							$response['status'] =200;
							$response['msg'] = "You are successfull Bid your amount...!";

		                   // $user_balance = walletBalance($user_id)-$amount;
                            
							$updated_balance = subBalance($user_id, $amount);
		 					

					    	$response['wallet_balance'] = $updated_balance;


					    	// check for commision

					    	$referral_user_id = haveReferral($user_id);

                            
                            $sql = " SELECT * FROM tbl_users where id='$referral_user_id' ";

							$qry=mysqli_query($obj, $sql);

							while($data= mysqli_fetch_array($qry)){

							$referral_income = $data['commision'];

							}


					    	$referral_commision = ($referral_income / 100) * $amount;

					    	
					    	if($referral_user_id!=''){

					    		// add commision

                              
                              $addCommision = addCommision($referral_user_id, $referral_commision);


					    	 // addCommisionTransaction to tbl wallet 

					    	  addCommisionTransaction($referral_user_id, $referral_commision, $game_id, $round);

					    	}

					    	


					    	addBiddingTransaction($user_id, $amount, $game_id, $updated_balance, $round);

					    	

						}


					}
					else
					{
						 $response['status'] =201;
						 $response['msg']="Game is Closed.";
					}




			}else{
                $response['status'] =201;
				$response['msg']="Insufficient Balance.";
			}	


         
	    }else{
             $response['status'] =201;
	    	 $response['msg']="Access Token Not Valid.";
	    }

		

 	}else{
        $response['status'] =201;
 		$response['msg']="Invalid Parameter.";
 	}

 }

	return $response;
}


// output

function response($response){
	
	//echo json_encode(array("status"=>"200","data"=>$response));
	echo json_encode($response);
}


?>