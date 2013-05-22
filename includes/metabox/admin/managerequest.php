<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="refresh" content="300" />
<title>Equipment Page</title>
<?php
//add 5.5 hours next week
//1851* to open the door
//notes
//need to make the dates on be real dates like there can only be leap day every 4 years.
//need to make the group change on the database
//need to test the requests that they are put on the database successfully
//need to make sure the table can be sorted and keep the colors correct
//make it so enter will unfocus the textboxes for times and name and escape will cancel the ajax an not change the innerHTML.
//I need to do these things: make it so pressing enter clicks the ok. Pressing escape or clicking the mouse out of the equipment request dialog. When we check items we need to mark what the height is of each row. If one has more characters then that row needs to be taller and when we change one row height then we should not change all other rows.
require_once($_SERVER['DOCUMENT_ROOT']."/includes/session.php");
  if(date("D",time()) != 'Mon')
    //get last monday
    $lastmonday = date("Y-m-d H:i:s",strtotime("last Monday"));
  else
    //get today (monday)
    $lastmonday = date("Y-m-d H:i:s",time());
  @mysql_select_db("icommequipment", $con);
  $sql = "SELECT id,create_date, name, `group`, request,check_out_date, check_in_date, comment, manageapprove FROM equipment WHERE check_in_date > '$lastmonday' ORDER BY create_date;";
  $equipment_result = @mysql_query($sql);
  if(!$equipment_result){ echo "There was a problem connecting to the database"; exit(); }
  //if($statement->execute()){
  //echo "we got in";
  //$statement->store_result(); //we need to do this before getting the number of rows
  $returnNum = @mysql_num_rows($equipment_result);
  //$statement->bind_result($id,$create_date,$name,$group,$request,$check_out_date,$check_in_date,$comment,$manageapprove);
  
  //echo "there are $returnNum rows returned<br />";
  //} else echo "there was an error";

function converttime($time){
  $timeAr = split(":",$time);
  $time = $timeAr[0];
  if($time == 0) return '12AM';
  if($time == 12) return '12PM';
  if($time < 12) return $time+0 . 'AM';
  if($time > 12) return $time-12 . 'PM';
  //we convert ot am/pm time here
  return 'err:'.$time;
}

?>
<script type="text/javascript">
function focuscomment(){
	//alert('comment');
	document.getElementById('comment').focus();
}

document.getElementById('overlay_request').onload = focuscomment;

// Preload images
arrow = new Image(29,22);
arrow.src = 'scripts/arrowdown.png';
var maxheight = 0;

//added ONBLUR, cuz without that, u would recive an error if u had not
// edited the value in the input box
// Magnus Gudmundsson
//http://www.shawnolson.net/scripts/public_smo_scripts.js

function changecss(theClass,element,value){
	//Last Updated on June 23, 2009
	//documentation for this script at
	//http://www.shawnolson.net/a/503/altering-css-class-attributes-with-javascript.html
	var cssRules;
  var added = false;
  for (var S = 0; S < document.styleSheets.length; S++){

    if (document.styleSheets[S]['rules']) {
      cssRules = 'rules';
	  } else if (document.styleSheets[S]['cssRules']) {
	    cssRules = 'cssRules';
	  } else {
	    //no rules found... browser unknown
	  }

    for (var R = 0; R < document.styleSheets[S][cssRules].length; R++){
      if (document.styleSheets[S][cssRules][R].selectorText == theClass){
        if(document.styleSheets[S][cssRules][R].style[element]){
    	    document.styleSheets[S][cssRules][R].style[element] = value;
    	    added=true;
		      break;
	      }
	    }
	  }
	  if(!added){
	    if(document.styleSheets[S].insertRule){
			  document.styleSheets[S].insertRule(theClass+' { '+element+': '+value+'; }',document.styleSheets[S][cssRules].length);
		  } else if (document.styleSheets[S].addRule) {
				document.styleSheets[S].addRule(theClass,element+': '+value+';');
			}
	  }
  }
}

var unsetcell = false;
var savedcellval = '';

