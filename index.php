<?php get_header(); ?>
	<section id="content" role="main">
		<header class="page-header">
			<h1 class="page-title"><?php jfk2013_page_title(); ?></h1>
			<?php if ( category_description() ) : ?>
				<h2 class="page-sub-title"><?php echo category_description(); ?></h2>
			<?php endif; ?>
		</header>
		<div id="main-content">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content' ); ?>
				<?php endwhile; ?>
				<?php ps_page_navi(); ?>
			<?php endif; ?>
		</div>
	</section><!-- #content -->
<?php get_footer(); ?>
