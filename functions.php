<?php
/* Set Up
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
/* theme_setup */
if ( !function_exists( 'jfk2013_theme_setup' ) ) :
	add_action( 'after_setup_theme', 'jfk2013_theme_setup' );

remove_action( 'wp_head', 'jetpack_og_tags' );

function jfk2013_theme_setup() {
	global $content_width;

	if ( !isset( $content_width ) )
		$content_width = 744;

	load_theme_textdomain( 'jfk2013', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	add_theme_support( 'menus' );
	add_theme_support( 'automatic-feed-links' );
	add_post_type_support( 'page', 'excerpt' );

	// Add support for custom headers.
	$defaults = array(
		'default-image' => get_template_directory_uri() . '/images/headers/main-image.jpg',
		'width'         => apply_filters( 'jfk2013_header_image_width', 1500 ),
		'height'        => apply_filters( 'jfk2013_header_image_height', 479 ),
		'header-text'   => false,
	);
	add_theme_support( 'custom-header', $defaults );

	add_theme_support( 'post-thumbnails' );
	add_image_size( 'slide-thumb', 218, 99999 );
	add_image_size( 'slide-crop-thumb', 218, 100, true );
	add_image_size( 'archive-thumb', 148, 99999 );
	add_image_size( 'archive-crop-thumb', 148, 148, true );
}
endif;

/* Query
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
add_action( 'pre_get_posts', 'add_news_post_type_category' );
function add_news_post_type_category( $query ) {
	if ( !is_admin() && $query->is_main_query() && get_query_var('news') ) {
		$query->set( 'post_type', 'post' );
		$query->is_archive = true;
		$query->is_home = false;
		$query->is_front_page = false;
	}
	return $query;
}

add_filter( 'rewrite_rules_array', 'news_rewrite_rules', 9 );
function news_rewrite_rules( $rules ) {
	$newrules = array();

	/* News */
	$newrules['news/?$'] = 'index.php?news=news';
	$newrules['news/page/([0-9]{1,})/?$'] = 'index.php?news=news&paged=$matches[1]';
	$newrules['news/([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$'] = 'index.php?news=news&year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]';
	$newrules['news/([0-9]{4})/([0-9]{1,2})/?$'] = 'index.php?news=news&year=$matches[1]&monthnum=$matches[2]';
	$newrules['news/([0-9]{4})/page/?([0-9]{1,})/?$'] = 'index.php?news=news&year=$matches[1]&paged=$matches[2]';
	$newrules['news/([0-9]{4})/?$'] = 'index.php?news=news&year=$matches[1]';

	return $newrules + $rules;
}

add_filter( 'query_vars', 'add_news_query_var' );
function add_news_query_var( $vars ){
	array_push( $vars, 'news' );
	return $vars;
}

/* Head
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
/**
 * jfk2013_wp_title
 */
add_filter( 'wp_title', 'jfk2013_wp_title', 10, 2 );
function jfk2013_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = $site_description . $sep . $title;

	if ( get_query_var( 'news' ) && is_archive() ) {
		$label_name = __( 'What\'s new', 'jfk2013' );
		$title = $label_name . $sep . $title;
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = sprintf( __( 'Page %s', 'jfk2013' ), max( $paged, $page ) ) . $sep . $title;

	return $title;
}

/**
 * jfk2013_head_mobile_meta
 */
add_action( 'wp_head', 'jfk2013_head_mobile_meta' );
function jfk2013_head_mobile_meta() {
	echo <<< EOT
<meta name="viewport" content="width=device-width">
EOT;
}
/**
 * Enqueues scripts and styles for front-end.
 */
add_action( 'wp_enqueue_scripts', 'jfk2013_scripts_styles', 1 );
function jfk2013_scripts_styles() {

	wp_enqueue_script( 'jquery-touchSwipe', get_template_directory_uri() . '/js/jquery.touchSwipe.min.js', array( 'jquery' ), get_file_time( 'js/jquery.touchSwipe.min.js' ), true );
	wp_enqueue_script( 'jquery-carouFredSel', get_template_directory_uri() . '/js/jquery.carouFredSel.min.js', array( 'jquery' ), get_file_time( 'js/jquery.carouFredSel.min.js' ), true );

	wp_enqueue_script( 'equalize-script', get_template_directory_uri() . '/js/equalize.min.js', array( 'jquery' ), get_file_time( 'js/equalize.min.js' ), true );
	wp_enqueue_script( 'jquery-fittext', get_template_directory_uri() . '/js/jquery.fittext.js', array( 'jquery' ), get_file_time( 'js/jquery.fittext.js' ), true );
	wp_enqueue_script( 'common-script', get_template_directory_uri() . '/js/common.min.js', array( 'jquery' ), get_file_time( 'js/common.min.js' ), true );

	$protocol = is_ssl() ? 'https' : 'http';
	$query_args = array(
		'family' => 'Open+Sans',
	);
	wp_enqueue_style( 'jfk2013-fonts', add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ), array(), null );

	wp_enqueue_style( 'jfk2013-style', get_template_directory_uri() . '/style.css' , array(), get_file_time( 'style.css' ) );

}