function editCell (cell,id,boxsize,maxboxsize,type) {
  //make it ignore the second mouse click
  if(!unsetcell){
    unsetcell = true;
    if(document.all) {
      savedcellval = cell.innerText;
      
      cell.innerHTML =
        '<input ' +
        ' id="editCell"' +
        ' onclick="event.cancelBubble = true;"' + 
        ' onchange="setCell(this.parentElement, this.value)" ' +
        ' onblur="setCell(this.parentElement, this.value)" ' +
        ' value="' + cell.innerText + '"' +
        ' size="' + cell.innerText.length + '"' +
        ' />';
        document.all.editCell.focus();
    }
    else if (document.getElementById) {
      savedcellval = cell.firstChild.nodeValue;
      var input = document.createElement('input');
      input.setAttribute('value', cell.firstChild.nodeValue);
      input.setAttribute('size', boxsize);
      input.setAttribute('maxlength', maxboxsize);
      input.onkeydown = function (evt) { 
        var status = editkeypress(evt);
        if(status && status == 27) escapekey(input);
        if(status && status == 13) setCell(this, id,type); 
      };
      input.onblur = function (evt) { setCell(this, id,type);};
      cell.replaceChild(input, cell.firstChild);
      input.focus();
    }
  }
}


function escapekey(cell){
  unsetcell = false;
  cell.parentNode.innerHTML = savedcellval;
}

function editkeypress(e){
  var keynum;
  var keychar;
  
  if(window.event){ // IE
    keynum = e.keyCode;
  }
  else if(e.which){ // Netscape/Firefox/Opera
    keynum = e.which;
  }
  //keychar = String.fromCharCode(keynum);
  //escape is 27
  //enter is 13
  
  if(keynum == 27) return keynum;
  if(keynum == 13) return keynum;
  //alert (keynum);
  return false;
}


function setCell(cell,id,type){
  var thetime;
  var thedate;
  var time;
  var error;
  
  //make it possible to click back again on the cell
  unsetcell = false;
  var finalval = cell.value;
  //change the format into 2009-08-21 14:00:00
  if(cell.parentNode.className.indexOf('col5') != -1){
    checkDateArr = cell.value.split('-');
    thedate = checkDateArr[2]+'-'+checkDateArr[0]+'-'+checkDateArr[1];
    thetime = document.getElementById('checkouttime'+id).innerHTML;
  }
  if(cell.parentNode.className.indexOf('col6') != -1){
    checkDateArr = document.getElementById('checkoutdate'+id).innerHTML.split('-');
    thedate = checkDateArr[2]+'-'+checkDateArr[0]+'-'+checkDateArr[1];
    thetime = cell.value;
  }
  
  if(cell.parentNode.className.indexOf('col7') != -1){
    checkDateArr = cell.value.split('-');
    thedate = checkDateArr[2]+'-'+checkDateArr[0]+'-'+checkDateArr[1];
    thetime = document.getElementById('checkintime'+id).innerHTML;
  }
  if(cell.parentNode.className.indexOf('col8') != -1){
    checkDateArr = document.getElementById('checkindate'+id).innerHTML.split('-');
    thedate = checkDateArr[2]+'-'+checkDateArr[0]+'-'+checkDateArr[1];
    thetime = cell.value;
  } 
  
  //makes it so the cell doesn't disappear
  if(cell.value == '') cell.value = '&nbsp;';
  if(thetime){
    thetime = trim(thetime.toUpperCase());
    var ampm = thetime.substr(-2);
    thetime = thetime.substring(0,thetime.indexOf(ampm));
    if(parseInt(thetime) > 12 || parseInt(thetime) < 1) error = 'Please use numbers between 1 and 12 for the time';
    alert(thetime);
    if(ampm == 'AM' ){
      if(thetime == 12)
        time = 0
      else 
        time = thetime;
    } else if(ampm == 'PM'){
      if(thetime == 12)
        time = 12
      else 
        time = 12+parseInt(thetime);
    } else error = 'Use only AM or PM after the time'; 
    finalval = thedate+' '+time+':00:00';
    //convert the time to military. If there are any errors like no AM PM then make error popup and then the error will make the cell cancel any changes and alert the error to the user
  }
  
  if(error > '') {
    cell.parentNode.innerHTML = savedcellval; 
    alert('error: '+error);
  }
  else {alert(finalval);
    makeHttpRequest('scripts/processEquipmentRequestAJAX.php?editcell='+finalval+'&id='+id+'&type='+type, debug);
    cell.parentNode.innerHTML = cell.value;
  }
}

