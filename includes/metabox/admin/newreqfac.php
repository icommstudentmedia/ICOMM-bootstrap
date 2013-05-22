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
    $_POST['checkindate'] = $_POST['checkoutdate'];
    
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
    if(!$_POST['requestcheck']) $error[] = "Request a facility";

    if($_POST['checkoutdate'] == '') 
    $error['nodatecheckout'] = "Enter a reservation date";
    if($_POST['checkouttime'] == '') 
    $error['notimecheckout'] = "Enter a start time";
    else if(!$_POST['timeofdaycheckout']) 
    $error['ampmcheckout'] = "Enter AM or PM for start time";

    if($_POST['checkintime'] == '') 
    $error['nodatetimecheckin'] = "Enter a end time";
    else if(!$_POST['timeofdaycheckin']) 
    $error['ampmcheckin'] = "Enter AM or PM for end time";

    //make the date
    $start = strtotime($_POST['checkindate']);
    $end = strtotime($_POST['checkoutdate']);


    //after the hour is parsed then check that they both are in the right order
    //convert to military time
    $checkouttime = convertmilitarytime($_POST['checkouttime'],$_POST['timeofdaycheckout']);
    $checkintime = convertmilitarytime($_POST['checkintime'],$_POST['timeofdaycheckin']);
        
    if($_POST['checkoutdate'] == $_POST['checkindate']){
        if($checkouttime > $checkintime){
            $error[] = "The start time needs to be before the end time";
        }
        if($checkouttime == $checkintime && $checkouttime != ''){
            $error[] = "The start and end times cannot be the same";
        }
    }
    if($_POST['checkoutdate'] == date('m/d/Y')){
        $error[] = "Sorry, policy prohibits same day reservations";
    }
    
    
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

//        $checkouttimestamp = strtotime(date("m/d/Y",time()). " $convertedCheckouttime");
//        if($checkouttimestamp > strtotime(date("m/d/Y",time())) AND $checkouttimestamp < time()) $error[] = "The start time needs to be after the current half-hour today";
//        $checkintimestamp = strtotime(date("m/d/Y",time()). " $convertedCheckintime");
//        if($checkintimestamp > strtotime(date("m/d/Y",time())) AND $checkintimestamp < time()) $error[] = "The end time needs to be after the current half-hour today";
    }

//    if(date("m-d-YYYY",time()) == $_POST['checkindate']){
//        $checkintimestamp = strtotime(date("m/d/Y",time()). " $checkintime:00");
//        if($checkintimestamp > strtotime(date("m/d/Y",time())) AND $checkintimestamp < time()) $error[] = "The end time is needs to be after the current half-hour today";
//    }

    if(strtotime($_POST['checkoutdate']) < strtotime(date("m/d/Y",time()))) $error[] = "You need to pick a reservation date of tomorrow or later";


    if($_POST['checkindate'] != '' ){
        $checkinWeekday = getweekday($_POST['checkindate']);        
    }
    if($_POST['checkoutdate'] != '' ){
        $checkoutWeekday = getweekday($_POST['checkoutdate']);
    }


    // Enforce Valid Reservation times
    if($checkoutWeekday == 'Sunday'){
        if($checkouttime < 1 || $checkouttime > 17){
            $error[] = "Please change the reservation time. Sunday is closed for reservations.";           
        }
    }

    // Enforce Comments on selected items
    if(!trim($_POST['comment']) && (!(strpos($requestcheck, 'Edit Bay') === false))){
        $error[] = "Enter your class and a project description";   
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
    echo '<meta http-equiv="refresh" content="0;url=http://www.byuicomm.net/admin/makefacreq.php?request=1" />';
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

#top input, #facilityCheckList input[type=text] {
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
	height:150px;
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
    margin-left: 19px;
    padding-left: 15px;
    width: 150px;
}

#top-left #checkouttime, #top-left #timeofdaycheckout, #top-right #checkintime, #top-right #timeofdaycheckin {
    float: left;
    width: 70px;
}

#top #checkouttime, #top #checkintime {
	margin-left:63px;
	margin-right:10px;
}

#top-right {
	float:right;
	padding-left:15px;
	margin-right:15px;
	width:350px;
	height:150px;
	background:  -moz-linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(255, 255, 255, 0) 75%) repeat scroll 0 0 transparent;
	background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0 ,0 ,0 ,0.1)), color-stop(75%,rgba(255,255,255,0)));
	-moz-border-radius:5px;
	border-radius:5px;
}

#top-right #group {
    margin-right: 22px;
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

#popup .row li {
    display: inline;
    margin-right: 20px;
}

