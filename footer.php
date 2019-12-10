<?php
/**
 * The template for displaying the footer
 *
 */
?>
<?php if (basel_needs_footer()): ?>
	<?php basel_page_bottom_part(); ?>

	<?php if ( basel_get_opt( 'prefooter_area' ) != '' ): ?>
		<div class="basel-prefooter">
			<div class="container">
				<?php echo do_shortcode( basel_get_opt( 'prefooter_area' ) ); ?>
			</div>
		</div>
	<?php endif ?>

	<!-- FOOTER -->
	<footer class="footer-container color-scheme-<?php echo esc_attr( basel_get_opt( 'footer-style' ) ); ?>">
		
		<?php 
		if ( basel_get_opt( 'disable_footer' ) ) {
			get_sidebar( 'footer' ); 
		}
		?>

		<?php if ( basel_get_opt( 'disable_copyrights' ) ): ?>
			<div class="copyrights-wrapper copyrights-<?php echo esc_attr( basel_get_opt( 'copyrights-layout' ) ); ?>">
				<div class="container">
					<div class="min-footer">
						<div class="col-left">
							<?php if ( basel_get_opt( 'copyrights' ) != ''): ?>
								<?php echo do_shortcode( basel_get_opt( 'copyrights' ) ); ?>
							<?php else: ?>
								<p>&copy; <?php echo date( 'Y' ); ?> <a href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo( 'name' ); ?></a>. <?php _e( 'All rights reserved', 'basel' ) ?></p>
							<?php endif ?>
						</div>
						<?php if ( basel_get_opt( 'copyrights2' ) != ''): ?>
							<div class="col-right">
								<?php echo do_shortcode( basel_get_opt( 'copyrights2' ) ); ?>
							</div>
						<?php endif ?>
					</div>
				</div>
			</div>
		<?php endif ?>
		
	</footer>
<?php endif ?>
</div> <!-- end wrapper -->

<div class="basel-close-side"></div>
<?php do_action( 'basel_before_wp_footer' ); ?>
<?php wp_footer(); ?>

<?php if (basel_needs_footer()) do_action( 'basel_after_footer' ); ?>

</body>
</html>
