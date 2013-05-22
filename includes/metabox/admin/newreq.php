<?php
date_default_timezone_set('America/Denver');
//echo date_default_timezone_get();
function convertmilitarytime($thetime,$ampm){
  $time=0;
  $halfHourValue = 0;  
  if((strpos($thetime,'.5') !== false)){
        $thetime = substr($thetime, 0, -2);
        $halfHourValue = 0.5;
  }
    
  if($ampm == 'AM'){
    if($thetime == 12)
      $time = 0;
    else $time = $thetime;
  }
  
  if($ampm == 'PM'){
    if($thetime == 12)
      $time = 12;
    else $time = 12+$thetime;
  }
  return $time+$halfHourValue;
}

function getweekday($date){  //MDY
  $dateArr = explode("/",$date);
  $timestamp = mktime(0,0,0,$dateArr[0],$dateArr[1],$dateArr[2]);
  $timeArr = getdate($timestamp);
  return $timeArr['weekday'];
}

if($_POST['submitrequest'] && !$_GET['request']){
    $requestcheck = '';
    
    if(isset($_POST['requestcheck'])) {
        $requestcheck = implode(",",$_POST['requestcheck']);
    }

    if(!$_POST['reporter'] > '') $error[] = "Enter a reporter";


    if(!$_POST['email'] > '') {
        $error[] = "Enter a valid BYU-I email";
    }else if(!preg_match('/[a-z]{3}[0-9]{5}@byui.edu/', $_POST['email'])){
            $error[] = "Enter a valid BYU-I email";
    } 

    if(!$_POST['group'] > '') $error[] = "Choose a group";
    if(!$_POST['requestcheck']) $error[] = "Request some equipment";

    if($_POST['checkoutdate'] == '') 
    $error['nodatecheckout'] = "Enter a check-out date";
    if($_POST['checkouttime'] == '') 
    $error['notimecheckout'] = "Enter a check-out time";
    else if(!$_POST['timeofdaycheckout']) 
    $error['ampmcheckout'] = "Enter AM or PM for check-out time";

    if($_POST['checkindate'] == '') 
    $error['nodatetimecheckin'] = "Enter a check-in date";
    if($_POST['checkintime'] == '') 
    $error['nodatetimecheckin'] = "Enter a check-in time";
    else if(!$_POST['timeofdaycheckin']) 
    $error['ampmcheckin'] = "Enter AM or PM for check-in time";

    //make the date
    $start = strtotime($_POST['checkindate']);
    $end = strtotime($_POST['checkoutdate']);

    if(strtotime($_POST['checkoutdate']) > strtotime($_POST['checkindate']))
        $error[] = "The check-out date needs to be before the check-in date";

    //after the hour is parsed then check that they both are in te right order
    //convert to military time
    $checkintime = convertmilitarytime($_POST['checkintime'],$_POST['timeofdaycheckin']);
    $checkouttime = convertmilitarytime($_POST['checkouttime'],$_POST['timeofdaycheckout']);
        
    if($_POST['checkoutdate'] == $_POST['checkindate']){
        if($checkouttime > $checkintime){
            $error[] = "The check-out time needs to be before the check-in time";
        }
        if($checkouttime == $checkintime && $checkouttime != ''){
            $error[] = "The check-out and check-in times cannot be the same";
        }
    }
    if($_POST['checkoutdate'] == date('m/d/Y')){
        $error[] = "Sorry, policy prohibits same day reservations";
    }
    
    
    //valid check-in times of Friday Checkouts: Before 12PM Monday
    //valid check-in times of Monday-Thursday Checkouts: 1PM-2PM & 3PM-5PM on Tuesdays, 1PM-5PM on Wednesday & Thursday & Friday.
    //valid check-out times Monday: 9AM-5PM, Tuesdays: 1PM-2PM & 3PM-5PM, Wednesday & Thursday & Friday: 1PM-5PM
    //saturday sunday saturday and sunday are closed for requests

    //date j is doy of month without leading 0's  n is the month without leading 0's
    if(date("n-j-Y",time()) == $_POST['checkoutdate']){

        $convertedCheckouttime = $checkouttime.":00"; 
        $convertedCheckintime = $checkintime.":00"; 
        if(!(strpos($checkouttime,'.5') !== false)){
            $convertedCheckouttime = substr($checkouttime, 0, -2).":30"; 
        }
        if(!(strpos($checkintime,'.5') !== false)){
            $convertedCheckintime = substr($checkintime, 0, -2).":30"; 
        }

        $checkouttimestamp = strtotime(date("m/d/Y",time()). " $convertedCheckouttime");
        if($checkouttimestamp > strtotime(date("m/d/Y",time())) AND $checkouttimestamp < time()) $error[] = "The check-out time needs to be after the current half-hour today";
        $checkintimestamp = strtotime(date("m/d/Y",time()). " $convertedCheckintime");
        if($checkintimestamp > strtotime(date("m/d/Y",time())) AND $checkintimestamp < time()) $error[] = "The check-in time needs to be after the current half-hour today";
    }

    if(date("m-d-YYYY",time()) == $_POST['checkindate']){
        $checkintimestamp = strtotime(date("m/d/Y",time()). " $checkintime:00");
        if($checkintimestamp > strtotime(date("m/d/Y",time())) AND $checkintimestamp < time()) $error[] = "The check-in time is needs to be after the current half-hour today";
    }

    if(strtotime($_POST['checkoutdate']) < strtotime(date("m/d/Y",time()))) $error[] = "You need to pick a check-out date of tomorrow or later";

    if(strtotime($_POST['checkindate']) < strtotime(date("m/d/Y",time()))) $error[] = "You need to pick a check-in date of tomorrow or later";
    //if(strtotime(str_replace("-","/",$_POST['checkoutdate'])) < time()) echo "You need to pick a Check-out date after today";

    if($_POST['checkindate'] != '' ){
        $checkinWeekday = getweekday($_POST['checkindate']);        
    }
    if($_POST['checkoutdate'] != '' ){
        $checkoutWeekday = getweekday($_POST['checkoutdate']);
    }


    // Enforce Valid Check-in times
    if($checkoutWeekday == 'Friday' && $checkinWeekday != 'Friday'){
        if($checkintime < 9 || $checkintime > 12){
            $error[] = "Please change the check-in time. Items checked-out on Fridays must be returned before 12PM Monday.";
        }
    }
    if($checkinWeekday == 'Monday'){
        if($checkintime < 9 || $checkintime > 17){
            $error[] = "Please change the check-in time. Valid check-in times for Monday are from 9AM - 5PM.";           
        }
    }
    if($checkinWeekday == 'Tuesday'){
        if($checkintime < 1 || $checkintime > 17){
            $error[] = "Please change the check-in time. Valid check-in times for Monday are from 9AM - 5PM.";
        }
        if($checkintime < 3 && $checkintime > 1){
            $error[] = "Please change the check-in time. Valid check-in times for Tuesday are from 1PM - 2PM, 3PM - 5PM.";
        }
    }
    if($checkinWeekday == 'Wednesday'){
        if($checkintime < 1 || $checkintime > 17){
            $error[] = "Please change the check-in time. Valid check-in times for Wednesday are from 1PM - 5PM.";           
        }
    }
    if($checkinWeekday == 'Thursday'){
        if($checkintime < 1 || $checkintime > 17){
            $error[] = "Please change the check-in time. Valid check-in times for Thursday are from 1PM - 5PM.";           
        }
    }
    if($checkinWeekday == 'Friday'){
        if($checkintime < 1 || $checkintime > 17){
            $error[] = "Please change the check-in time. Valid check-in times for Friday are from 1PM - 5PM.";           
        }
    }
    if($checkinWeekday == 'Saturday'){
        if($checkintime < 1 || $checkintime > 17){
            $error[] = "Please change the check-in time. Saturday is closed for checkins.";           
        }
    }
    if($checkinWeekday == 'Sunday'){
        if($checkintime < 1 || $checkintime > 17){
            $error[] = "Please change the check-in time. Sunday is closed for checkins.";           
        }
    }

    // Enforce Valid Check-out times
    if($checkoutWeekday == 'Monday'){
        if($checkouttime < 9 || $checkouttime > 17){
            $error[] = "Please change the check-out time. Valid check-out times for Monday are from 9AM - 5PM.";           
        }
    }
    if($checkoutWeekday == 'Tuesday'){
        if($checkouttime < 1 || $checkouttime > 17){
            $error[] = "Please change the check-out time. Valid check-out times for Monday are from 9AM - 5PM.";
        }
        if($checkouttime < 3 && $checkouttime > 1){
            $error[] = "Please change the check-out time. Valid check-out times for Tuesday are from 1PM - 2PM, 3PM - 5PM.";
        }
    }
    if($checkoutWeekday == 'Wednesday'){
        if($checkouttime < 1 || $checkouttime > 17){
            $error[] = "Please change the check-out time. Valid check-out times for Wednesday are from 1PM - 5PM.";           
        }
    }
    if($checkoutWeekday == 'Thursday'){
        if($checkouttime < 1 || $checkouttime > 17){
            $error[] = "Please change the check-out time. Valid check-out times for Thursday are from 1PM - 5PM.";           
        }
    }
    if($checkoutWeekday == 'Friday'){
        if($checkouttime < 1 || $checkouttime > 17){
            $error[] = "Please change the check-out time. Valid check-out times for Friday are from 1PM - 5PM.";           
        }
    }
    if($checkoutWeekday == 'Saturday'){
        if($checkouttime < 1 || $checkouttime > 17){
            $error[] = "Please change the check-out time. Saturday is closed for checkins.";           
        }
    }
    if($checkoutWeekday == 'Sunday'){
        if($checkouttime < 1 || $checkouttime > 17){
            $error[] = "Please change the check-out time. Sunday is closed for checkins.";           
        }
    }

    // Enforce Comments on selected items
    if(!trim($_POST['comment']) && (!(strpos($requestcheck, 'Lens') === false) || !(strpos($requestcheck, 'Light Kit') === false) || !(strpos($requestcheck, 'Video Productions Kit') === false) || !(strpos($requestcheck, 'Other') === false))){
        $error[] = "Enter a description in the comments";   
    }

    
    if(!$error){

    $checkinArr = explode("/",$_POST['checkindate']);
    $checkoutArr = explode("/",$_POST['checkoutdate']);

    $check_in_date = $checkinArr[2] . "-" . $checkinArr[0] . "-" . $checkinArr[1] . " $checkintime:00:00";
    $check_out_date = $checkoutArr[2] . "-" . $checkoutArr[0] . "-" . $checkoutArr[1] . " $checkouttime:00:00";

    /* 
    INSERT INTO equipment (name ,`group` ,request ,check_out_date ,check_in_date ,last_mod ,`comment`)
    VALUES ('mike', 'Inews', 'Lens,Flash', '2009-7-4 ', '2009-7-4', NOW() , 'I request equipment');
    */
    @mysql_select_db("icommequipment", $con);

    $sql = "INSERT INTO equipment (name, email, `group`, request, check_out_date, check_in_date, last_mod, create_date, `comment`) VALUES( '".mysql_real_escape_string(strip_tags($_POST['reporter']))."','".mysql_real_escape_string(strip_tags($_POST['email']))."','".mysql_real_escape_string(strip_tags($_POST['group']))."','".mysql_real_escape_string(strip_tags($requestcheck))."','".mysql_real_escape_string(strip_tags($check_out_date))."','".mysql_real_escape_string(strip_tags($check_in_date))."', NOW() , NOW(), '".mysql_real_escape_string(strip_tags($_POST['comment']))."');";
    $equipment_result = @mysql_query($sql);
    if(!$equipment_result){ echo "There was a problem connecting to the database"; exit(); }
    //$statement = $db->prepare($sql);
    //$statement->bind_param('ssssss', $_POST['reporter'],$_POST['group'],$requestcheck,$check_out_date,$check_in_date,$_POST['comment']);
    //if($statement->execute()) 
    $_SESSION['success'] = "Thank you. Your request has been submitted.";
    //else
    //$_SESSION['success'] = "There was a problem with your request, please let the admin know about the problem";
    //I could send all the values to my mail when there is a problem so I will know if it was a problem with their data or the internet
    //$statement->close();
    echo '<meta http-equiv="refresh" content="0;url=http://www.byuicomm.net/admin/makereq.php?request=1" />';
    exit();
    }  
}

