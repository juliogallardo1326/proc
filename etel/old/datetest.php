<?php
 include('includes/dbconnection.php');
 require_once('includes/function.php');
 include('includes/function1.php');
 include('includes/function2.php'); 
 $trans_amount=0;											
 $str_startdate='2004-02-23';
 $str_afterdate='2004-02-29';
 $approvedstatusdate='2004-03-19';
 $count=0;
 $enddate='2004-09-13';
 for($numloop=0;$numloop<29;$numloop++){

 echo $count+=1;
 						$startdate=$str_startdate." 00:00:00";
 						$afterdate=$str_afterdate." 23:59:59";
						$datearray=explode("-",$str_startdate);
				 $year=$datearray[0]."<br>";
				 $month=$datearray[1]."<br>";
				 $day=$datearray[2]."<br>";
				$time=mktime(0,0,0,$month,$day,$year);
		$str_startdate =strtotime ("+1 week",$time);
		echo $str_startdate= date('Y-m-d',$str_startdate)."<br>";
		$datearray=explode("-",$str_afterdate);
				 $year=$datearray[0]."<br>";
				 $month=$datearray[1]."<br>";
				 $day=$datearray[2]."<br>";
				$time=mktime(0,0,0,$month,$day,$year);
		$str_afterdate=strtotime ("+1 week",$time);
		echo $str_afterdate= date('Y-m-d',$str_afterdate)."<br>";
		$datearray=explode("-",$approvedstatusdate);
				 $year=$datearray[0]."<br>";
				 $month=$datearray[1]."<br>";
				 $day=$datearray[2]."<br>";
		$time=mktime(0,0,0,$month,$day,$year);
		$approvedstatusdate =strtotime ("+1 week",$time);
		echo $approvedstatusdate= date('Y-m-d',$approvedstatusdate)."<br>";
				//next set of days generated
		}//end of while loop		
		
	echo $count;
?>