function editgroup(cell){
  id = cell.id;
  groupval = cell.value;
  makeHttpRequest('scripts/processEquipmentRequestAJAX.php?editcell='+groupval+'&id='+id+'&type=group', debug);
}


function openrequest(cell){
  document.getElementById('overlay_request').style.display = 'block';
  //make escape run cancel button
  document.onkeydown = function (evt) {
    var status = editkeypress(evt);
    if(status && status == 27) clickcancel();
  };
  document.getElementById('overlay_request').onclick = function (evt) {
    clickok();
  };
  var anElement = 'requestrow'+cell;
  var ytote = document.getElementById(anElement).offsetTop;
  var xtote = document.getElementById(anElement).offsetLeft;
  //alert(ytote + ' ' + xtote);
  while((anElement = anElement.offsetParent)!=null){
    ytote += document.getElementById(anElement).offsetTop;
    xtote += document.getElementById(anElement).offsetLeft;
  }
  //alert(ytote + ' ' + xtote);
  var copy = document.getElementById('popup');
  
  //move the request div to the cell that has been clicked on.
  copy.style.top = (ytote+43)+'px';
  copy.style.left = (xtote-242)+'px';
  copy.style.display='block';
  
  request = document.getElementById('requestdata'+cell).innerHTML;
  
  //alert(request);
  var requestAr = request.split(',');
  //alert(requestAr[0]);
  var allMyChecks = document.getElementsByName('requestcheck');
  //first clear all the checkboxes
  for(i=0;i < allMyChecks.length;i++){
    allMyChecks[i].checked = false;
  }
  //alert('all checks are not checked');
  //then only clear the ones that are in the array
  for(i=0;i < allMyChecks.length;i++){
    for(j=0;j < requestAr.length; j++){
      if(requestAr[j].search(allMyChecks[i].value)!= -1){
        allMyChecks[i].checked = true;
        //alert(allMyChecks[i].value);
      }
    }
  }
  //set the value of the hidden input so we remember what cell was clicked on
  document.getElementById('currentrequestid').value = cell;
}

function clickok(){
  var datahtml = '';
  var count = 0;
  allMyChecks = document.getElementsByName('requestcheck');
  for(i=0;i < allMyChecks.length;i++){
    if(allMyChecks[i].checked == true){
      datahtml += allMyChecks[i].value + ',';
      //alert(allMyChecks[i].value);
    }
  }
  datahtml = datahtml.substring(0, datahtml.length-1);
  var requestid = document.getElementById('currentrequestid').value;
  //we save the user selections so we can get them later if the cell is clicked again
  document.getElementById('requestdata'+requestid).innerHTML = datahtml;
  if(datahtml.length > 30){
    document.getElementById('requestshow'+requestid).innerHTML = datahtml.substr(0,29)+'...';
  } else 
    document.getElementById('requestshow'+requestid).innerHTML = datahtml;
  
  //do the ajax request to change the value of the checks in the database
  makeHttpRequest('scripts/processEquipmentRequestAJAX.php?request='+datahtml+'&id='+requestid);
  
  //alert('hide the request div');
  document.getElementById('overlay_request').style.display = 'none';
  document.getElementById('popup').style.display='none';
  document.onkeydown = null;
  document.getElementById('overlay_request').onclick = null;
}

function clickcancel(){
  document.getElementById('popup').style.display='none';
  document.getElementById('overlay_request').style.display = 'none';
  document.onkeydown = null;
  document.getElementById('overlay_request').onclick = null;
}

function approvalclick(id){
  if(document.getElementById('approval'+id).innerHTML == '<img src="scripts/check.png">'){
    document.getElementById('approval'+id).innerHTML = '&nbsp;';
  }
  else {
    document.getElementById('approval'+id).innerHTML = '<img src="scripts/check.png" />'; 
  }
  makeHttpRequest('scripts/processEquipmentRequestAJAX.php?approval=change&id='+id);
}