?>
<script type="text/javascript" src="/js/datepicker/jquery-1.4.2.js"></script>
<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>-->
<link type="text/css" href="/js/datepicker/base/ui.all.css" rel="stylesheet" />
<script type="text/javascript" src="/js/datepicker/ui/ui.core.js"></script>
<script type="text/javascript" src="/js/datepicker/ui/ui.datepicker.js"></script>
<script type="text/javascript">
$(function() {
$('#checkindate').datepicker({
changeMonth: true,
changeYear: true,
yearRange: '-20:+20'
});
$('#checkoutdate').datepicker({
changeMonth: true,
changeYear: true,
yearRange: '-20:+20'
});

});
</script>

<style type='text/css'>	
@font-face {
    font-family: 'OptimaLTStdRoman';
    src: url('fonts/optima-webfont.eot');
    src: url('fonts/optima-webfont.eot?#iefix') format('eot'),
         url('fonts/optima-webfont.woff') format('woff'),
         url('fonts/optima-webfont.ttf') format('truetype'),
         url('fonts/optima-webfont.svg#webfontFD9GRi5e') format('svg');
    font-weight: normal;
    font-style: normal;
}

body {
  background: url("images/background_jeans.png") repeat scroll 0 0 #F5F4F0;
}

input, textarea {
    background-color: #FEFEFE;
}