add_action( 'jfk2013_body_before', 'add_fb_root' );
function add_fb_root() {
$output = <<<EOD
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
EOD;
echo $output;
}

/* Header
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
/* jfk2013_main_nav */
function jfk2013_main_nav() {
	echo get_jfk2013_main_nav();
}

/* get_jfk2013_main_nav */
function get_jfk2013_main_nav() {
	$output = wp_nav_menu( array( 'menu' => 'main-menu', 'container_id' => 'main-nav-box', 'echo' => false, 'theme_location' => 'main_menu' ) );
	return $output;
}

/* jfk2013_main_img */
function jfk2013_main_img() {
	echo get_jfk2013_main_img();
}

/* get_jfk2013_main_img */
function get_jfk2013_main_img() {
	$header_image = get_header_image();
	if ( $header_image && (is_home() || is_front_page()) ) {
		$output = '<p id="main-img"><img src="' . esc_url( $header_image ) . '" alt="' . esc_attr( get_bloginfo( 'description', 'display' ) ) . '"></p>';
		return $output;
	}
}


/* Common
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
function entry_thumbnail( $args = array() ) {
	echo get_entry_thumbnail( $args );
}

function get_entry_thumbnail( $args = array() ) {
	$defaults = array(
		'id'       => get_the_ID(),
		'size'     => 'archive-thumb',
		'width'    => 148,
		'height'   => 148,
		'class'    => '',
	);
	$r       = wp_parse_args( $args, $defaults );
	extract($r);
	$class = $class ? ' ' . $class : '';
	$output = '<p class="thumb' . $class . '">' . "\n";
	if ( !is_single() )
		$output .= '<a href="' . get_permalink( $id ) . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'est' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark">' . "\n";
	if ( has_post_thumbnail( $id ) ) {
		$output .= get_the_post_thumbnail( $id, $size ) . "\n";
	} else {
		$src = apply_filters('est_no-image', get_template_directory_uri() . '/images/other/no-image.png', $width, $height);
		$output .= '<img src="' . $src . '" alt="' . the_title_attribute( 'echo=0' ) . '" width="' . $width . '" height="' . $height . '">' . "\n";
	}
	if ( !is_single() )
		$output .= '</a>' . "\n";
	$output .= '</p>' . "\n";
	return apply_filters( 'entry_thumbnail', $output, $size, $width, $height );
}
function entry_data() {
	echo get_entry_data();
}
function get_entry_data() {
	$output = '<p class="entry-date"><time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time></p>';
	return apply_filters( 'entry_date', $output );
}

function entry_more_link( $post_id = null ) {
	echo get_entry_more_link( $post_id );
}

function get_entry_more_link( $post_id = null ) {
	if ( ! $post_id )
		$post_id = get_the_ID();

	return '<p class="entry-more"><a href="'. get_permalink( $post_id ) . '">' . __( 'Read more &raquo;', 'jfk2013' ) . '</a></p>';
}

function content_social_button() {
	echo get_content_social_button();
}
function get_content_social_button() {
	$id = get_the_ID();
	$url = esc_url( get_permalink( $id ) );
	$text = esc_html( get_the_title( $id ) );
	$via = esc_attr( 'JAWS_FES_KANSAI' );
	$output = '<div class="social-button">' . "\n";
	$output .= '<p class="twitter-button"><a href="https://twitter.com/share" class="twitter-share-button" data-url="' . $url . '" data-text="' . $text . '" data-lang="ja" data-via="' . $via . '">ツイート</a></p>' . "\n";
	$output .= '<p class="fb-like" data-href="' . $url . '" data-send="false" data-layout="button_count" data-width="70" data-show-faces="false"></p>' . "\n";
	$output .= '<p class="g-plusone" data-size="medium" data-href="' . $url . '"></p>' . "\n";
	$output .= '<p class="hatena"><a href="http://b.hatena.ne.jp/entry/' . $url . '" class="hatena-bookmark-button" data-hatena-bookmark-title="' . $text . '" data-hatena-bookmark-layout="simple-balloon" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a></p>' . "\n";
	$output .= '</div>' . "\n";
	return $output;
}
add_filter('excerpt_more', 'new_excerpt_more');
function new_excerpt_more($more) {
	return '...';
}

/* Archive
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
/* jfk2013_page_title */
function jfk2013_page_title() {
	echo get_jfk2013_page_title();
}

