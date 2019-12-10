<div class="basel-main-import-area basel-row basel-one-column">
	<div class="basel-column basel-stretch-column">
		<div class="basel-column-inner">
			<div class="basel-box basel-box-shadow">
				<div class="basel-box-header">
					<h2>Activate your theme license</h2>
					<span class="basel-box-label <?php if ( basel_is_license_activated() ): ?>basel-label-success<?php else: ?>basel-label-warning<?php endif; ?>">
						<?php if ( basel_is_license_activated() ): ?>
							<?php esc_html_e('Activated', 'basel'); ?>
						<?php else: ?>
							<?php esc_html_e('Optional', 'basel'); ?>
						<?php endif ?>
					</span>
				</div>
				<div class="basel-box-content">
					<?php BASEL_Registry()->license->form(); ?>
				</div>
				<div class="basel-box-footer">
					<p>
						<strong>Note:</strong> you are allowed to use our theme only on one domain if you have purchased a regular license. <br/>
						But we give you an ability to activate our theme to turn on auto updates on two domains: for the development website and for your production (live) website.<br>
						If you need to check all your active domains or you want to remove some of them you should visit <a href="https://xtemos.com/" target="_blank">our website</a> and check the activation list in your account.
					</p>
				</div>
			</div>
		</div>
	</div>
</div>