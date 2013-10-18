<!DOCTYPE html>
<html lang="en">
<head> 
  <meta charset="utf-8"> 
  <title><?php wp_title('|',1,'right'); ?> <?php bloginfo('name'); ?></title> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  

  <!-- Le styles --> 
  <link href="<?php bloginfo('stylesheet_url');?>" rel="stylesheet" media:"screen"> 

  <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements --> 
  <!--[if lt IE 9]> 
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> <![endif]--> 


  <?php wp_head(); ?> 
</head> 
<body> 
  <div id="wrap">

    <div class="container">

      <div id="header-wrapper" class="topnav-wrapper">
        <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->

        <div class="row-fluid">
          <div class="container container-narrow">

            <div class="navbar navbar-inverse">

              <div class="row-fluid visible-desktop">

                <div class="span9 desktop-topleft-nav">
                  <div class="navbar-inner">
                    <ul class="nav">
                      <li class="rightborder">
                        <a href= "<?php echo content_url(); ?>/about">About Us</a>
                      </li>
                      <li class="rightborder">
                        <a href="http://www.byui.edu/">BYU-Idaho</a>
                      </li>
                        <li>
                          <a href="http://www.soapboxagency.net/">Agency</a>
                        </li>
                        <li>
                          <a href="<?php echo content_url(); ?>/advertise-with-us/">Advertise with Us</a>
                        </li>
                  </ul>
                </div>
              </div>

              <div class="span3 login desktop-topright-nav">
                <div class="navbar-inner">
                  <ul class="nav pull-right">
                    <li>
                      <?php wp_loginout(); ?>
                    </li>

                    <?php
                    if ( is_user_logged_in() ) {
                      echo '<li><a href="'.admin_url().'" >Dashboard</a></li>';
                    }
                    ?>

                    <li>
                      <a href="<?php echo content_url(); ?>/contact/">Contact</a>
                    </li>
                  </ul>
                </div>
              </div>

            </div>

            <hr class="visible-desktop">

            <div class="row-fluid desktop-header"> <!-- desktop header is found in the custom-responsive.css -->

              <div class="span8 "> 
                <a class="brand" href="<?php echo site_url(); ?>">Scroll</a>
              </div>
              <div class="span4">

                <form class="form-search line-height pull-right front-search" method="get" action="http://beta.byuicomm.net/search/">
                  <div class="input-append">
                    <input type="text" class="span9 search-query" name="q" placeholder="Search...">
                    <button type="submit" class="btn"><i class="icon-search"></i></button>
                  </div>
                </form>
              </div>
            </div>

            <div class="navbar-inner desktop-navbar"> <!--desktop navbar is found in the custom-responsive.css --> 
              <ul class="nav">
                <li class="rightborder">
                  <a href="<?php echo site_url(); ?>">
                    <i class="icon-home icon-white"></i>
                  </a>
                </li>
                <?php wp_nav_menu (array( 'theme_location' => 'top-bar', 
                  'depth' => 2,
                  'container' => false,
                  'menu_class' => 'nav',
                  'walker' => new Bootstrap_Walker_Nav_Menu()
                  ));
                  ?>
                  <!-- Read about Bootstrap dropdowns at http://twitter.github.com/bootstrap/javascript.html#dropdowns -->
                </ul>
              </div><!--/.nav-collapse -->

              <!--
              * Mobile version of the navigation, title, and search bar
              * 
              * Hidden on the desktop version
              -->

            <div class="row-fluid mobile-desktop-head"> <!-- mobile-desktop-head is found in the custom.css -->
              <div class="span12">
                <div class="mobile_leftcollapse">
                  <div class="navbar-inner"> 
                    <div class="container" style="width: auto">
                      <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
                      <a class="btn btn-navbar mobile-menu-button-correct-width" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </a>
                      <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
                      <div class="nav-collapse collapse">
                        <ul class="nav">
                          <li class="rightborder">
                            <a href="<?php echo site_url(); ?>"><i class="icon-home icon-white"></i></a>
                          </li>
                          <?php wp_nav_menu (array( 'theme_location' => 'top-bar', 
                            'depth' => 2,
                            'container' => false,
                            'menu_class' => 'nav',
                            'walker' => new Bootstrap_Walker_Nav_Menu()
                            ));
                          ?>
                          <!-- Read about Bootstrap dropdowns at http://twitter.github.com/bootstrap/javascript.html#dropdowns -->
                        </ul>
                      </div><!--/.nav-collapse -->
                    </div>
                  </div>   
                </div>  <!-- /nav collapse collapse -->
                <!-- end of collapse nav  ( mobile left collapse) -->

                <div class="mobiletitle"> 
                  <a class="brand" href="<?php echo site_url(); ?>">Scroll</a>
                </div> <!-- / mobiletitle -->

                <div class="mobile_rightcollapse_bar">
                  <div class="navbar-inner"> 
                    <div class="container" style="width: auto">
                      <a id="mobile-search-button" class="btn btn-navbar" data-toggle="collapse" data-target=".search-collapse">
                        <span class="icon-search icon-white"></span>
                      </a>

                      <div class="search-collapse collapse">
                        <form class="form-search line-height mobileposition front-search" method="get" action="http://beta.byuicomm.net/search/">
                          <div class="input-append">
                            <input type="text" class="span9 search-query mobile-searchbar" name="q" placeholder="Search...">
                            <button type="submit" class="btn"><i class="icon-search"></i></button>
                          </div>
                        </form>
                        <div class="mobile-searchform-height"></div>
                      </div> <!-- search-collapse -->
                    </div>
                  </div>
                </div> <!-- mobile_rightcollapse_bar -->
              </div>
            </div> <!-- Mobile View End-->

          </div><!-- /.navbar -->
        </div> <!-- /.container -->
      </div>
    </div><!-- /.navbar-wrapper -->