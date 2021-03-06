<!DOCTYPE html>

<html <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml" hreflang="en-us">

<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-4879883-9"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'UA-4879883-9');
	</script>

	<!-- Basic Page Needs
	================================================== -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title><?php bloginfo('name'); ?></title>
	<meta name="author" content="@theinitgroup">
	
	<!-- Mobile Specific Metas
  	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!-- Favicons
	================================================== -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php bloginfo('template_directory'); ?>/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php bloginfo('template_directory'); ?>/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php bloginfo('template_directory'); ?>/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?php bloginfo('template_directory'); ?>/favicon/manifest.json">
	<link rel="mask-icon" href="<?php bloginfo('template_directory'); ?>/favicon/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#ffffff">
	
	<!-- Facebook open graph tags -->
	<meta property="og:title" content="<?php bloginfo('name'); ?>"/>
	<meta property="og:description" content="<?php bloginfo('description'); ?>"/>

	<?php if (have_posts()):while(have_posts()):the_post(); endwhile; endif;?>
		<meta property="fb:app_id" content="505574093146847" />
	<?php if (is_single()) { ?>
		<!-- Open Graph -->
		<meta property="og:url" content="<?php the_permalink(); ?>"/>
		<meta property="og:title" content="<?php single_post_title(''); ?>" />
		<meta property="og:description" content="<?php echo strip_tags(get_the_excerpt()); ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:image" content="<?php if (function_exists('wp_get_attachment_thumb_url')) {
			echo wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'medium', false );
		} ?>" />
		<!-- Schema.org -->
		<meta itemprop="name" content="<?php single_post_title(''); ?>"> 
		<meta itemprop="description" content="<?php echo strip_tags(get_the_excerpt()); ?>"> 
		<meta itemprop="image" content="<?php if (function_exists('wp_get_attachment_thumb_url')) {
			echo wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'medium', false );
		} ?>">
		<!-- Twitter Cards -->
		<meta property="twitter:card" content="summary"> 
		<meta property="twitter:site" content="@thetoastedpost"> 
		<meta property="twitter:title" content="<?php single_post_title(''); ?>"> 
		<meta property="twitter:description" content="<?php echo strip_tags(get_the_excerpt()); ?>"> 
		<meta property="twitter:creator" content="@thetoastedpost"> 
		<meta property="twitter:image" content="<?php if (function_exists('wp_get_attachment_thumb_url')) {
			echo wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'medium', false );
		} ?>">
		<meta property="twitter:url" content="<?php the_permalink(); ?>" />
		<meta property="twitter:domain" content="<?php echo site_url(); ?>">
	<?php } else { ?>
		<!-- Open Graph -->
		<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
		<meta property="og:description" content="<?php bloginfo('description'); ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:image" content="<?php echo bloginfo('template_directory'); ?>/assets/images/default_facebook.jpg" />
		<!-- Schema.org -->
		<meta itemprop="name" content="<?php bloginfo('name'); ?>"> 
		<meta itemprop="description" content="<?php bloginfo('description'); ?>"> 
		<meta itemprop="image" content="<?php echo bloginfo('template_directory'); ?>/assets/images/default_google.jpg">
		<!-- Twitter Cards -->
		<meta property="twitter:card" content="summary"> 
		<meta property="twitter:site" content="@thetoastedpost"> 
		<meta property="twitter:title" content="<?php bloginfo('name'); ?>"> 
		<meta property="twitter:description" content="<?php bloginfo('description'); ?>"> 
		<meta property="twitter:creator" content="@thetoastedpost"> 
		<meta property="twitter:image" content="<?php echo bloginfo('template_directory'); ?>/assets/images/default_twitter.jpg">
		<meta property="twitter:url" content="<?php the_permalink() ?>" />
		<meta property="twitter:domain" content="<?php echo site_url(); ?>">
	<?php } ?>

	<!-- WP Generated Header
	================================================== --> 
	<?php wp_head(); ?>

	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
	n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
	document,'script','https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '505574093146847'); // Insert your pixel ID here.
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=505574093146847&ev=PageView&noscript=1"
	/></noscript>
	<!-- DO NOT MODIFY -->
	<!-- End Facebook Pixel Code -->
    
</head>

<style>
	#loader{
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999999;
		background: white;
		top: 0;
	}
	#loader img {
		display: block;
		position: fixed;
		top: 50%;
		left: 50%;
		width: 300px;
		height: 300px;
		margin: -200px 0 0 -150px;
	}
</style>

<body <?php body_class();?>>

<div id="loader">
	<img src="<?php echo bloginfo('template_directory'); ?>/assets/images/loading.gif"></img>
</div>

<?php 

echo '<div id="bodyWrap">';

echo '<header></header>';

?>
	<section id="top" class="outer" data-parallax='{"y" : 500, "smoothness": 1}'>
		<?php
			echo '<div class="inner">';
				echo '<img class="svg" src="'.get_bloginfo('template_directory').'/assets/images/logo.svg" alt="" />';
			echo '</div>';
			echo '<div id="instafeed"></div>';
		?>
	</section>
<?php

echo '<section id="about">';

	echo '<div class="wrap">';

		echo '<div id="introVideo">';
			echo '<video preload poster="'.get_bloginfo('template_directory').'/assets/videos/poster.jpg">';
				echo '<source src="'.get_bloginfo('template_directory').'/assets/videos/ttp_intro.webm" type="video/webm" />';
				echo '<source src="'.get_bloginfo('template_directory').'/assets/videos/ttp_intro.mp4" type="video/mp4; codecs="avc1.42E01E, mp4a.40.2"" />';
				echo '<source src="'.get_bloginfo('template_directory').'/assets/videos/ttp_intro.ogv" type="video/ogg; codecs="theora, vorbis"" />';
			echo '</video>';
			echo '<i class="fa fa-play"></i>';
			echo '<i class="fa fa-pause"></i>';
		echo '</div>';

		echo '<div class="tagline">';
			echo '"<span class="red">Good</span> <span class="green">food</span> = <span class="red">Good</span> <span class="green">mood</span>. Gotta feed the people."';
		echo '</div>';

		echo '<div id="story">';
			$page = get_page_by_title('About');
			echo apply_filters('the_content', $page->post_content);
			echo '<div class="ending">';
				echo '<p>Cheers,</p>';
				echo '<img class="signature" src="'.get_bloginfo('template_directory').'/assets/images/signature.svg" alt="Tiki Friedman" />';
				echo '<p>Tiki Friedman</p>';
			echo '</div>';
			echo '<div class="more"><i class="fa fa-angle-down"></i></div>';
		echo '</div>';

	echo '</div>';

echo '</section>';

?>
