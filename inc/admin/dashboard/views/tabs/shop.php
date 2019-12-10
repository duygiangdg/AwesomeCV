<div class="basel-main-import-area basel-row basel-two-columns <?php echo esc_attr( BASEL_Registry()->import->get_imported_versions_css_classes() ); ?>">
	<div class="basel-column import-shop-options">
		<div class="basel-column-inner">
			<div class="basel-box basel-box-shadow">
				<div class="basel-box-header">
					<h2>Shop page options</h2>
					<span class="basel-box-label basel-label-success"><?php _e('Optional', 'basel'); ?></span>
				</div>
				<div class="basel-box-content">
					<?php BASEL_Registry()->import->shops_import_screen(); ?>
				</div>
				<div class="basel-box-footer">
					<p>Set up <strong>shop page</strong> settings examples from our demo. It may replace some of your theme settings.</p>
				</div>
			</div>
		</div>
	</div>
	<div class="basel-column import-single-products">
		<div class="basel-column-inner">
			<div class="basel-box basel-box-shadow">
				<div class="basel-box-header">
					<h2>Single product page layouts</h2>
					<span class="basel-box-label basel-label-success"><?php _e('Optional', 'basel'); ?></span>
				</div>
				<div class="basel-box-content">
					<?php BASEL_Registry()->import->products_import_screen(); ?>
				</div>
				<div class="basel-box-footer">
					<p>Set up <strong>product page</strong> settings examples from our demo. It may replace some of your theme settings.</p>
				</div>
			</div>
		</div>
	</div>
</div>