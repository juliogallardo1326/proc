<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php 




 $trans_refno=func_Trans_Ref_No(26786766);
 echo($trans_refno."<Br>");
 $c=strlen($trans_refno);
 echo($c."<br>");
 $a=substr($trans_refno,2,$c-4);
 echo($a);
 


function func_Trans_Ref_No($trans_id)
{	
	$random_first=rand(0,9);
	$random_second=rand(0,9);
	$random_firststr=func_assign_randstr_value($random_second);
	$random_secondstr=func_assign_randstr_value($random_first);
	$return_refno=$random_first.$random_second.$trans_id.$random_firststr.$random_secondstr;
    return $return_refno;
			
}
		
		
		
function func_assign_randstr_value($num)
{

  switch($num)
  {
    case "1":
     $rand_value = "A";
    break;
    case "2":
     $rand_value = "B";
    break;
    case "3":
     $rand_value = "C";
    break;
    case "4":
     $rand_value = "D";
    break;
    case "5":
     $rand_value = "E";
    break;
    case "6":
     $rand_value = "F";
    break;
    case "7":
     $rand_value = "G";
    break;
    case "8":
     $rand_value = "H";
    break;
    case "9":
     $rand_value = "J";
    break;
	case "0":
     $rand_value = "Z";
    break;
  }
  return $rand_value;
}

function func_update_single_field($tablename,$fieldname,$fieldvalue,$cnn,$comparefield,$comparefieldvalue)
{
	$qryUpdate = "update $tablename set $fieldname='$fieldvalue' where $comparefield=$comparefieldvalue";
	if(!mysql_query($qryUpdate,$cnn_cs)){
		print(mysql_errno().": ".mysql_error()."<BR>");
		print("Can not execute approval status update query");
		exit();
	}
}

?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

</body>
</html>
