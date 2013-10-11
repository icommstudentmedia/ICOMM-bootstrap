    <?php
    /**
    *   Template Name: Contact Us
    *   Date: March 09,2013
    *   @author Lyle Palagar
    **/

    get_header(); ?>

    <!-- This file contains the social media icons for the desktop version and also 
    the mobile version of the website -->
    <?php include_once "social-menu.php"; ?>

    <!-- /////////body starts//////// --> 
    <div class="container container-narrow">
        <div class="row-fluid vert-padding">
            <div class="span10">
              <h1 class="pull-left">Contact Us</h1>
          </div>
        </div>
    </div>


<div class="row-fluid">

    <div class="span7 contact">
        <hr>
        <!--   content for content us -->

        <div class="row-fluid">
            <div class="span8">

                <p> 
                    For more information on how we can assist in developing a program that 
                    will help meet your marketing needs and objectives,please contact:
                </p>
                <p>
                    <strong>Kristina Miller</strong> or <strong>John Thompson</strong> at: <br> 
                    (208) 496-3737 or icomm@byui.edu 
                </p>

                <p>
                    When e-mailing, please include the words "byuicomm.net Ad Agency Questions" in the subject line.</p>
                    Or submit your information below and an Ad Sales representative will
                    contact you. 
                </p>

            </div>
        </div>
        <!-- ////////// end ///////  -->



        <!-- form is starting -->

        <div class="container">
          <form id="contact-form" name="contact-form" method="post" action=" # ">
             <h3 class="feature-lead"> Drop us a line! </h3>
             <div class="row-fluid">
              <label for="name">Name</label>
              <input class="span3" type="text" name="name" id="name" value="" />
              <p class="formError name"></p>
          </div>
          <div class="row-fluid"> 
            <label for="email">Email</label>
            <input class="span3" type="text"  name="email" id="email" value="" />
            <p class="formError email"></p>
        </div>
        <div class="row-fluid"> 
            <label for="phone">Phone</label>
            <input class="span3" type="text"  name="phone" id="phone" value="" />
            <p class="formError phone"></p>
        </div>
        <div class="row-fluid">
            <label for="subject">Subject</label>
            <select class="span3" name="subject" id="subject">
                <option value="" selected="selected"> - Choose -</option>
                <option value="Question">Question</option>
                <option value="Business proposal">Business proposal</option>
                <option value="Advertisement">Advertising</option>
                <option value="Complaint">Complaint</option>
            </select>
            <p class="formError subject"></p>
        </div>
        <div class="row-fluid">
            <label for="message">Message</label>
            <textarea class="span3" name="message" id="message" cols="50" rows="6"></textarea>
            <p class="formError message"></p>
        </div>
        <div class="row-fluid important">
            <label for="required">Don't fill this out</label>
            <input class="span3" id="required-field" class="required-field" name="required"/>
        </div>
        <div class="row-fluid buttons">
            <input class="btn" type="submit" name="button" id="button" value="Submit" />
            <input class="btn" type="reset" name="button2" id="button2" value="Reset" />
        </div>
    </form>
</div>


</div> <!-- end of span8  -->



<!--right - side bar  -->
<div class="span5">
    <hr>
    <div class="sidebar_container">
        <!-- Google Map  code -->
        <h3 class="feature-lead"> Our Location</h3>
        <div id="map_canvas" style="width:300px; height:350px"></div>
        <h4>Contact Info</h4>
        <hr class="no-margin">
        <h5 class="no-margin">Scroll and I~Comm Office</h5>
        <ul class="nav ul-nostyle">
            <li class="rightborder"><a href="mailto:icomm@byui.edu">icomm@byui.edu</a></li>
            <li class="rightborder">208-496-3737</li>
        </ul>
        <h5 class="no-margin">Scroll Advertising Sales</h5>
        <ul class="nav ul-nostyle">
            <li class="rightborder"><a href="mailto:scrollads@byui.edu">scrollads@byui.edu</li>
            <li class="rightborder">208-496-3730</li>
        </ul>
        <h5 class="no-margin">Brigham Young University - Idaho</h5>
        <ul class="nav ul-nostyle">
            <li class="rightborder">Spori 114A</li>
            <li class="rightborder">525 South Center Street</li>
            <li class="rightborder">Rexburg, ID</li>
            <li class="rightborder">83460-0115</li>
        </ul>
        <!-- End -->

        <!-- Replace script code  -->
    </script>
    <script type="text/javascript"
    src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBls400dztaixmQbXYQnfD-GKEEhBaygMo&sensor=true">
    </script>
    <script>
    var marker;
    var map;

    function initialize() {
        var myLatlng = new google.maps.LatLng(43.820924,-111.78266);
        var mapOptions = {
            zoom: 15,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }

        var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

        var contentString = '<div id="content">'+
        '<div id="siteNotice">'+
        '</div>'+
        '<h2 id="firstHeading" class="firstHeading">iComm Student Media</h2>'+
        '<div class="sporibld"></div>'+
        '<div id="bodyContent">'+
        '<p>Spori 114A </p><p>525 South Center Street </p><p>Rexburg, ID 83460-0115</p>'+
        '<p>Website: <a href="http://www.byuicomm.net">'+
        'www.byuicomm.net</a></p>'+
        '</div>'+
        '</div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString,
        });

        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title:"iComm Student Media"
        });

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map,marker);
        });
    }
    window.onload = initialize();
    </script>

</div> <!-- end of span 3-->
</div> <!-- endo f sidebar container -->
<!-- //////end /// -->
</div> <!-- end of row-fluid -->
</div> <!-- end of container narrow -->

<!--      ended       -->

<?php get_footer();?>