#reqcontent {
 width:800px;
/* background: -moz-linear-gradient(center top , #fafafa, #f1f1f1) repeat scroll 0 0 #f1f1f1;
 background: -webkit-gradient(linear, left top, left bottom, from(#fafafa), to(#f1f1f1));*/
 background:none;
 -ms-filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#75ffffff,endColorstr=#75ffffff);
 filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#75ffffff,endColorstr=#75ffffff);
 zoom: 1;
 background-color: rgba(255,255,255, 0.9);
 height:auto;
 padding-bottom:1px;
 -moz-box-shadow: 0 0 5px #ccc;
 border-radius:2px;
 -webkit-box-shadow: 0 0 5px#ccc;
 margin-top: 30px;
 margin-bottom: 0px;
 margin-left: auto;
 margin-right: auto;
 box-shadow: 0 0 5px #CCCCCC;
 border-top-left-radius: 10px; 
 border-top-right-radius: 10px; 
 border-bottom-left-radius: 10px; 
 border-bottom-right-radius: 10px; 
}
#reqcontent h4{
    border-bottom: 1px solid #CCCCCC;
    font-size: 24px;
    margin-left: 20px;
    margin-top: 0px;
    padding-left: 20px;
    padding-right: 20px;
    padding-top: 20px;
    width: 265px;
}
#reqcontent h5 {
 margin-left:107px;
 margin-top:-29px;
}
h5, h4{
	    font-weight: lighter;

}
#header {
 font-family:'OptimaLTStdRoman', Optima;
 color: #333333;
 padding-left:10px;
}

