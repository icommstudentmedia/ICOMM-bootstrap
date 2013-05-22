<?php
/**
 * Template Name: Home-Page + Carousel OLD
 * Last Revised: July 16, 2012
 */
 get_header(); ?>
<!--
* This is the jumbotron as it appears on the main bootstrap page, I copied/pasted it in and added correct links to make sure I have the formatting right.
* From here we just implement the loop.
* For Clarification purposes, we want to grab the 5 most recent articles and their thumbnails for the carousel at the top right?
* -Shane
-->

<div id="myCarousel" class="carousel slide visible-desktop">
 <div class="carousel-inner">
  <div class="item active">
   <img src="<?php bloginfo('template_directory'); ?>/img/bootstrap-mdo-sfmoma-01.jpg" alt="">
   <div class="carousel-caption">
    <h4>Title of a News Story</h4>
    <p>According to tthe example layout, this is not a snippet, but rather an extract from the article. Is there going to be a set length to this snippet? Also something to consider is that on the mockup 
      the text is restricted to the left-hand side, do we want the text to span across the page, and if not, how should we restrict text to appear on the carousel caption? </p>
    <button class="btn btn-primary" type="button">Read More</button>
   </div>
  </div>
  <div class="item">
   <img src="<?php bloginfo('template_directory'); ?>/img/bootstrap-mdo-sfmoma-02.jpg" alt="">
   <div class="carousel-caption">
    <h4>This will be the Story's Title</h4>
    <p>According to tthe example layout, this is not a snippet, but rather an extract from the article. Is there going to be a set length to this snippet? Also something to consider is that on the mockup 
      the text is restricted to the left-hand side, do we want the text to span across the page, and if not, how should we restrict text to appear on the carousel caption? </p>
    <button class="btn btn-large btn-primary" type="button">Read More</button>
   </div>
  </div>
  <div class="item">
   <img src="<?php bloginfo('template_directory'); ?>/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
   <div class="carousel-caption">
    <h4>How about a Title for an Epic story the Scroll will make</h4>
    <p>According to tthe example layout, this is not a snippet, but rather an extract from the article. Is there going to be a set length to this snippet? Also something to consider is that on the mockup 
      the text is restricted to the left-hand side, do we want the text to span across the page, and if not, how should we restrict text to appear on the carousel caption? </p>    
    <button class="btn btn-primary" type="button">Read More</button>
   </div>
  </div>
 </div>
 <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
 <a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
</div>

    <div class="navbar-wrapper">
      <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
      <div class="container">

        <div class="navbar navbar">
          <div class="navbar-inner">
            <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </a>
            <a class="brand" href="<?php echo site_url(); ?>"><?php bloginfo('name'); ?></a>
            <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
            <div class="nav-collapse collapse">
              <ul class="nav">

                <?php wp_nav_menu (array('theme_location' => 'second-menu', 'menu_class' => 'nav'));?>

              </ul>
            </div><!--/.nav-collapse -->
          </div><!-- /.navbar-inner -->
        </div><!-- /.navbar -->

      </div> <!-- /.container -->
    </div><!-- /.navbar-wrapper -->

<div class="container marketing">
  <hr>
  <div class="row">
    <div class="span9">
      <!-- Top Stories  3 Across-->
      <div class="row">

        <h2 class="feature">Top Stories</h2>
        <div class="span3">
          <div class="thumbnail-wrapper">
            <img src="<?php bloginfo('template_directory'); ?>/img/bootstrap-mdo-sfmoma-01.jpg" alt="">
            <div class="description">
              <p class='description-content'>I am not sure if this is the title of the story</p>
            </div>
          </div>
        </div>
  
          <div class="span3">
            <div class="thumbnail-wrapper">
              <img src="<?php bloginfo('template_directory'); ?>/img/bootstrap-mdo-sfmoma-02.jpg" alt="">
              <div class="description">
                <p class='description-content'>Or The Description</p>
              </div>
            </div>
          </div>
  
          <div class="span3">
            <div class="thumbnail-wrapper">
              <img src="<?php bloginfo('template_directory'); ?>/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
              <div class="description">
                <p class='description-content'>Truth or Fiction</p>
              </div>
            </div>
          </div>
        </div>
  
      <hr>
      <!-- Latest stories, Endless content, one story per line -->
      <div class="row feature">

        <h2 feature-lead>Latest Stories</h2>
        <div>
          <img class="feature-img pull-left" src="<?php bloginfo('template_directory'); ?>/img/bootstrap-mdo-sfmoma-01.jpg" alt="">
          <h3> All-you-can-eat pie </h3>
          <p class="lead">Body/copy is 12 px. The title of the article is 16 px. The category Latest Stories and Stop Stories is 22 px. It’s all in Arial. It would be one sentence about 2-3 lines.</p>
        </div>
      </div>
      <hr>
      <div class="row feature">
        <div>
          <img class="feature-img pull-left" src="<?php bloginfo('template_directory'); ?>/img/bootstrap-mdo-sfmoma-02.jpg" alt="">
          <h3> Student travel across the country for the holidays </h3>
          <p class="lead">Body/copy is 12 px. The title of the article is 16 px. The category Latest Stories and Stop Stories is 22 px. It’s all in Arial. It would be one sentence about 2-3 lines.</p>
        </div>

      </div>
    </div>

    <!-- Sidebar -->
    <div class="span3">
      <?php get_sidebar(); ?>
    </div>
    
  </div>
</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?php get_footer();?>
