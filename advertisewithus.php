<?php
    /**
    *   Template Name: Advertise with Us
    *   Date: April 05,2013
    *   @author Isaac Andrade
    **/
    get_header();
    ?>


    <!-- This file contains the social media icons for the desktop version and also 
      the mobile version of the website -->
      <?php include_once "social-menu.php"; ?>
      
    <!-- Title Block -->
    <div class="container container-narrow">
      <div class="row-fluid vert-padding">
        <div class="span10">
          <h2 class="feature">Advertise with Us</h2>
        </div> 
      </div>

      <hr/>
      <!-- End of Title Block -->

      <!-- Main Content -->
      <div class="row-fluid">

        <!-- Page Aside -->
        <aside class="span4">
          <img src="/wp-content/themes/icomm-bootstrap/img/placeholder_352_300.jpg" alt="Advertise With Us Image">

          <h3>I~Comm Student Media</h3>
          <h4>Contact Information</h4>
          <hr>
          <p><strong>Scroll Advertising Sales</strong></p>
          <ul class="ul-nostyle">
            <li><a href="mailto:scrollads@byui.edu">scrollads@byui.edu</a></li> <!-- This enables the user to send an email by clicking on it. -->
            <li>Office: <span class="office-number">208-496-3730</span></li>
            <li>Fax: <span class="fax-number">208-496-5411</span></li>
          </ul>   
        </aside>

        <!-- Blank div to divide the two others -->
       <!--  <div class="span1"></div> -->

        <div class="span6">
          <ul class="ul-nostyle">
            <li>
              <h3>Advertise with I~Comm</h3>
              <p>We can promote your business by advertising with us.
                You can submit your advertisement order here by completing the order form.</p>
                <p>Download: <a href="#">Order Form</a></p> 
              </li>
              <li>
                <h3>What can I advertise?</h3>
                <p>To learn more about what your business can advertise with I~Comm, please read our Ad Policy.</p>
                <p>Download: <a href="#">I~Comm Ad Policy</a></p>
              </li>
              <li>
                <h3>Important Information</h3>
                <p>Please note the following as you plan to advertise with I~Comm:</p>
                <ul>
                  <li>Full payment is due Thursday by 12p.m. (noon) prior to publication.</li>

                  <li>Cancellation of advertising space is accepted without penalty until 12p.m. (noon) the Thursday before publication.</li>

                  <li>Cancellations after that deadline will be charged 50% (fifty percent) of the ad's full price.</li>

                  <li>Ads canceled after Friday by 12p.m. (noon) will be charged full price.</li>
                </ul>
              </li>
            </ul>
          </div> <!-- End of main-content -->      

        </div> <!-- End of row-fluid -->
      </div>

      <?php get_footer(); ?>