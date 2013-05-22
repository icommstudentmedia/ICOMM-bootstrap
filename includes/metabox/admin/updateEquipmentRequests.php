<?php

echo "<h1>BLAH!!</h1>";   

    require_once($_SERVER['DOCUMENT_ROOT']."/includes/session.php");
    @mysql_select_db("icommequipment", $con);


    // Set Filter default variables  
    $filters = array('submitted' => '1', 'approved' => '2', 'rejected' => '0', 'prepped' => '3', 'finished' => '4');
    $targetFilter = $_GET['f'];
    $currentfilter = '1';      
    $weeksToSubtract = 0;
    $queryLimit = '';  


    if(isset($_GET['f'])) {
        $currentfilter = $filters[$targetFilter];
    }

    if($currentfilter == 4) {
        $weeksToSubtract = 30;
    }else if($currentfilter == 0) {
        $weeksToSubtract = 1;
    }

    $newdate = strtotime ('-'.$weeksToSubtract.' week' , strtotime (date("Y-m-d H:i:s",time()))) ;
    $newdate = date ('Y-m-d H:i:s' , $newdate );


    if($weeksToSubtract != 0){
        $queryLimit = " AND create_date > '$newdate'";
    }

    $sqlSt = "SELECT id,create_date, name, `group`, request,check_out_date, check_in_date, comment, manageapprove FROM equipment WHERE manageapprove = '$currentfilter'".$queryLimit." ORDER BY create_date DESC;";  
?>
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
    
    <?php 
        $create_date_split = split(' ',$create_date);
        $time_to_split = split(':',$create_date_split[1]);
        $formatted_hour = converttime($time_to_split[0]);
        if(strlen($formatted_hour) == 3){
            $formatted_hour = '0'.$formatted_hour;
        }        
        $formatted_create_date = substr($create_date_split[0], 0, -5).' | '.substr($formatted_hour, 0, -2).':'.$time_to_split[1].' '.substr($formatted_hour, -2);
    ?>      
    <td class="col0"><?php echo $create_date;?></td>
    <td class="col1"><?php echo $formatted_create_date;?></td>
    <td class="col2" id="reporter<?php echo $id;?>" onclick="editCell(this,<?php echo $id;?>,28,29,'name');"><?php echo $name;?></td>
    <td class="col3">
    <select name="group" id="<?php echo $id;?>" onchange="editgroup(this);">
      <option value="" <?php if($group == '') echo 'selected="selected"';?>></option>
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
    
    
    <td class="col10" id="approval<?php echo $id;?>" onclick="approvalclick(<?php echo $id;?>)">
        <select name="group" id="<?php echo $id;?>" onchange="editgroup(this);">
            <option value="1" <?php if($manageapprove == 1) echo 'selected="selected"';?>>&nbsp;Submitted</option>
            <option value="2" <?php if($manageapprove == 2) echo 'selected="selected"';?>>&nbsp;Approved</option>
            <option value="0" <?php if($manageapprove == 0) echo 'selected="selected"';?>>&nbsp;Rejected</option>
            <option value="3" <?php if($manageapprove == 3) echo 'selected="selected"';?>>&nbsp;Prepped</option>
            <option value="4" <?php if($manageapprove == 4) echo 'selected="selected"';?>>&nbsp;Finished</option>
        </select>
    </td>
    
    
  </tr>
  <?php
  $count++;
  }
  ?>
  </tbody>
  