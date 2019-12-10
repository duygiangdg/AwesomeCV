<div class="basel-main-import-area basel-row basel-two-columns <?php echo esc_attr( BASEL_Registry()->import->get_imported_versions_css_classes() ); ?>">
	<div class="basel-column import-base">
		<div class="basel-column-inner">
			<div class="basel-box basel-box-shadow">
				<div class="basel-box-header">
					<h2>Basic or full import</h2>
					<span class="basel-box-label basel-label-error"><?php _e('Required', 'basel'); ?></span>
				</div>
				<div class="basel-box-content">
					<div class="basel-success base-imported-alert">
						Base dummy content is successfully imported and installed. Now you can choose any version to apply its settings for your website or leave a default one.
						You are be able to switch to default version settings any time.
					</div>
					<?php BASEL_Registry()->import->imported_versions(); ?>
					<?php BASEL_Registry()->import->base_import_screen(); ?>
				</div>
				<div class="basel-box-footer">
					<p>
						<strong>Basic import</strong> includes default version from our demo and a few products, blog posts and portfolio projects.
						It is a required minimum to see how our theme built and to be able to import additional
						versions or pages.
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="basel-column import-versions">
		<div class="basel-column-inner">
			<div class="basel-box basel-box-shadow">
				<div class="basel-box-header">
					<h2>Demo versions</h2>
					<span class="basel-box-label basel-label-warning"><?php _e('Recommended', 'basel'); ?></span>
				</div>
				<div class="basel-box-content">
					<div class="basel-warning choose-version-warning">Now you can select a version, apply its settings and set it as a home page.
						<br>
						Or just leave this and continue using default version. You will be able to activate any version later.
					</div>
					<?php BASEL_Registry()->import->versions_import_screen(); ?>
				</div>
				<div class="basel-box-footer">
					<p>
						<strong>Demo version</strong> includes page content, slider and settings for one
						of our versions. Import will also change your home page
						and may add some widgets.<br>
						<strong>WARNING</strong>: it may change your Theme Settings.
					</p>
				</div>
			</div>
		</div>
	</div>
</div>