form {
	color: #333333;
    font-family: 'Helvetica';
    font-size: 13px;
}

fieldset {
	border:none;
        padding-top: 10px;
}

textarea {
	border:1px solid #cccccc;
	-moz-border-radius:5px;
	padding:5px;
	font-family: Optima;
	color:#888888;
	font-size:12px;
}


#top input, #top select {
	float: right;
        width: 185px;
        margin-right: 20px;
}

#top input, #equipmentCheckList input[type=text] {
	border:1px solid #cccccc;
	height:20px;
	-moz-border-radius:5px;
	border-radius:5px;
        padding-left: 10px;
}

#top select {
    text-align: center;
}

#top label {
    float:left;
    display: block;
    padding-top: 5px;
}

#top ul.column {
    padding-left: 10px;
    padding-right: 10px;
}

#top-left {
	float:left;
	padding-left:15px;
	margin-left:15px;
	width:350px;
	height:190px;
	background:  -moz-linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(255, 255, 255, 0) 75%) repeat scroll 0 0 transparent;
	background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0 ,0 ,0 ,0.1)), color-stop(75%,rgba(255,255,255,0)));
	-moz-border-radius:5px;
	border-radius:5px;
}

#top ul li {
    list-style: none outside none;
    margin-bottom: 15px;
    float: left;
    width: 330px;
}

#top #checkoutdate, #top #checkindate{
    float: left;
    margin-left: 31px;
    padding-left: 15px;
    width: 150px;
}

#top-left #checkouttime, #top-left #timeofdaycheckout, #top-right #checkintime, #top-right #timeofdaycheckin {
    float: left;
    width: 70px;
}

#top #checkouttime, #top #checkintime {
	margin-left:31px;
	margin-right:10px;
}

#top-right {
	float:right;
	padding-left:15px;
	margin-right:15px;
	width:350px;
	height:190px;
	background:  -moz-linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(255, 255, 255, 0) 75%) repeat scroll 0 0 transparent;
	background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0 ,0 ,0 ,0.1)), color-stop(75%,rgba(255,255,255,0)));
	-moz-border-radius:5px;
	border-radius:5px;
}

#top-right #group {
    margin-bottom: 36px;
    margin-right: 30px;
}

#header #message {
    background-color: #FAFAFA;
    border: none;
    color: #222;
    cursor: default;
    float: right;
    font-size: 1.2em;
    font-variant: small-caps;
    font-weight: bold;
    resize: none;
    width: 375px;
    height: 60px;
    margin-top: -75px;
    margin-right: 15px;
    visibility: hidden;
}


#popup {
        clear: both;
	width:720px;
	margin-left:15px;
	background: -moz-linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(255, 255, 255, 0) 75%) repeat scroll 0 0 transparent;
	background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0 ,0 ,0 ,0.1)), color-stop(75%,rgba(255,255,255,0)));
	-moz-border-radius:5px;
	border-radius:5px;
	padding:25px;	
}

#equipmentCheckList #comment {
    height: 125px;
    resize: none;
    transition:background-color 0.25s linear 0s;
    -moz-transition:background-color 0.25s linear 0s;
    -webkit-transition:background-color 0.25s linear 0s;
    -o-transition:background-color 0.25s linear 0s;
}

#popup #submit {
	color:#EEE;
	width:100px;
	height:30px;
	-moz-border-radius:5px;
	border-radius:5px;
	background: -moz-linear-gradient(top, #94b0bc, #738892);
        background: -webkit-gradient(linear, left top, left bottom, from(#94b0bc), to(#738892));
	font-size:14px;
	border:1px solid #cccccc;
        left: 20px;
        position: relative;
        top: -55px;
        float:right;
        margin-right:80px;
        margin-top:30px;
        padding-bottom: 3px;
}

#popup #submit:hover {
	color:#FFF;
	font-size:14px;
	width:100px;
	height:30px;
	-moz-border-radius:5px;
	border-radius:5px;
	border:1px solid #cccccc;
}

#popup #submit:active {
	color:#FFF;
	font-size:14px;
	width:100px;
	height:30px;
	-moz-border-radius:5px;
	border-radius:5px;
	background: -moz-linear-gradient(top, #738892, #94b0bc);
        background: -webkit-gradient(linear, left top, left bottom, from(#738892), to(#94b0bc));
	border:1px solid #cccccc;
        padding-bottom: 5px;
}

#equipmentCheckList {
    width: 100%;
    height: 210px;
}
#equipmentCheckList ul li {
    list-style: none;
    margin-bottom: 5px;
}
#equipmentCheckList input {
    margin-right: 5px;
}
#equipmentCheckList input[type="text"] {
    float: right;
    width: 160px;
}
#equipmentCheckList label {
    vertical-align: middle;
}
#equipmentCheckList .column {
    display: inline;
    width: 165px;
    float: left;
}
#equipmentCheckList .column li{
    width: 165px;
}