/* get_jfk2013_page_title */
function get_jfk2013_page_title() {
	global $wp_query;
	$output = '';
	if ( !is_home() || !is_front_page() ) {
		if ( is_category() ) :
			$output .= single_cat_title( '', false );
		elseif ( is_tag() ) :
			$output .= single_tag_title( '', false );
		elseif ( is_tax() ) :
			$output .= single_term_title( '', false );
		elseif ( is_post_type_archive() ) :
			$output .= post_type_archive_title( '', false );
		else :
			$output .= __( 'Archives', 'jfk2013' );
		endif;
	}
	return $output;
}

/* ps_page_navi */
function ps_page_navi() {
	echo get_ps_page_navi();
}

/* get_ps_page_navi */
function get_ps_page_navi() {
	global $wp_query;
	if ( function_exists( 'page_navi' ) ) {
		if ( $wp_query->max_num_pages > 1 ) {
			$args = array(
				'items'         => 5,
				'show_boundary' => false,
				'edge_type'     => 'none',
				'navi_element'  => 'nav',
				'elm_class'     => 'archive-nav',
				'echo'          => false,
			);
			return page_navi( $args );
		}
	}
}

/* Single
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
function jfk2013_link_pages() {
	echo get_jfk2013_link_pages();
}

function get_jfk2013_link_pages() {
	global $page, $numpages, $multipage, $more, $pagenow;
	$output = '';
	if ( $multipage ) {
		$output .= '<div class="page-links">' ."\n";
		$i = $page - 1;
		if ( $i && $more ) {
			$output .= _wp_link_page( $i );
			$output .= ' &laquo;</a>';
		}
		$i = $page + 1;
		$output .= wp_link_pages( array( 'before' => '', 'after' => '', 'link_before' => '<span>', 'link_after' => '</span>', 'echo' => 0 ) );
		if ( $i <= $numpages && $more ) {
			$output .= _wp_link_page( $i );
			$output .= ' &raquo;</a>';
		}
		$output .= '</div>' ."\n";
	}
	return $output;
}

function jfk2013_content_nav() {
	echo get_jfk2013_content_nav();
}
function get_jfk2013_content_nav() {
	global $wp_query;
	$output = '';
	$next = get_adjacent_post( false, '', false );
	$previous = get_adjacent_post();
	$output .= '<nav id="single-nav">' . "\n";
	if ( $previous )
		$output .= '<p class="nav-previous"><a href="' . esc_url( get_permalink( $previous->ID ) ) . '">' . esc_html( '&larr;' . get_the_title( $previous->ID ) ) . '</a></p>' . "\n";

	if ( $next )
		$output .= '<p class="nav-next"><a href="' . esc_url( get_permalink( $next->ID ) ) . '">' . esc_html( get_the_title( $next->ID ) . '&rarr;' ) . '</a></p>' . "\n";

	$output .= '</nav>' . "\n";
	return $output;
}

/* Page
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

/* HOME
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
function jfk2013_slide( $limit = -1 ) {
	echo get_jfk2013_slide( $limit );
}
function get_jfk2013_slide( $limit = -1 ) {
	$output = '';
	$posts_array = array();
	$args = array(
		'post_type' => 'sponsor',
		'posts_per_page' => $limit,
		'orderby' => 'menu_order',
		'order' => 'ASC',
	);
	$posts_array = get_posts( $args );
	$post_count = count($posts_array);
	if ( $posts_array ) {
		$output .= '<div id="slide">' . "\n";
		$output .= '<ul id="carousel">' . "\n";
		foreach ( $posts_array as $post ) {
			setup_postdata( $post );
			$id = $post->ID;
			$link = esc_url( get_field( '_sponsor_url', $id ) );
			$blank = get_field( '_sponsor_target', $id );
			if ( $blank ) {
				$target = ' target="_blank"';
			} else {
				$target = '';
			}
			$img = get_the_post_thumbnail( $id, 'slide-thumb' );
			if ( $link ) {
				$output .= '<li><a href="' . $link . '"' . $target . '>' . $img . '</a></li>';
			} else {
				$output .= '<li>' . $img . '</li>';
			}
		}
		$output .= '</ul>' . "\n";
		$output .= '</div>' . "\n";
	}
	return $output;
}
function jfk2013_new_post( $args = array() ) {
	echo get_jfk2013_new_post( $args );
}
function get_jfk2013_new_post( $args = array() ) {
	$output = '';
	$posts_array = array();
	$defaults = array(
		'posts_per_page' => 3,
	);
	$args = wp_parse_args( $args, $defaults );
	extract($args);
	$posts_array = get_posts( $args );
	if ( ! $posts_array )
		return;

	$output .= '<div class="new-post-content" id="new-post">' . "\n";
	$output .= '<h2 class="new-post-title">' . esc_html__( 'News', 'jfk2013' ) . '</h2>' . "\n";
	foreach ( $posts_array as $post ) {
		setup_postdata($post);
		$id = $post->ID;
		$title = esc_html( get_the_title( $id ) );
		$link = esc_url( get_permalink( $id ) );
		$post_date = $post->post_date;
		$post_date = esc_attr( date_i18n( get_option( 'date_format' ), strtotime($post_date) ) );
		$post_date_unix = esc_attr( date_i18n( 'c', strtotime($post_date) ) );
		$event_venue = '';
		$output .= '<article id="post-' . $id . '" class="' . implode( ' ', get_post_class( '', $id ) ) . '">' . "\n";
		$output .= '<header class="new-post-header">' . "\n";
		$output .= '<p class="post-date">';
		$output .= '<time datetime="' . $post_date_unix . '">' . $post_date . '</time>';
		if ( $event_venue )
			$output .= '<span class="event-venue">' . $event_venue . '</span>';
		$output .= '</p>';
		$output .= '<h1 class="post-title"><a href="' . $link . '" title="' . esc_attr( sprintf( __( 'Permalink to %s', 'athlete' ), the_title_attribute( 'echo=0' ) ) ) . '" rel="bookmark">' . $title . '</a></h1>' . "\n";
		$output .= '</header>' . "\n";
		$output .= get_entry_more_link( $id ); 
		$output .= '</article>' . "\n";
	}
	$output .= '<p class="more-archive-list"><a href="' . esc_url( home_url('news/') ) . '">' . esc_html__( 'Archive list &gt;', 'jfk2013' ) . '</a></p>' . "\n";
	$output .= '</div>' . "\n";
	return $output;
}

/* Side
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

/* Footer
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
/* jfk2013_footer_nav */
function jfk2013_footer_nav() {
	echo get_jfk2013_footer_nav();
}