function debug(text){
  if(text) alert(text);
  //document.getElementById('debug').innerHTML = text;
}


  //the whole page is populated from the database at the beginning. Every second as described below it will check the database to see if there has been a cell updated from another computer. The request div will be moving from one row to another so we need to clear the checks and get the currect boxes that should be checked. we will do split(' ;','Tripod; Camera'); split(' ;',cell.value);  then go through a loop and change the check to on if it is in the array and off if it is not found in the array. In the loop each time around we will go through all the inputs in the cell. we need to be able to check the value and check against the array. After we check all of them then go and show the div where it goes on the screen. 
  //clicking the cancel button does a hide function to hide the div. 
  //after clicking the ok button a checksave function sends the values of all the checks to the database. And it updates the current rowid and cell in the updated table. And there should be 12 records in the database (for this rowid) with a 1 or a 0 for each. that way we don't check which ones are in there allready.
  
  //we will update all 12 checks. when we make a new row, part of it will be inserting a new set of 12 checks in the database that is 0 for all.
  //we will have a java timer running that fires once every second and checks the database to see if the updated table has a different row and cell that was updated last. We will use replaceChild to replace the value of only that cell when we see that is has changed. Since the javascript ajax call is running seperatly than the ajax when we update the cells We need  to make sure that we are not reupdating the database because we see it has changed. So there will be a javascript variable that stores the current rowid and cell every second. when we make an update to the database in ajax we will also update the current variable so we don't reupdate the next second.

//some things that need to be improved. We need a better looking dropdown box. maybe jquery. We also might need jquery to make a nice lightbox type div showup when editing a comment to let it be larger when editing it. Or at least an area text box. We also need a date picker. We want to use an access short cut key to cancel the div checkbox on escape button. Also make it so clicking off the div cancels it. Keep 10 empty rows below to use. And check if another needs to be added as information is changed in the fields. We might want to enable sorting the rows using javascript. We need to find a javascript timer that makes a function fire every second.

//I will have a server script running all the time with the cron job on dream host. I will have it always checking the database to see which row has been updated. We don't want it to be doing any database queries unless it sees the database file change its modified date. We check it every second. When it changes the mod date then it will see what the last row in a real file says for update_log_id and it will record into the file all the rows that it has seen added for the change. the info it gets will be like this. row,col,info  134,5,'here is the comment that was added' I think this will make it so we don't have to do any calls from the database. We will only be doing updates to the database from the clients when we just changed a field in the page. But the clients will check the flat file every second to see the modified date and if it has been modified then it will get its contents and take the info that is in there and put it in the correct rows and columns in the table like divs. 

//this is for the icomm page more specifically. we will cache the data feeds in files. Each time someone calls a feed and it is more than one hour old we will call an external php process to run which will allow the ser to imediatly go somewhere else and the php process will still go out and update the feed. It takes too long to update the feed while the user is there (10 seconds for one and there are 5-6 feeds on a page sometimes.) So we will show the user the cached files while the php process updates the feed. It is most likly not changed so we don't need to show them the changes imediatly. We will show them with in the hour of the changes this way. We don't control the cms code or I would update the chached file as part of updating process. As the php process parses the xml feed it saves the comma seperated list in a temp file named after the feed type. There will be 56 of them. Then after it is done writing to the temp file it will rename the file to the name of the file that the website reads from. We don't need the database in this process since all the info come from the feeds. Can I also put the info into a database instead of a cache file? That was just to decrease the chance of running into a problem of too many connections to the database. We will have more of that problem with the equipment request page since we want it to be upto date every second.

//for the equipment request page how can we use cached files to get the info instead of from the database every second. We will update to the database but when an update happens we will save it to the cache as well as the database update log and the data table. We want to make it so the database is only updating the file when it is being used. We can check if the file has been updated in the last minute and if it hasn't then start the php update file process which will run for 1 hour once per second. Then after it stops then if someone changes something then the php checks to see if the php file is running by checking the last mod time. It should have been updated in the last 10 seconds or it is inactive since it updates every second. Then it will start the file if the mod time is older because that would mean the process has ended. 

