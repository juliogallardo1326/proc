<?
//changed -- function to generate char
function func_assign_randstr_value($num)
{
	switch($num)
	{
		case "1": $rand_value = "A"; break;
		case "2": $rand_value = "B"; break;
		case "3": $rand_value = "C"; break;
		case "4": $rand_value = "D"; break;
		case "5": $rand_value = "E"; break;
		case "6": $rand_value = "F"; break;
		case "7": $rand_value = "G"; break;
		case "8": $rand_value = "H"; break;
		case "9": $rand_value = "J"; break;
		case "0": $rand_value = "Z"; break;
	}
	return $rand_value;
}

function func_assign_randstr_last_value($num)
{
	switch($num)
	{
		case "1": $rand_value = "K"; break;
		case "2": $rand_value = "L"; break;
		case "3": $rand_value = "M"; break;
		case "4": $rand_value = "N"; break;
		case "5": $rand_value = "O"; break;
		case "6": $rand_value = "P"; break;
		case "7": $rand_value = "Q"; break;
		case "8": $rand_value = "R"; break;
		case "9": $rand_value = "S"; break;
		case "0": $rand_value = "T"; break;
	}
	return $rand_value;
}

//change -- function to concatenate random numbers with trans id
function func_Trans_Ref_No($trans_id)
{	
	$random_first=rand(0,9);
	$random_second=rand(0,9);
	$random_firststr=func_assign_randstr_value($random_second);
	$random_secondstr=func_assign_randstr_value($random_first);
	$random_thirdstr=func_assign_randstr_last_value($random_second);
	$random_fourthstr=func_assign_randstr_last_value($random_first);
	$return_refno=$random_fourthstr.$random_secondstr.$random_first.$random_second.$trans_id.$random_thirdstr.$random_firststr;
	return $return_refno;
			
}
///////////////newfunction

function func_User_Ref_No($userid)
{	
	$random_first=rand(0,9);
	$random_second=rand(0,9);
	$random_third=rand(10,99);
	$random_fourth=rand(0,9);
	$random_fifth=rand(0,9);
	$random_sixth=rand(10,99);
	$random_firststr=func_assign_randstr_value($random_second);
	$random_secondstr=func_assign_randstr_last_value($random_first);
	$random_thirdstr=$random_third;
	$random_fourthstr=$random_fourth;
	$random_fifthstr=func_assign_randstr_last_value($random_fifth);
	$random_sixthstr=$random_sixth;
	$user_ref_num=$random_secondstr.$userid.$random_fourthstr;
	return $user_ref_num;
			
}
?>