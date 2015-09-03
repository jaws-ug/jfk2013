<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php entry_thumbnail(); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php if ( get_field( 'sub_title' ) ) : ?>
			<h2 class="entry-sub-title"><?php esc_html( the_field( 'sub_title' ) ); ?></h2>
		<?php endif; ?>
	</header>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php jfk2013_link_pages(); ?>
	</div><!-- .entry-summary -->
	<?php jfk2013_content_nav(); ?>
</article><!-- #post-<?php the_ID(); ?> -->
