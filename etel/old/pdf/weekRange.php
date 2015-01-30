<?php
$str_date = "2004-05-11";
	$str_year = substr($str_date,0,4);
	$str_month = substr($str_date,5,2);
	$str_day = substr($str_date,8,2);
	$i_day_of_week = date("w",mktime(0,0,0,$str_month,$str_day,$str_year));
	$i_month_without_zero = date("n",mktime(0,0,0,$str_month,$str_day,$str_year));
	$i_year = date("Y",mktime(0,0,0,$str_month,$str_day,$str_year));
	//$i_month_with_zero = date("m",mktime(0,0,0,$str_month,$str_day,$str_year));
	$i_day = date("j",mktime(0,0,0,$str_month,$str_day,$str_year));

	$StartOfWeek = date("d",mktime(0,0,0,$i_month_without_zero,($i_day-$i_day_of_week),$i_year))."/".date("m",mktime(0,0,0,$i_month_without_zero,($i_day-$i_day_of_week),$i_year))."/".date("Y",mktime(0,0,0,$i_month_without_zero,($i_day-$i_day_of_week),$i_year)); 

	$EndOfWeek = date("d",mktime(23,59,59,$i_month_without_zero,($i_day+(6-$i_day_of_week)),$i_year))."/".date("m",mktime(23,59,59,$i_month_without_zero,($i_day+(6-$i_day_of_week)),$i_year))."/".date("Y",mktime(23,59,59,$i_month_without_zero,($i_day+(6-$i_day_of_week)),$i_year)); 
	
	print(date("Y-m-d",mktime(0,0,0,$i_month_without_zero,($i_day_of_week-$i_day_of_week),$i_year))."<br>");
	print($StartOfWeek."<br>");
	print($EndOfWeek."<br>");

?>