#equipmentCheckList .column:first-child {
    margin-left: 0px;
}
#equipmentCheckList .column:last-child {
    width: 250px;
}

#broadcastKitOptions {
    margin-left: -24px;
    margin-top: -15px;
    display: none; 
    height: 0px;
    transition:height 0.05s linear 0s;
    -moz-transition:height 0.05s linear 0s;
    -webkit-transition:height 0.05s linear 0s;
    -o-transition:height 0.05s linear 0s;
}

#broadcastKitOptions:first-child {
    margin-top: -12px;
}
#broadcastKitOptions:last-child {
    margin-bottom: 10px;
}

#broadcastKitOptions input {
    height: 12px;
    width: 12px;
}

#studioClass, #editClass, #studioTime, #editTime {
    display: none;
}

#equipmentCheckList .column:last-child li{
    width: 250px;
}

#comment {
    margin-left: 5px;
    margin-top: 10px;
}


label {
/*	padding-top:5px;
	position:absolute;*/
}
table {
    margin-left: -5px;
    margin-top: 20px;
}
table input {
	float:right;
}
#errors {
	font-family:Optima;
        font-size: 0.9em;
	color:red;
	width:700px;
	margin-top:-30px;
	margin-left:30px;
	-moz-border-radius:5px;
	border-radius:5px;
	padding-top:1px;
	padding-left:20px;
}