//I just figured out how to make the browsers stay synced without running a process all the time. Just every time something is changed update the database and then update a textfile showing the id,row,col,data  If it is the comment it will only put a few like 20 characters into the table and the file. but when someone clicks on the comment it will get the actual comment in the database. And when someone changes the comment then it will change the textfile and so all browsers that are open will have a javascript function running in the background that checks if there is a change to the textfile and if there is a change in the timestamp of the textfile then go and get the data from it and only use the last part of it starting with the id that was changed last. The id is the same as the update table. We need to figure out exactly how the data is archived in the database so that it doesn't take up too much space. When data for the table is fetched it only gets data that is 2 weeks in the past to 1 month in the future or maybe more. Should we have an option at the top of the screen that lets the user filter by only this weeks data or by all future including this week. Another for only last 3 weeks. And one to select a date range.



</script>
<style text="text/css">

img{
  border:0px;
}

a{
  text-decoration:none;
}

.row a:link,.row a:visited{
  color:black;
}

table{
  border-collapse:collapse;
}


#mainTable select {
  float:right;
  font-size:11px;
  font-family:  arial, verdana, sans-serif;
  text-align: center;
}

#mainTable option {
    text-align: left;
}

#mainTable input{
  border:0px;
  font-size:11px;
  font-family:  arial, verdana, sans-serif;
  margin-left:-1px;
}

.clear{
  clear:both;
}

.col1{
  width:105px;
}

.col2{
  width:168px;
}

.col3{
  width:100px;
}


.col4{
  width:191px;
}

.col5{
  width:102px;
}


.col6{
  width:107px;
}

.col7{
  width:98px;
}

.col8{
  width:102px;
}

.row .col8 img{
  margin-left:23px;
}

.col9{
  width:172px;
  padding:0 !important;
}

.col10{
  width:54px;
}



td.col4:hover div.arrow{
  background: #4Fb3dd url(scripts/arrowdown.png) no-repeat;
  background-position:bottom;
  border:1px solid black !important;
}

.arrow, .buttonmouseout, .buttonmouseover{
  background: #8FD5FF url(scripts/arrowdown.png) no-repeat;
  background-position:bottom;
  border:1px solid grey !important;
  float:right;
  width:12px;
  height:14px !important;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;  
}

div.arrow:hover, .buttonmouseover{
  background: #4Fb3dd url(scripts/arrowdown.png) no-repeat;
  background-position:center;
  border:1px solid black !important;
}

.therequest{
  float:left;
  height:10px !important;
  width:174px;
  border:0px !important;
  white-space: nowrap;
  font-family:  arial, verdana, sans-serif;
}

#savecomment{
  background:#E8E8E8;
  text-align:right;
  padding:3px;
}

.thickbox{
  padding:4px 0 3px 3px;
  display:block;
}

#overlay_request{
  background:#000000;
  opacity:0.8;
  height:100%;
  width:100%;
  z-index:99;
  position:fixed;
  top:0;
  left:0;
  display:none;
}

#mainTable{
  width:1279px;
  padding-left:2px;
}

thead th{
  padding: 3px 0;
  border-left: 1px solid white;
	font-family: "Times New Roman";
	font-weight: bold;
	font-size: 13px;
}

thead,thead a:link,thead a:visited{
	background: #53525A;
	color: #ddd;
}

#mainTable td {
	font-family:  arial, verdana, sans-serif;
	font-size: 11px;
	padding: 5px 3px 3px 10px;
	border: 1px solid gray;
	padding: 2px 4px;
}

tr:nth-child(even) td:nth-child(1), tr:nth-child(even) td:nth-child(3), tr:nth-child(even) td:nth-child(4), tr:nth-child(even) td:nth-child(9) {
	background: #d0d9d8;
}

tr:nth-child(odd) td:nth-child(1), tr:nth-child(odd) td:nth-child(3), tr:nth-child(odd) td:nth-child(4), tr:nth-child(odd) td:nth-child(9) {
	background: #a0b3b8;
}

tr:nth-child(even) td:nth-child(2), tr:nth-child(even) td:nth-child(5), tr:nth-child(even) td:nth-child(6), tr:nth-child(even) td:nth-child(7), tr:nth-child(even) td:nth-child(8), tr:nth-child(even) td:nth-child(10) {
	background: #e7f1f0;
}

tr:nth-child(odd) td:nth-child(2), tr:nth-child(odd) td:nth-child(5), tr:nth-child(odd) td:nth-child(6), tr:nth-child(odd) td:nth-child(7), tr:nth-child(odd) td:nth-child(8), tr:nth-child(odd) td:nth-child(10) {
	background: #B6CCD1;
}

