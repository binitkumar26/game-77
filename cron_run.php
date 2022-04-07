<?php
//include include/header.php';
include('../include/config.php');

include("curl.php") ;

date_default_timezone_set("Asia/calcutta");

// echo $current_date=date('Y-m-d');

// echo $current_time=date('h:ia');
 
// echo $endTime = date('h:ia', strtotime('+15 minutes'));
// die;

 $current_date_time = date('Y-m-d H:i');

 $date = date('Y-m-d');


$sql1= "SELECT * FROM spin_game where expire_date_time='$current_date_time' ORDER BY id asc";
$qry1= mysqli_query($obj,$sql1);
$count_search= mysqli_num_rows($qry1);

while ($data1=mysqli_fetch_array($qry1)) {
	# code...
    $game_id=$data1['id'];
    $round=$data1['round'];

    $sql= "SELECT * FROM spin_result where game_id='$game_id' and round='$round'";
    $qry= mysqli_query($obj,$sql);
    $count_result= mysqli_num_rows($qry);

    if($count_result > 0){

           $data=mysqli_fetch_array($qry);

        

            if($data['status']=='Announced')
            {
                // if result status is Announced

            //	echo "1";

                

                $open_number = $data['temp_number'];

                 $result = spinResultSet($game_id, $date, $open_number, $round);

                 $result1= updateSpinGameTime($game_id,$round);

                 $sql="UPDATE spin_result SET open_number='$open_number', created_at='$current_date_time', status='Final Announced' where  game_id='$game_id' and round='$round' ";
    
                 $qry=mysqli_query($obj,$sql);


            }
            elseif($data['status']=='Final Announced')
            {  

                 // if result status is Final Announced

 
            	//echo "2";

                echo "Result Already Done.";
            
            }
            else
            { 

                // if result is blank status

                //echo "3";

                $minimum_bidding_amount_on_number =minimum_bidding_amount_on_number($game_id, $round);

                $open_number = $minimum_bidding_amount_on_number;

                 $result = spinResultSet($game_id, $date, $open_number, $round);

                 $result1= updateSpinGameTime($game_id,$round);

                 $sql="UPDATE spin_result SET open_number='$open_number', temp_number='$open_number', created_at='$current_date_time', status='Final Announced' where  game_id='$game_id' and round='$round' ";
    
                 $qry=mysqli_query($obj,$sql);

            

            } 

        }
        else
        {
            // if result is not there
             //echo "4";

             $minimum_bidding_amount_on_number =minimum_bidding_amount_on_number($game_id, $round);

             $open_number = $minimum_bidding_amount_on_number;

             $result = spinResultSet($game_id, $date, $open_number, $round);

             $result1= updateSpinGameTime($game_id,$round);


              $sql="INSERT INTO spin_result (game_id,round,open_number, temp_number,date, created_at, status) VALUES('$game_id','$round','$open_number','$open_number', '$date', '$current_date_time', 'Final Announced')";
    
              $qry=mysqli_query($obj,$sql);

        }


        //echo  $minimum_bidding_amount_on_number =minimum_bidding_amount_on_number($game_id, $round);
}

?>