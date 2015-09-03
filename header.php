<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<title><?php is_front_page() ? bloginfo('name') : wp_title( '|', true, 'right' ); ?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php do_action( 'jfk2013_body_before' ); ?>
		<div id="page" class="hfeed site">
			<header id="masthead" class="site-header" role="banner">
					<h1 id="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></a></h1>
				<nav id="main-nav" class="header-navigation" role="navigation">
					<h3 class="menu-title"><span><?php esc_html_e( 'Main menu', 'athlete' ); ?></span></h3>
					<p id="menu-toggle"><span><?php esc_html_e( 'Open MENU' , 'jfk2013' ); ?></span></p>
					<?php jfk2013_main_nav(); ?>
				</nav>
			</header>
			<div id="main" class="wrapper">