tr:nth-child(even):hover td:nth-child(1), tr:nth-child(odd):hover td:nth-child(1), tr:nth-child(even):hover td:nth-child(2), tr:nth-child(odd):hover td:nth-child(2), tr:nth-child(even):hover td:nth-child(3), tr:nth-child(odd):hover td:nth-child(3), tr:nth-child(even):hover td:nth-child(4), tr:nth-child(odd):hover td:nth-child(4), tr:nth-child(even):hover td:nth-child(5), tr:nth-child(odd):hover td:nth-child(5), tr:nth-child(even):hover td:nth-child(6), tr:nth-child(odd):hover td:nth-child(6), tr:nth-child(even):hover td:nth-child(7), tr:nth-child(odd):hover td:nth-child(7), tr:nth-child(even):hover td:nth-child(8), tr:nth-child(odd):hover td:nth-child(8), tr:nth-child(even):hover td:nth-child(9), tr:nth-child(odd):hover td:nth-child(9), tr:nth-child(even):hover td:nth-child(10), tr:nth-child(odd):hover td:nth-child(10) {
	background: #608CA5;
	color: white;
}

.outerBorderL {
	border-left: 1px solid gray;
}

.outerBorderR {
	border-right: 1px solid gray;
}
 
.center {
	text-align: center;
}

/* =========================
	popup
========================= */

#popup {
	background: #506158;
	width: 240px;
	min-height: 200px;
	border: 1px solid gray;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	display:none;
	position:absolute;
	top:0;
	left:0;
	z-index:100;
}

#popup td {
	color: white;
	font-family:  arial, verdana, sans-serif;
	font-size: 11px;
	padding: 4px 0px 4px 4px;
}

#divider {
	border-right: 1px solid gray;
}

#button-row {
	border-top: 2px solid gray;
	padding: 8px 0px 8px 20px;
	background: #506158;
}

#button-row input {
  -moz-border-radius: 3px; 
  -webkit-border-radius: 3px;
}

#popup tr:nth-child(even) td:nth-child(1), #popup tr:nth-child(even) td:nth-child(2), #popup tr:nth-child(odd) td:nth-child(3), #popup tr:nth-child(odd) td:nth-child(4) {
	background: #576a60;
}

#popup tr:nth-child(odd) td:nth-child(1), #popup tr:nth-child(odd) td:nth-child(2), #popup tr:nth-child(even) td:nth-child(3), #popup tr:nth-child(even) td:nth-child(4) {
	background: #35403a;
}

col#one, col#three {
	width: 20px;
}

col#two {
	width: 100px;
	border-right: 1px solid gray;
}

col#four {
	width: 100px;
}

tfoot td {
	border: none;
}

#tfoot {
	height:24px; 
	background:#53525A;
	width:1279px;
}



</style> 
<script type="text/javascript" src="scripts/jquery-latest.js"></script>
<script type="text/javascript" src="scripts/thickbox.js"></script>
<script type="text/javascript" src="scripts/ajax.js"></script>
<script type="text/javascript" src="scripts/jquery.tablesorter.min.js"></script>

<script type="text/javascript">
$(function() {
		$("#mainTable").tablesorter({sortList: [[0,0]], headers: { 2:{sorter: false}, 3:{sorter: false}, 8:{sorter: false}}});
});
</script>
<link rel="stylesheet" href="scripts/thickbox.css" type="text/css" media="screen" />
</head>
<body>
<div id="overlay_request"></div>
<h3><a href="makereq.php" target="blank">Make a new request</a></h3>
<br />


