				<div id="footer-content">
					<?php jfk2013_slide(); ?>
					<?php if ( is_front_page() ) : ?>
						<div id="jfk2013-new-post-box">
							<?php jfk2013_new_post(); ?>
							<div class="new-post-content" id="twitter-content">
								<h2 class="new-post-title"><?php esc_html_e( 'Twitter', 'jfk2013' ); ?></h2>
								<a class="twitter-timeline" height="552" data-dnt=true href="https://twitter.com/JAWS_FES_KANSAI" data-widget-id="354424797167505408">#jawsug に関するツイート</a>
								<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
							</div>
							<div class="new-post-content" id="facebook-content">
								<h2 class="new-post-title"><?php esc_html_e( 'Facebook', 'jfk2013' ); ?></h2>
								<div class="fb-like-box" data-href="https://www.facebook.com/JawsFestaKansai2013" data-height="552" data-show-faces="true" data-stream="true" data-show-border="true" data-header="false"></div>
							</div>
						</div>
					<?php endif; ?>
					<div id="jfk2013-contact-box">
						<p class="text"><?php esc_html_e( 'To participate in the JAWS FESTA Kansai 2013', 'jfk2013' ); ?></p>
						<p class="contact-button"><a href="<?php echo home_url('/tickets/'); ?>"><?php esc_html_e( 'Tickets', 'jfk2013' ); ?></a></p>
					</div>
				</div>
			</div><!-- #main -->
			<footer id="colophon" role="contentinfo">
				<?php jfk2013_footer_nav(); ?>
				<p id="copyright"><small>Copyright &copy; AWS User Group Japan. All rights reserved.</small></p>
				<?php jfk2013_social_button(); ?>
			</footer><!-- #colophon -->
		</div><!-- #page -->
		<?php wp_footer(); ?>
	</body>
</html>