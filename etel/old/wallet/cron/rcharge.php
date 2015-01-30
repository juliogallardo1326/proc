#!/usr/bin/php 
<?
###############################################################################
## PROGRAM     : Dopays.com - The First Open Source Payment Gateway          ##
## VERSION     : 2.80                                                        ##
## AUTHOR(S)   : Dmitry Pereuda, dmitry@pereuda.com                          ##
##               Oleg Ostaplyuck, oleg@ostaplyuk.com                         ##
## COMPANY     : Dosware Team                                                ##
## COPYRIGHTS  : (C)2003 Dosware Team. All Rights Reserved                   ##
##                                                                           ##
## LICENSE     : http://www.gnu.org/copyleft/gpl.html                        ##
###############################################################################
## Dopays  is free software;  you can redistribute it and/or modify it under ##
## the terms of the  GNU  General Public License as published  by  the  Free ##
## Software Foundation; either version 2 of the License, or (at your option) ##
## any later version.                                                        ##
##                                                                           ##
## Dopays is distributed in the hope that it will be useful, but WITHOUT ANY ##
## WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS ##
## FOR A PARTICULAR PURPOSE.  See the  GNU General Public License  for  more ##
## details.                                                                  ##
##                                                                           ##
## TO COMPLY WITH THIS LICENSE, PLEASE DO NOT REMOVE THE LINK TO THE DOSWARE ##
## WEBSITE FROM YOUR COPY OF THE SCRIPT.  THIS IS THE LEAST YOU  CAN  DO  TO ##
## SUPPORT THE DEVELOPMENT OF DOPAYS.COM!                                    ##
##                                                                           ##
## You should have received a copy of the  GNU  General Public License along ##
## with this program; if not, you can find it here:                          ##
##    http://www.gnu.org/copyleft/gpl.html                                   ##
## or write to the:                                                          ##
##    Free Software Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA ##
###############################################################################
@set_time_limit(0);
include('../config.htm');
###############################################################################
function out($str=''){
	print "$str\r\n";
	flush();
}
###############################################################################
function SendEmailNotification($UserId, $ProductId, $SubscriptActive){
	global $sitename, $data;
	$name="";
	$email="";
	$productname="";
	$sql="SELECT email, fname, lname FROM {$data['DbPrefix']}members WHERE id=$UserId";
  $res=mysql_query($sql);
  if ($res) if (mysql_num_rows($res) > 0) {
    $row=mysql_fetch_array($res);
    $email=$row['email'];
    $name=$row['fname'] . " " . $row['lname'];
  }
  $sql="SELECT name FROM {$data['DbPrefix']}products WHERE id=$ProductId";
  $res=mysql_query($sql);
  if ($res) if (mysql_num_rows($res) > 0) {
    $row=mysql_fetch_array($res);
    $productname=$row['name'];
  }
  if ($SubscriptActive) {
    $key="SUBSCRIPTION-RESUMED";
  }
  else {
    $key="SUBSCRIPTION-STOPPED";
  }
  if (!empty($email)) {
    $post['username']=$name;
    $post['product']=$productname;
    $post['email']=$email;
    send_email($key, $post);
  }
  else out("Can not send e-mail notification to user $name, email field is empty.");
}
###############################################################################
function Main(){
	global $data;
	$sql="SELECT 
      subs.*, subs.id as subscriptionid, (TO_DAYS(NOW()) - TO_DAYS(subs.sdate)) as daystopay, subs.member as payerid,
      prod.type, prod.type, prod.price, prod.period, prod.trial, prod.tax, prod.shipping, prod.name as productname, prod.owner as payeeid,
      memb.fname as userfname, memb.lname as userlname
    FROM 
      {$data['DbPrefix']}subscriptions subs LEFT JOIN {$data['DbPrefix']}products prod ON subs.product=prod.id,
      {$data['DbPrefix']}subscriptions subs2 LEFT JOIN {$data['DbPrefix']}members memb ON subs.owner=memb.id
    WHERE subs2.id=subs.id AND subs.sdate < NOW()";
	$res=mysql_query($sql);
	if ($res) if (mysql_num_rows($res) > 0){
		out("Subscriptions to process: " . mysql_num_rows($res));
		out("Working...");
		while ($row=mysql_fetch_array($res)){
			$amount=$row['shipping'] + $row['tax'] + $row['price'];
      $payrounds=floor ($row['daystopay'] / $row['period']);
      $unpaiddays=$row['daystopay'] - (floor($row['daystopay'] / $row['period']) * $row['period']);
      $amount=$amount * $payrounds;
      if ($payrounds > 0){
        if (select_balance($row['payerid']) >= $amount){
          $sql="UPDATE {$data['DbPrefix']}subscriptions SET ";
          if ($row['holded'] == 1){
            $paydate=date("Y-m-d", mktime (0,0,0, date("m"), date("d") - $unpaiddays, date("Y")));
            $sql .= " sdate='$paydate',holded=0";
            SendEmailNotification($row['payerid'], $row['product'], true);
          }else{
            $paydate=date("Y-m-d", mktime (0,0,0, date("m"), date("d") - $unpaiddays, date("Y")));
            $sql .= " sdate='$paydate'";
          }
          $sql .= " WHERE id={$row['subscriptionid']}";
          $rslt=mysql_query($sql);
          if ($rslt) {
            $fees=($amount * $data['PaymentPercent']/100) + $data['PaymentFees'];
            transaction($row['payerid'], $row['payeeid'], $amount, $fees, 0, 1, 'Payment for subscription ' . $row['productname'] . ' for ' . $row['userfname'] . " " . $row['userlname'] . ", " . ($payrounds*$row['period']) . ' days', '');
          }
        } else {
          $sql="UPDATE {$data['DbPrefix']}subscriptions SET holded=1 WHERE id={$row['subscriptionid']}";
          mysql_query($sql);
          SendEmailNotification($row['payerid'], $row['product'], false);
        }
      }
		}
	}
	out("Done.");
}
###############################################################################
Main();
###############################################################################
?>