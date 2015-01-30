<div id="time" style="position:absolute;width:300px;height:40px;z-index:1; overflow: hidden;left:10;top:104">
<table><tr><td>
<!-- <form name="form_timer"><input type="text" value="" name="curr_time" style="border:10px;font-face:verdana;font-weight:bold;Color:#006633;width:280px"></form> -->
</td></tr></table>
</div>
<script language="JavaScript">
<!--
self.setInterval('show_time()', 1000)
function show_time() {
	var time_per
	var x = new Array("Sunday", "Monday", "Tuesday");
  	var x = x.concat("Wednesday","Thursday", "Friday");
  	var x = x.concat("Saturday");
	var z = new Array("January","February","March","April","May");
  	var z = z.concat("June","July","August","September");
  	var z = z.concat("October","November","December");
	var today_datetime = new Date();
	var today_month = z[today_datetime.getMonth()];
	var today_date = today_datetime.getDate();
	var today_day  = x[today_datetime.getDay()];
	var today_year = today_datetime.getYear();
	var today_hours = today_datetime.getHours();
	var today_mins = today_datetime.getMinutes();
	var today_secs = today_datetime.getSeconds();
	if(today_secs < 10) {
		today_secs = "0"+ today_secs ;
	}
	if(today_mins < 10){
		today_mins ="0"+ today_mins;
	}
	if(today_hours >= 12) {
		time_per ="PM";
	} else {
		time_per ="AM";
	}
	if(today_hours > 12){
		today_hours = today_hours - 12;
	}
	if(today_hours < 10) {
		today_hours ="0"+ today_hours;
	}
	if(today_hours == "00") {
		today_hours = "12";
	}

	var mytime = today_month +" "+ today_date +", "+ today_year +" "+ today_day +" "+ today_hours +":"+ today_mins +":"+ today_secs+" "+ time_per;
	// document.form_timer.curr_time.value=mytime;
	time.innerHTML = "<font style='font-face:verdana;font-weight:bold;Color:#448A99;'>"+mytime+"</font>";
}
//-->
</script>
