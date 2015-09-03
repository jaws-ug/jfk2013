<?php get_header(); ?>
	<div id="content" role="main">
		<?php if ( have_posts() ) : ?>
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php if ( get_field( 'sub_title' ) ) : ?>
					<h2 class="entry-sub-title"><?php esc_html( the_field( 'sub_title' ) ); ?></h2>
				<?php endif; ?>
			</header><!-- .entry-header -->
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				<?php content_social_button(); ?>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
<?php get_footer(); ?>
