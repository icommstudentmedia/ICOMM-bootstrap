<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
<title>Brightcove Upload</title>
<link href="styles/style_wizard.css" rel="stylesheet" type="text/css">
<link href="uploadify/uploadify.css" type="text/css" rel="stylesheet" />

<script type="text/javascript" src="uploadify/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="uploadify/jquery.uploadify.v2.1.4.min.js"></script>

<script type="text/javascript" src="uploadify/swfobject.js"></script>

<script type="text/javascript" src="js/SmartWizard.js"></script>
<script>
$().ready(function() { $('.wiz-container').smartWizard(); }); 
</script>
<script type="text/javascript">
$(document).ready(function() {
$('#file_upload').uploadify({
  'uploader'       : 'uploadify/uploadify.swf',
  'script'         : 'uploadify/uploadify.php',
  'cancelImg'      : 'uploadify/cancel.png',
  'folder'         : 'upload',
  'auto'           : false,
  'queueSizeLimit' : 1,
  'onSelect'       : function(event,ID,fileObj) {
			  		$size=fileObj.size;
					$name = fileObj.name;
					document.videos.size.value=$size;
					document.videos.name.value=$name;
		            }
});
});
</script>

</head>
<body>
	<div id="wrapper">
    
    <div id="smartwizard" class="wiz-container">
    	<div id="logo-br"></div>
		<ul id="wizard-anchor">
			<li><a href="#wizard-1" class="ribbon-left">
          <small>Open Apace </small></a></li>
			<li><a href="#wizard-2">
          <small>Sign on</small></a></li>
			<li><a href="#wizard-3">
          <small>Move Video</small></a></li>
			<li><a href="#wizard-4">
          <small>Create XML</small></a></li>
          <li><a href="#wizard-4" class="ribbon-right">
          <small>Complete</small></a></li>
		</ul>
		<div id="wizard-body" class="wiz-body">
  			<div id="wizard-1" >
  			   <div class="wiz-content">
                <div class="br-left">
                	<h2>Open Apace Connector</h2><br />	
                	<p>Start the application Apace Connector. The icon can be found in the dock at the bottom of your screen.<br /></p>
                <p>Then proceed to log in.</p></div>
                <div class="br-right">
                <object id="flashObj" width="459" height="253" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" align="right" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,47,0"><param name="movie" value="http://c.brightcove.com/services/viewer/federated_f9?isVid=1&isUI=1" /><param name="bgcolor" value="#FFFFFF" /><param name="flashVars" value="@videoPlayer=963574530001&playerID=696754907001&playerKey=AQ~~,AAAAocwtmPk~,RTQzrMOt-UDZQH4470qVL-nnGe_o-pgd&domain=embed&dynamicStreaming=true" /><param name="base" value="http://admin.brightcove.com" /><param name="seamlesstabbing" value="false" /><param name="allowFullScreen" value="true" /><param name="swLiveConnect" value="true" /><param name="allowScriptAccess" value="always" /><embed src="http://c.brightcove.com/services/viewer/federated_f9?isVid=1&isUI=1" bgcolor="#FFFFFF" flashVars="@videoPlayer=963574530001&playerID=696754907001&playerKey=AQ~~,AAAAocwtmPk~,RTQzrMOt-UDZQH4470qVL-nnGe_o-pgd&domain=embed&dynamicStreaming=true" base="http://admin.brightcove.com" name="flashObj" width="459" height="253" seamlesstabbing="false" type="application/x-shockwave-flash" allowFullScreen="true" allowScriptAccess="always" swLiveConnect="true" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed></object>
                </div>
            </div>        
            <div class="wiz-nav">
              <input class="next btn" id="next" type="button" value="Next >" />
            </div>          
        </div>
  			<div id="wizard-2">
  			   <div class="wiz-content">
               <div class="br-left">
            		<h2>Open VIDEOS_TO_BRIGHTCOVE</h2><br />	
                	 <p>Find the drive called VIDEOS_TO_BRIGHTCOVE</p><br />
                     <p>1. Click the check mark next to it and then click "Apply"</p>
               		 <p>This will map a drive to that folder and it will appear on your desktop.</p><br />
                     <p>2. Double click on that drive to open it.</p>
                     <p>Open the folder "Brightcove Account". You will notice this folder contains three folders:"Done", "Errors" and "Priority".</p>
                </div>
                <div class="br-right">
                <object id="flashObj" width="459" height="253" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" align="right" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,47,0"><param name="movie" value="http://c.brightcove.com/services/viewer/federated_f9?isVid=1&isUI=1" /><param name="bgcolor" value="#FFFFFF" /><param name="flashVars" value="@videoPlayer=963574529001&playerID=696754907001&playerKey=AQ~~,AAAAocwtmPk~,RTQzrMOt-UDZQH4470qVL-nnGe_o-pgd&domain=embed&dynamicStreaming=true" /><param name="base" value="http://admin.brightcove.com" /><param name="seamlesstabbing" value="false" /><param name="allowFullScreen" value="true" /><param name="swLiveConnect" value="true" /><param name="allowScriptAccess" value="always" /><embed src="http://c.brightcove.com/services/viewer/federated_f9?isVid=1&isUI=1" bgcolor="#FFFFFF" flashVars="@videoPlayer=963574529001&playerID=696754907001&playerKey=AQ~~,AAAAocwtmPk~,RTQzrMOt-UDZQH4470qVL-nnGe_o-pgd&domain=embed&dynamicStreaming=true" base="http://admin.brightcove.com" name="flashObj" width="459" height="253" seamlesstabbing="false" type="application/x-shockwave-flash" allowFullScreen="true" allowScriptAccess="always" swLiveConnect="true" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed></object>
                </div>
            </div>  
            <div class="wiz-nav">
              <input class="back btn" id="back" type="button" value="< Back" />
              <input class="next btn" id="next" type="button" value="Next >" />            </div>                        
        </div>
  			<div id="wizard-3">
  			   <div class="wiz-content">
               <div class="br-left">
                <h2>Move Video File</h2><br />
                <p>4. Move you video file into the "Brightcove Account" folder. You may do this by draging and dropping or by copy and pasting, which ever you prefer.</p><br />
                <p><b>At this point you have to wait.</b> <br />Wait until your video disappears from the "Brightcove Account" folder. This means it has been successfully uploaded and has been received by Brightcove. You can confirm that it has been completed successfully by checking in the "Done" folder. If there was an error in it's transmission, it will appear in the "Errors" folder.  </p>
                <p>Remember one single 100 MB video file will take about 5 minutes to upload.</p><br /><br />
                <p>Note: Do not proceed to next step until your video file has been successfully uploaded</p>
                </div>
            	<div class="br-right">
                <object id="flashObj" width="459" height="253" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" align="right" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,47,0"><param name="movie" value="http://c.brightcove.com/services/viewer/federated_f9?isVid=1&isUI=1" /><param name="bgcolor" value="#FFFFFF" /><param name="flashVars" value="@videoPlayer=963611615001&playerID=696754907001&playerKey=AQ~~,AAAAocwtmPk~,RTQzrMOt-UDZQH4470qVL-nnGe_o-pgd&domain=embed&dynamicStreaming=true" /><param name="base" value="http://admin.brightcove.com" /><param name="seamlesstabbing" value="false" /><param name="allowFullScreen" value="true" /><param name="swLiveConnect" value="true" /><param name="allowScriptAccess" value="always" /><embed src="http://c.brightcove.com/services/viewer/federated_f9?isVid=1&isUI=1" bgcolor="#FFFFFF" flashVars="@videoPlayer=963611615001&playerID=696754907001&playerKey=AQ~~,AAAAocwtmPk~,RTQzrMOt-UDZQH4470qVL-nnGe_o-pgd&domain=embed&dynamicStreaming=true" base="http://admin.brightcove.com" name="flashObj" width="450" height="253" seamlesstabbing="false" type="application/x-shockwave-flash" allowFullScreen="true" allowScriptAccess="always" swLiveConnect="true" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed></object>
                </div>
            </div>           
            <div class="wiz-nav">
              <input class="back btn" id="back" type="button" value="< Back" />
              <input class="next btn" id="next" type="button" value="Next >" />            </div>             
        </div>
        <div id="wizard-4">
  			   <div class="wiz-content">
                   <div class="br-left">
                    <h2>Create XML Manifest File</h2><br />
                    <p>Now that you have uploaded your video to Brightcove, you now need to create an XML manifest file. </p><br />
                    <p>This file tells Brightcove about your video (i.e. title, description, etc.). You can also add tags to you video. Tags will associate your video to special categries (i.e. sports, news, events, lifestyle, etc.). This will allow the videos to be sorted and searched for according to that criteria.</p> <br />
                    <p>5. Fill out the form and click submit. </p>
                    <p>This will send your XML maifest file to Brightcove. As soon as your XML maifest file gets processed by Brightcove, they will send you an email of its success or failure. If the upload did not work please contact David Hernandez for assistance.</p>
                    </div>
               <div style="float:right;">
                <form action="make_file.php" method="POST" id="videos" enctype="multipart/form-data" name="videos">
    				<table>
        				<tr>
            				<td >Video Title:</td>
            				<td  ><div class="br-input"><input type="text" size=40 name="title" required="required" maxlength="250" size="18" onblur="if (this.value == '') {this.value = 'Max. 250 Characters';}" onfocus="if (this.value == 'Max. 250 Characters') {this.value = '';}" value="Max. 250 Characters"/></div><br /></td>
        				</tr>
        				<tr>
            				<td>Select File:</td>
            				<td><input id="file_upload" name="file_upload" type="file" /><br /></td>
        				</tr>
        				<tr>
            				<td ></td>
            				<td><input  size=40 type="hidden" name="size" required="required" /></td>
        				</tr>
        				<tr>
            				<td ></td>
            				<td ><input size=40 type="hidden" name="name" required="required" /></td>
        				</tr>
        				<tr>
            				<td >Video Description:</td>
			            	<td ><div class="br-textarea"><textarea name="des" cols=35 rows=2 required="required"></textarea></div><br /></td>
        				</tr>
                        <tr>
            				<td >Confirmation Email:</td>
            				<td  ><div class="br-input"><input type="email" size=40 name="email" id="email" required="required" /></div><br /></td>
        				</tr>
        				<tr>
            				<td >Tags :</td>
            				<td  >
                            <div class="br-tag"><input type="text" size=10 name="tag1" id="tag1" ></div>
                            <div class="br-tag"><input type="text" size=10 name="tag2" id="tag2" ></div>
                            <div class="br-tag"><input type="text" size=10 name="tag3" id="tag3" ></div><br />
                            </td>
        				</tr>
        				
    			</Table>
                </div>
            </div> <!--End Content Wizard -->          
            
            <div class="wiz-nav">
              <input class="back btn" id="back" type="button" value="< Back" />
              <input class="btn" id="next" type="submit" value="Submit" />
              </form>
            </div>             
        
    </div> <!--End Wizard 4 -->
	</div>
</div>
</body>

</html>