<div class="basel-main-import-area basel-row basel-two-columns <?php echo esc_attr( BASEL_Registry()->import->get_imported_versions_css_classes() ); ?>">
	<div class="basel-column import-pages">
		<div class="basel-column-inner">
			<div class="basel-box basel-box-shadow">
				<div class="basel-box-header">
					<h2>Additional pages</h2>
					<span class="basel-box-label basel-label-success"><?php _e('Optional', 'basel'); ?></span>
				</div>
				<div class="basel-box-content">
					<?php BASEL_Registry()->import->pages_import_screen(); ?>
				</div>
				<div class="basel-box-footer">
					<p>Import additional pages that may be useful for your website like Contacts, About us, FaQs, Services etc.</p>
				</div>
			</div>
		</div>
	</div>
	<div class="basel-column import-elements">
		<div class="basel-column-inner">
			<div class="basel-box basel-box-shadow">
				<div class="basel-box-header">
					<h2>Additional elements</h2>
					<span class="basel-box-label basel-label-success"><?php _e('Optional', 'basel'); ?></span>
				</div>
				<div class="basel-box-content">
					<?php BASEL_Registry()->import->elements_import_screen(); ?>
				</div>
				<div class="basel-box-footer">
					<p>Elements pages demonstrate abilities of custom WPBakery Page Builder elements that come with our theme.</p>
				</div>
			</div>
		</div>
	</div>
</div>