<table id="mainTable" class="tablesorter">

  <thead>
  <tr id="rowheader">
    <th class="col1 outerBorderL">Date Created</th><th class="col2">Reporter Name</th><th class="col3 unsortable">Group</th><th class="col4 unsortable">Requested Equipment</th><th class="col5">Checkout Date</th><th class="col6">Checkout Time</th><th class="col7">Checkin Date</th><th class="col8">Checkin Time</th><th class="col9 unsortable">Comment</th><th class="col10 unsortable outerBorderR">Approval</th>
  </tr>
  </thead>
  <tbody>
  <?php
  $count = 1;
  $hiddenrequests = '';
  while (list($id,$create_date,$name,$group,$request,$check_out_date,$check_in_date,$comment,$manageapprove) = @mysql_fetch_array($equipment_result)) {
    $checkoutparts = explode(" ",$check_out_date);
    $checkinparts = explode(" ",$check_in_date);
    
    $checkoutdate = $checkoutparts[0];
    $checkoutdateArr = explode("-",$checkoutdate);
    $checkoutdate = $checkoutdateArr[1] . "-" . $checkoutdateArr[2] . "-" . $checkoutdateArr[0];
    
    $checkindate = $checkinparts[0];
    $checkindateArr = explode("-",$checkindate);
    $checkindate = $checkindateArr[1] . "-" . $checkindateArr[2] . "-" . $checkindateArr[0];
    
    $checkouttime = $checkoutparts[1];
    $checkintime = $checkinparts[1];
    
    $create_date_parts = explode(" ",$create_date);
    $create_dateArr = explode("-",$create_date_parts[0]);
    $create_date = $create_dateArr[1] . "-" . $create_dateArr[2] . "-" . $create_dateArr[0] . " " . $create_date_parts[1];
    
  //$id,$slug,$name,$group,$request,$check_out_date,$check_in_date,$comment,$manageapprove,$create_date
  ?>
  <tr id="row<?php echo $count;?>" class="row">
    
    <td class="col1"><?php echo $create_date;?></td>
    <td class="col2" id="reporter<?php echo $id;?>" onclick="editCell(this,<?php echo $id;?>,28,29,'name');"><?php echo $name;?></td>
    <td class="col3">
    <select name="group" id="<?php echo $id;?>" onchange="editgroup(this);">
    <?php
      $oldGroupValues = array("Inews", "Rixida", "Scroll", "Alloy", "Web", "Genesis", "Sales", "Class (Specify)");
      if(in_array($group, $oldGroupValues)){        
    ?>
      <option value="Inews" <?php if($group == 'Inews') echo 'selected="selected"';?>>&nbsp;I~News</option>
      <option value="Rixida" <?php if($group == 'Rixida') echo 'selected="selected"';?>>&nbsp;Rixida</option>
      <option value="Scroll" <?php if($group == 'Scroll') echo 'selected="selected"';?>>&nbsp;Scroll</option>
      <option value="Alloy" <?php if($group == 'Alloy') echo 'selected="selected"';?>>&nbsp;Alloy</option>
      <option value="Web" <?php if($group == 'Web') echo 'selected="selected"';?>>&nbsp;Web</option>
      <option value="Genesis" <?php if($group == 'Genesis') echo 'selected="selected"';?>>&nbsp;Genesis</option>
      <option value="Sales" <?php if($group == 'Sales') echo 'selected="selected"';?>>&nbsp;Sales</option>
      <option value="Class (Specify)" <?php if($group == 'Class (Specify)') echo 'selected="selected"';?>>&nbsp;Class (Specify)</option>
    <?php 
        } else { 
    ?>
      <option value="Scroll Photo" <?php if($group == 'Scroll Photo') echo 'selected="selected"';?>>&nbsp;Scroll Photo</option>
      <option value="Scroll Digital" <?php if($group == 'Scroll Digital') echo 'selected="selected"';?>>&nbsp;Scroll Digital</option>
      <option value="I~Comm Video Productions" <?php if($group == 'I~Comm Video Productions') echo 'selected="selected"';?>>&nbsp;I~Comm Video Productions</option>
      <option value="Comm 260" <?php if($group == 'Comm 260') echo 'selected="selected"';?>>&nbsp;Comm 260</option>
      <option value="Comm 265" <?php if($group == 'Comm 265') echo 'selected="selected"';?>>&nbsp;Comm 265</option>
      <option value="Comm 360" <?php if($group == 'Comm 360') echo 'selected="selected"';?>>&nbsp;Comm 360</option>
      <option value="Comm 365" <?php if($group == 'Comm 365') echo 'selected="selected"';?>>&nbsp;Comm 365</option>
      <option value="Other (specify in comments)" <?php if($group == 'Other (specify in comments)') echo 'selected="selected"';?>>&nbsp;Other (specify in comments)</option>
    <?php 
        };
    ?>
    </select>
    </td>
    <td class="col4" id="requestrow<?php echo $id;?>" onclick="openrequest(<?php echo $id;?>);">
    <div class="therequest" id="requestshow<?php echo $id;?>"><?php 
    if(strlen($request) > 32)
      echo substr(trim($request),0,31) . "...";
    else 
      echo $request; //only for displaying the first part of the items requested
      ?></div><span style="display:none;" id="requestdata<?php echo $id;?>"><?php 
      echo $request; //only for storing the data to remember the check values
      ?></span><div class="arrow"></div></td>
    <td class="col5" id="checkoutdate<?php echo $id;?>" onclick="editCell(this,<?php echo $id;?>,10,10,'check_out_date');"><?php echo $checkoutdate;?></td>
    <td class="col6" id="checkouttime<?php echo $id;?>" onclick="editCell(this,<?php 
    echo $id;?>,4,4,'check_out_date');"><?php echo converttime($checkouttime);?></td>
    
    <td class="col7" id="checkindate<?php echo $id;?>" onclick="editCell(this,<?php echo $id;?>,10,10,'check_in_date');"><?php echo $checkindate;?></td>
    <td class="col8" id="checkintime<?php echo $id;?>" onclick="editCell(this,<?php 
    echo $id;?>,4,4,'check_in_date');"><?php echo converttime($checkintime);?></td>
    
    <td class="col9"><a href="scripts/getcomment.php?id=<?php echo $id;?>&height=80&width=355" title="Edit or view a comment" class="thickbox"><div id="comment<?php echo $id;?>"><?php 
    if(strlen($comment) > 28)
      echo substr(trim($comment),0,27) . "...";
    else
      echo $comment;?></div></a></td>
    <td class="col10" id="approval<?php echo $id;?>" onclick="approvalclick(<?php echo $id;?>)"><?php if($manageapprove == 1) echo '<img src="scripts/check.png" style="vertical-align: top;"