#errors ul {
	margin-top:-5px;
}
#success {
	font-family:Optima;
        font-size: 0.9em;
	color:green;
	width:700px;
	margin-top:-30px;
	margin-left:30px;
	-moz-border-radius:5px;
	border-radius:5px;
	padding-top:1px;
	padding-left:20px;
}
</style>
</head>
<body>
    <div id="reqcontent">
        <div id='header'>
            <h4>I~COMM Student Media</h4>
            <h5>Equipment Request</h5>
            <textarea id="message" name="message" rows="4" cols="40" disabled><?php if($_POST['message']) { echo $_POST['message'];}?></textarea>                            
        </div>
        <?php 
        if($error) {
        echo "<div id='errors'><p>Please correct the following:</p><ul>";
        foreach($error as $each){
            echo "<li class='error'>$each</li>";
        }
        echo "</ul></div>";
        }

        if($_SESSION['success']) {
            echo "<div id='success'><p>".$_SESSION['success']."</p></div>";$_SESSION['success'] = '';
            }

        if($_GET['request'] != 1) {
        ?>
        <form method="post" action="">
            <div id="top">
                <fieldset id="top-left">
                    <ul class="column">
                        <li>
                            <label for="reporter">Name:</label>
                            <input type="text" name="reporter" id="reporter" value="<?php echo $_POST['reporter'];?>" />        
                        </li>
                        <li>
                            <label for="email">BYU-I Email:</label>
                            <input type="text" name="email" id="email" value="<?php echo $_POST['email'];?>" />
                        </li>
                        <li>
                            <label for="checkoutdate">Check-out Date:</label>
                            <input type="text" id="checkoutdate" name="checkoutdate" value="<?php echo $_POST['checkoutdate'];?>" title="Check-out Date" autocomplete="off" />
                            <div id="calendarcheckout"></div>
                        </li>
                        <li>
                            <label for="checkouttime">Check-out Time:</label> 
                            <select name="checkouttime" id="checkouttime" title="Check-out Time">
                                <option value="" <?php if($_POST['checkouttime'] == '') echo 'selected="selected"';?>></option>
                                <option value="1" <?php if($_POST['checkouttime'] == '1') echo 'selected="selected"';?>>1:00</option>
                                <option value="1.5" <?php if($_POST['checkouttime'] == '1.5') echo 'selected="selected"';?>>1:30</option>
                                <option value="2" <?php if($_POST['checkouttime'] == '2') echo 'selected="selected"';?>>2:00</option>
                                <option value="2.5" <?php if($_POST['checkouttime'] == '2.5') echo 'selected="selected"';?>>2:30</option>
                                <option value="3" <?php if($_POST['checkouttime'] == '3') echo 'selected="selected"';?>>3:00</option>
                                <option value="3.5" <?php if($_POST['checkouttime'] == '3.5') echo 'selected="selected"';?>>3:30</option>
                                <option value="4" <?php if($_POST['checkouttime'] == '4') echo 'selected="selected"';?>>4:00</option>
                                <option value="4.5" <?php if($_POST['checkouttime'] == '4.5') echo 'selected="selected"';?>>4:30</option>
                                <option value="5" <?php if($_POST['checkouttime'] == '5') echo 'selected="selected"';?>>5:00</option>
                                <option value="5.5" <?php if($_POST['checkouttime'] == '5.5') echo 'selected="selected"';?>>5:30</option>
                                <option value="6" <?php if($_POST['checkouttime'] == '6') echo 'selected="selected"';?>>6:00</option>
                                <option value="6.5" <?php if($_POST['checkouttime'] == '6.5') echo 'selected="selected"';?>>6:30</option>
                                <option value="7" <?php if($_POST['checkouttime'] == '7') echo 'selected="selected"';?>>7:00</option>
                                <option value="7.5" <?php if($_POST['checkouttime'] == '7.5') echo 'selected="selected"';?>>7:30</option>
                                <option value="8" <?php if($_POST['checkouttime'] == '8') echo 'selected="selected"';?>>8:00</option>
                                <option value="8.5" <?php if($_POST['checkouttime'] == '8.5') echo 'selected="selected"';?>>8:30</option>
                                <option value="9" <?php if($_POST['checkouttime'] == '9') echo 'selected="selected"';?>>9:00</option>
                                <option value="9.5" <?php if($_POST['checkouttime'] == '9.5') echo 'selected="selected"';?>>9:30</option>
                                <option value="10" <?php if($_POST['checkouttime'] == '10') echo 'selected="selected"';?>>10:00</option>
                                <option value="10.5" <?php if($_POST['checkouttime'] == '10.5') echo 'selected="selected"';?>>10:30</option>
                                <option value="11" <?php if($_POST['checkouttime'] == '11') echo 'selected="selected"';?>>11:00</option>
                                <option value="11.5" <?php if($_POST['checkouttime'] == '11.5') echo 'selected="selected"';?>>11:30</option>
                                <option value="12" <?php if($_POST['checkouttime'] == '12') echo 'selected="selected"';?>>12:00</option>
                                <option value="12.5" <?php if($_POST['checkouttime'] == '12.5') echo 'selected="selected"';?>>12:30</option>
                            </select>
                            <select name="timeofdaycheckout" id="timeofdaycheckout" title="Check-out AM/PM">
                                <option value="" <?php if($_POST['timeofdaycheckout'] == '') echo 'selected="selected"';?>></option>
                                <option value="AM" <?php if($_POST['timeofdaycheckout'] == 'AM') echo 'selected="selected"';?>>AM</option>
                                <option value="PM" <?php if($_POST['timeofdaycheckout'] == 'PM') echo 'selected="selected"';?>>PM</option>
                            </select>
                        </li>
                    </ul>
                </fieldset>
                <fieldset id="top-right">
                    <ul class="column">
                        <li>
                            <label for="group">Group:</label>
                            <select name="group" id="group">
                                <option value="" <?php if($_POST['group'] == '') echo 'selected="selected"';?>></option>
                                <?php
                                $oldGroupValues = array("Inews", "Rixida", "Scroll", "Alloy", "Web", "Genesis", "Sales", "Class (Specify)");
                                if(in_array($_POST['group'], $oldGroupValues)){        
                                ?>
                                <option value="Inews" <?php if($_POST['group'] == 'Inews') echo 'selected="selected"';?>>&nbsp;I~News</option>
                                <option value="Rixida" <?php if($_POST['group'] == 'Rixida') echo 'selected="selected"';?>>&nbsp;Rixida</option>
                                <option value="Scroll" <?php if($_POST['group'] == 'Scroll') echo 'selected="selected"';?>>&nbsp;Scroll</option>
                                <option value="Alloy" <?php if($_POST['group'] == 'Alloy') echo 'selected="selected"';?>>&nbsp;Alloy</option>
                                <option value="Web" <?php if($_POST['group'] == 'Web') echo 'selected="selected"';?>>&nbsp;Web</option>
                                <option value="Genesis" <?php if($_POST['group'] == 'Genesis') echo 'selected="selected"';?>>&nbsp;Genesis</option>
                                <option value="Sales" <?php if($_POST['group'] == 'Sales') echo 'selected="selected"';?>>&nbsp;Sales</option>
                                <option value="Class (Specify)" <?php if($_POST['group'] == 'Class (Specify)') echo 'selected="selected"';?>>&nbsp;Class (Specify)</option>
                                <?php 
                                    } else { 
                                ?>
                                <option value="Scroll Photo" <?php if($_POST['group'] == 'Scroll Photo') echo 'selected="selected"';?>>&nbsp;Scroll Photo</option>
                                <option value="Scroll Digital" <?php if($_POST['group'] == 'Scroll Digital') echo 'selected="selected"';?>>&nbsp;Scroll Digital</option>
                                <option value="I~Comm Video Productions" <?php if($_POST['group'] == 'I~Comm Video Productions') echo 'selected="selected"';?>>&nbsp;I~Comm Video Productions</option>
                                <option value="Comm 260" <?php if($_POST['group'] == 'Comm 260') echo 'selected="selected"';?>>&nbsp;Comm 260</option>
                                <option value="Comm 265" <?php if($_POST['group'] == 'Comm 265') echo 'selected="selected"';?>>&nbsp;Comm 265</option>
                                <option value="Comm 360" <?php if($_POST['group'] == 'Comm 360') echo 'selected="selected"';?>>&nbsp;Comm 360</option>
                                <option value="Comm 365" <?php if($_POST['group'] == 'Comm 365') echo 'selected="selected"';?>>&nbsp;Comm 365</option>
                                <option value="Other (specify in comments)" <?php if($_POST['group'] == 'Other (specify in comments)') echo 'selected="selected"';?> onclick="document.getElementById('comment').focus()">&nbsp;Other (specify in comments)</option>
                                <?php 
                                    };
                                ?>
                            </select>
                        </li>
                        <li>
                            <label for="checkindate">Check-in Date:</label>
                            <input type="text" id="checkindate" name="checkindate" value="<?php echo $_POST['checkindate'];?>" title="Check-in Date" autocomplete="off" />
                            <div id="calendarcheckin"></div>                            
                        </li>
                        <li>
                            <label for="checkintime">Check-in Time:</label>
                            <select name="checkintime" id="checkintime" title="Check-in Time">
                                <option value="" <?php if($_POST['checkintime'] == '') echo 'selected="selected"';?>></option>
                                <option value="1" <?php if($_POST['checkintime'] == '1') echo 'selected="selected"';?>>1:00</option>
                                <option value="1.5" <?php if($_POST['checkintime'] == '1.5') echo 'selected="selected"';?>>1:30</option>
                                <option value="2" <?php if($_POST['checkintime'] == '2') echo 'selected="selected"';?>>2:00</option>
                                <option value="2.5" <?php if($_POST['checkintime'] == '2.5') echo 'selected="selected"';?>>2:30</option>
                                <option value="3" <?php if($_POST['checkintime'] == '3') echo 'selected="selected"';?>>3:00</option>
                                <option value="3.5" <?php if($_POST['checkintime'] == '3.5') echo 'selected="selected"';?>>3:30</option>
                                <option value="4" <?php if($_POST['checkintime'] == '4') echo 'selected="selected"';?>>4:00</option>
                                <option value="4.5" <?php if($_POST['checkintime'] == '4.5') echo 'selected="selected"';?>>4:30</option>
                                <option value="5" <?php if($_POST['checkintime'] == '5') echo 'selected="selected"';?>>5:00</option>
                                <option value="5.5" <?php if($_POST['checkintime'] == '5.5') echo 'selected="selected"';?>>5:30</option>
                                <option value="6" <?php if($_POST['checkintime'] == '6') echo 'selected="selected"';?>>6:00</option>
                                <option value="6.5" <?php if($_POST['checkintime'] == '6.5') echo 'selected="selected"';?>>6:30</option>
                                <option value="7" <?php if($_POST['checkintime'] == '7') echo 'selected="selected"';?>>7:00</option>
                                <option value="7.5" <?php if($_POST['checkintime'] == '7.5') echo 'selected="selected"';?>>7:30</option>
                                <option value="8" <?php if($_POST['checkintime'] == '8') echo 'selected="selected"';?>>8:00</option>
                                <option value="8.5" <?php if($_POST['checkintime'] == '8.5') echo 'selected="selected"';?>>8:30</option>
                                <option value="9" <?php if($_POST['checkintime'] == '9') echo 'selected="selected"';?>>9:00</option>
                                <option value="9.5" <?php if($_POST['checkintime'] == '9.5') echo 'selected="selected"';?>>9:30</option>
                                <option value="10" <?php if($_POST['checkintime'] == '10') echo 'selected="selected"';?>>10:00</option>
                                <option value="10.5" <?php if($_POST['checkintime'] == '10.5') echo 'selected="selected"';?>>10:30</option>
                                <option value="11" <?php if($_POST['checkintime'] == '11') echo 'selected="selected"';?>>11:00</option>
                                <option value="11.5" <?php if($_POST['checkintime'] == '11.5') echo 'selected="selected"';?>>11:30</option>
                                <option value="12" <?php if($_POST['checkintime'] == '12') echo 'selected="selected"';?>>12:00</option>
                                <option value="12.5" <?php if($_POST['checkintime'] == '12.5') echo 'selected="selected"';?>>12:30</option>
                            </select>
                            <select name="timeofdaycheckin" id="timeofdaycheckin" title="Check-in AM/PM">
                                <option value="" <?php if($_POST['timeofdaycheckin'] == '') echo 'selected="selected"';?>></option>
                                <option value="AM" <?php if($_POST['timeofdaycheckin'] == 'AM') echo 'selected="selected"';?>>AM</option>
                                <option value="PM" <?php if($_POST['timeofdaycheckin'] == 'PM') echo 'selected="selected"';?>>PM</option>
                            </select>
                        </li>
                    </ul>
                </fieldset>
            </div>

            <div id="popup">
            <label>Select your equipment:</label>
                    <fieldset id="equipmentCheckList">
                        <ul class="column">
                            <li>
                            <input type="checkbox" name="requestcheck[]" value="Audio Kit" <?php if($_POST['requestcheck'] && in_array('Audio Kit',$_POST['requestcheck'])) echo "checked";?> /> 
                                <label>Audio Kit</label>
                            </li>
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Boom Kit" <?php if($_POST['requestcheck'] && in_array('Boom Kit',$_POST['requestcheck'])) echo "checked";?> />
                                <label>Boom Kit</label>
                            </li>
                            <li>
                                <input type="checkbox" name="broadcastKitCheckboxTrigger" id="broadcastKitCheckbox" value="" <?php if($_POST['requestcheck'] && in_array('Broadcast Kit',$_POST['requestcheck'])) echo "checked";?> onclick="toggleHiddenInputs('broadcastKitCheckbox', 'broadcastKitOptions', 38)"/>
                                <label>Broadcast Kit</label>
                            </li>
                            <li>
                                <ul id="broadcastKitOptions">
                                    <li>
                                        <input id="panasonic" type="radio" name="requestcheck[]" value="Broadcast Kit - Panasonic" <?php if($_POST['requestcheck'] && in_array('Broadcast Kit - Panasonic',$_POST['requestcheck'])) echo "checked";?> /> 
                                        <label>Panasonic</label>
                                    </li>
                                    <li>
                                        <input id="canon" type="radio" name="requestcheck[]" value="Broadcast Kit - Canon" <?php if($_POST['requestcheck'] && in_array('Broadcast Kit - Canon',$_POST['requestcheck'])) echo "checked";?> />
                                        <label>Canon</label>
                                    </li>
                                </ul>
                            </li>                
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Light Kit" <?php if($_POST['requestcheck'] && in_array('Light Kit',$_POST['requestcheck'])) echo "checked";?> onclick="focusOnComments(this)"/>
                                <label>Light Kit</label>
                            </li>
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Special Project Kit" <?php if($_POST['requestcheck'] && in_array('Special Project Kit',$_POST['requestcheck'])) echo "checked";?> />
                                <label>Special Project Kit</label>
                            </li>
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Video Productions Kit" <?php if($_POST['requestcheck'] && in_array('Video Productions Kit',$_POST['requestcheck'])) echo "checked";?> onclick="focusOnComments(this)"/>
                                <label>Video Productions Kit</label>
                            </li>
                        </ul>
                        <ul class="column">
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Flash" <?php if($_POST['requestcheck'] && in_array('Flash',$_POST['requestcheck'])) echo "checked";?> />
                                <label>Flash</label>
                            </li>
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Flip Camera" <?php if($_POST['requestcheck'] && in_array('Flip Camera',$_POST['requestcheck'])) echo "checked";?> />
                                <label>Flip Camera</label>
                            </li>
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Lens" <?php if($_POST['requestcheck'] && in_array('Lens',$_POST['requestcheck'])) echo "checked";?> onclick="focusOnComments(this)"/>
                                <label>Lens</label>
                            </li>
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Monopod" <?php if($_POST['requestcheck'] && in_array('Monopod',$_POST['requestcheck'])) echo "checked";?> />
                                <label>Monopod</label>
                            </li>
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Photo Camera" <?php if($_POST['requestcheck'] && in_array('Photo Camera',$_POST['requestcheck'])) echo "checked";?> />
                                <label>Photo Camera</label>
                            </li>
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Tripod" <?php if($_POST['requestcheck'] && in_array('Tripod',$_POST['requestcheck'])) echo "checked";?> />
                                <label>Tripod</label>
                            </li>
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Reflector" <?php if($_POST['requestcheck'] && in_array('Reflector',$_POST['requestcheck'])) echo "checked";?> />
                                <label>Reflector</label>
                            </li>
                        </ul>
                        <ul class="column">
                            <li>
                                <input type="checkbox" name="requestcheck[]" value="Other" <?php if($_POST['requestcheck'] && in_array('Other',$_POST['requestcheck'])) echo "checked";?>  onclick="focusOnComments(this)"/>
                                <label>Other</label>
                            </li>
                            <textarea id="comment" name="comment" onfocus='highlight(this)' onBlur="unhighlight(this)" rows="4" cols="40" placeholder="Insert your comment here..."><?php echo $_POST['comment'];?></textarea>
                        </ul>

                    </fieldset>
                    <input type="submit" id="submit" value="Submit" name="submitrequest" />
            </div>
        </form>
        <?php } ?>
    </div>
<script type="text/javascript">
//  var text = document.getElementById('comment');
//  text.innerHTML = 'Insert your comment here...';
  function removeText() { text.innerHTML = '';}
  function addText() { if(text.value == '') { text.innerHTML = 'Insert your comment here...';}}
  
  function highlight(textArea){
    $(textArea).css("background-color", "#D3ECF7");
    $(textArea).css("color", "#222222");
  }
  function unhighlight(textArea){
    $(textArea).css("background-color", "#FEFEFE");
    $(textArea).css("color", "#888888");
  }
  
  function toggleHiddenInputs(checkBoxId, inputGroupId, height){
      if(document.getElementById(checkBoxId).checked) {
        $('#'+inputGroupId).css('display', 'block');
        $('#'+inputGroupId).css('height', height);
        
        if(!document.getElementById('canon').checked){
            document.getElementById('panasonic').checked = true;
        }
        
     } else {
        $('#'+inputGroupId).css('display', 'none');
        $('#'+inputGroupId).css('height', '0');
      }
 }
 function focusOnComments(checkBox){
      if(checkBox.checked) {
        document.getElementById('comment').focus();
      }
 }
</script>