/* get_jfk2013_footer_nav */
function get_jfk2013_footer_nav() {
	$output = wp_nav_menu( array( 'menu' => 'footer-menu', 'container_id' => 'footer-nav-box', 'echo' => false, 'theme_location' => 'footer_menu' ) );
	return $output;
}

function jfk2013_social_button() {
	echo get_jfk2013_social_button();
}
function get_jfk2013_social_button() {
	$url = esc_url( home_url( '/' ) );
	$text = esc_attr( get_bloginfo( 'name', 'display' ) );
	$via = esc_attr( 'JAWS_FES_KANSAI' );
	$output = '<div class="social-button">' . "\n";
	$output .= '<p class="twitter-button"><a href="https://twitter.com/share" class="twitter-share-button" data-url="' . $url . '" data-text="' . $text . '" data-lang="ja" data-via="' . $via . '">ツイート</a></p>' . "\n";
	$output .= '<p class="fb-like" data-href="' . $url . '" data-send="false" data-layout="button_count" data-width="70" data-show-faces="false"></p>' . "\n";
	$output .= '<p class="g-plusone" data-size="medium" data-href="' . $url . '"></p>' . "\n";
	$output .= '<p class="hatena"><a href="http://b.hatena.ne.jp/entry/' . $url . '" class="hatena-bookmark-button" data-hatena-bookmark-title="' . $text . '" data-hatena-bookmark-layout="simple-balloon" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a></p>' . "\n";
	$output .= '</div>' . "\n";
	return $output;
}

add_action( 'wp_footer', 'jfk2013_footer_script', 99 );
function jfk2013_footer_script() {
	echo <<< EOT
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<script type="text/javascript">
	window.___gcfg = {lang: "ja"};
	(function() {
	var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
	po.src = "https://apis.google.com/js/plusone.js";
	var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
	})();
</script>
<script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
EOT;
}

/* Other
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
/**
 * get_file_time
 */
function get_file_time( $file = null, $path = null, $child = false ) {
	if ( !$path && $child )
		$path = get_stylesheet_directory();

	if ( !$path && !$child )
		$path = get_template_directory();

	$value = filemtime( $path . '/' . $file );
	return $value;
}