#facilityCheckList {
    width: 100%;
    height: 150px;
    position: relative;
}
#facilityCheckList ul li {
    list-style: none;
    margin-bottom: 5px;
}
#facilityCheckList input {
    margin-right: 5px;
}
#facilityCheckList input[type="text"] {
    float: right;
    width: 160px;
}
#facilityCheckList label {
    vertical-align: middle;
}
#facilityCheckList .column {
    display: inline;
    width: 165px;
    float: left;
}
#facilityCheckList .column li{
    width: 165px;
}

#facilityCheckList .column:first-child {
    margin-left: 0px;
}

#facilityCheckList .column:last-child {
    width: 250px;
}

#facilityCheckList .column:last-child li{
    width: 250px;
}

#facilityCheckList #comment {
    margin-left: 5px;
    margin-top: 10px;
    position: absolute;
    top: 50px;
    left: 50px;
    height: 65px;
    width: 400px;
    resize: none;
    transition:background-color 0.25s linear 0s;
    -moz-transition:background-color 0.25s linear 0s;
    -webkit-transition:background-color 0.25s linear 0s;
    -o-transition:background-color 0.25s linear 0s;
}

#submit {
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
        top: -187px;
}

#submit:hover {
	color:#FFF;
	font-size:14px;
	width:100px;
	height:30px;
	-moz-border-radius:5px;
	border-radius:5px;
	border:1px solid #cccccc;
}

#submit:active {
	color:#FFF;
	font-size:14px;
	width:100px;
	height:30px;
	-moz-border-radius:5px;
	border-radius:5px;
	background: -moz-linear-gradient(top, #738892, #94b0bc);
        background: -webkit-gradient(linear, left top, left bottom, from(#738892), to(#94b0bc));
	border:1px solid #cccccc;
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
            <h5>Facility Request</h5>
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
                            <label for="checkouttime">Start Time:</label> 
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
<!--                        <li>
                            <label for="checkoutdate">Check-out Date:</label>
                            <input type="text" id="checkoutdate" name="checkoutdate" value="<?php //echo $_POST['checkoutdate'];?>" title="Check-out Date" autocomplete="off" />
                            <div id="calendarcheckout"></div>
                        </li>-->
                        <li>
                            <label for="checkoutdate">Reservation Date:</label>
                            <input type="text" id="checkoutdate" name="checkoutdate" value="<?php echo $_POST['checkoutdate'];?>" title="Check-out Date" autocomplete="off" />
                            <div id="calendarcheckout"></div>                            
                        </li>
                        <li>
                            <label for="checkintime">End Time:</label>
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
            <label>Select your facility:</label>
                    <fieldset id="facilityCheckList">
                        <ul class="row">
                            <li>
                                <input type="radio" name="requestcheck[]" id="editBayCheckbox" value="Edit Bay" <?php if($_POST['requestcheck'] && in_array('Edit Bay',$_POST['requestcheck'])) echo "checked";?> onclick="focusOnComments(this)"/>
                                <label>Edit Bay</label>
                            </li>
                            <li>
                                <input type="radio" name="requestcheck[]" id="studioCheckbox" value="Studio" <?php if($_POST['requestcheck'] && in_array('Studio',$_POST['requestcheck'])) echo "checked";?>/>
                                <label>Studio</label>
                            </li>
                            <textarea id="comment" name="comment" onfocus='highlight(this)' onBlur="unhighlight(this)" rows="4" cols="40" placeholder="Specify your class and project here..."><?php echo $_POST['comment'];?></textarea>
                        </ul>

                    </fieldset>

            </div>
            <input type="submit" id="submit" value="Submit" name="submitrequest"  style="float:right; margin-right:80px;margin-top:20px;"/>
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
 function toggleRoomItem(checkBoxId){
      if(document.getElementById(checkBoxId).checked) {
          if(checkBoxId == "studioCheckbox") {
            $('#studioClass').css('display', 'block');
            $('#studioTime').css('display', 'block');
            document.getElementById('studioClass').focus();
            document.getElementById('studioTime').focus();
          }
          else if(checkBoxId == "editBayCheckbox") {
            $('#editClass').css('display', 'block');
            $('#editTime').css('display', 'block');
            document.getElementById('editClass').focus();
            document.getElementById('editTime').focus();
          }
      } else {
          if(checkBoxId == "studioCheckbox") {
            $('#studioClass').css('display', 'none');
            $('#studioClass').val('');
            $('#studioTime').css('display', 'none');
            $('#studioTime').val('');
          }
          else if(checkBoxId == "editBayCheckbox") {
            $('#editClass').css('display', 'none');
            $('#editClass').val('');
            $('#editTime').css('display', 'none');
            $('#editTime').val('');
          }
      }
 }

</script>