/>';else echo "&nbsp;";?></td>
  </tr>
  <?php
  $count++;
  }
  ?>
  </tbody>
</table>
<div id="tfoot"></div>

<div id="popup">
				<form name="requestform">
					<table>
						<col id="one" />
						<col id="two" />
						<col id="three" />
						<col id="four" />
						<tr>
							<td><input type="checkbox" name="requestcheck" value="Broadcast Kit" /></td>
							<td>Broadcast Kit</td>
							<td><input type="checkbox" name="requestcheck" value="Photo Camera" /></td>
							<td>Photo Camera</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="requestcheck" value="Special Project Kit" /></td>
							<td>Special Project Kit</td>
							<td><input type="checkbox" name="requestcheck" value="Flip Camera" /></td>
							<td>Flip Camera</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="requestcheck" value="Tripod" /></td>
							<td>Tripod</td>
							<td><input type="checkbox" name="requestcheck" value="Monopod" /></td>
							<td>Monopod</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="requestcheck" value="Audio Kit" /></td>
							<td>Audio Kit</td>
							<td><input type="checkbox" name="requestcheck" value="Lens" /></td>
							<td>Lens</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="requestcheck" value="Boom Kit" /></td>
							<td>Boom Kit</td>
							<td><input type="checkbox" name="requestcheck" value="Flash" /></td>
							<td>Flash</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="requestcheck" value="Light Kit" /></td>
							<td>Light Kit</td>
							<td><input type="checkbox" name="requestcheck" value="Studio" /></td>
							<td>Studio</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="requestcheck" value="Reflector" /></td>
							<td>Reflector</td>
							<td><input type="checkbox" name="requestcheck" value="Edit Bay" /></td>
							<td>Edit Bay</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="requestcheck" value="Video Camera" /></td>
							<td>Video Camera</td>
							<td><input type="checkbox" name="requestcheck" value="Hard Drive" /></td>
							<td>Hard Drive</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="requestcheck" value="Other" /></td>
							<td>Other</td>
						</tr>
					</table>
				</form>
				<div id="button-row">
          <input type="button" value="OK" onclick="clickok();" />
          <input type="button" value="Cancel" onclick="clickcancel();" />
          <input type="hidden" id="currentrequestid" value="" />
				</div>
			</div>
			</body>
</html>