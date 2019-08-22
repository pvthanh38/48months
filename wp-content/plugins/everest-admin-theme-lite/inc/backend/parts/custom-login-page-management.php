<?php defined('ABSPATH') or die("No script kiddies please!"); ?>
<div class="eat-tab-content eat-tab-custom-login-management" style="display: none;">
	<div class="eat-tab-content-header">
		<div class="eat-tab-content-header-title"><?php _e('custom Login page Management', 'everest-admin-theme-lite'); ?></div>
	</div>
	<div class="eat-tab-content-body">
		<div class="eat-options-wrap-outer">
			<div class="eat-options-wrap">
				<label for="eat-background-option"><?php _e('Background selection', 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap eat-background-select-wrap">
					<select id='eat-background-options' name='everest_admin_theme[custom_login][background][type]' class="eat-selectbox-wrap eat-select-options eat-background-selector">
						<option value='' ><?php _e( 'Default', 'everest-admin-theme-lite' ); ?></option>
						<option value='background-color' <?php selected( $plugin_settings['custom_login']['background']['type'], 'background-color' ); ?>><?php _e( 'Background Color', 'everest-admin-theme-lite'); ?></option>
					</select>
				</div>
			</div>

			<div class="eat-background-select-content">
				<div class="eat-background-color-content eat-background-color eat-common-content-wrap" style="display: <?php if(isset($plugin_settings['custom_login']['background']['type']) && $plugin_settings['custom_login']['background']['type'] =='background-color' ){ ?> block; <?php }else{ ?> none; <?php } ?>">
					<div class="eat-background-color-content-wrap">
						<div class="eat-options-wrap">
							<label for="eat-custom-login-background-color"><?php _e('Background Color', 'everest-admin-theme-lite' ); ?></label>
							<input id='eat-custom-login-background-color' type="text" name='everest_admin_theme[custom_login][background][background-color][color]' class='eat-color-picker' data-alpha="true" value='<?php if(isset($plugin_settings['custom_login']['background']['background-color']['color']) && $plugin_settings['custom_login']['background']['background-color']['color'] != '' ){ echo $plugin_settings['custom_login']['background']['background-color']['color']; } ?>' />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="eat-options-wrap">
		<div class='eat-style-label'><?php _e('Login Form settings', 'everest-admin-theme-lite'); ?></div>
			<label for="eat-hide-footer-completely"><?php _e( 'Theme selection?', 'everest-admin-theme-lite' ); ?></label>
			<div class="eat-input-field-wrap">
				<?php
				$login_form_templates = $eat_variables['login_form_templates'];
				if(isset($plugin_settings['custom_login']['login_form']['template']) && $plugin_settings['custom_login']['login_form']['template'] =='default'){
					$img_url = '';
				}
				
				?>
				<select name='everest_admin_theme[custom_login][login_form][template]' class="eat-selectbox-wrap eat-general-template-selection">
					<option value='default' data-img=''><?php _e('Default', 'everest-admin-theme-lite'); ?></option>
					<?php foreach($login_form_templates as $key=>$value){ ?>
						<option value="<?php echo $value['value']; ?>" data-img="<?php echo $value['img']; ?>" <?php if(isset( $plugin_settings['custom_login']['login_form']['template'] ) && $plugin_settings['custom_login']['login_form']['template'] == $value['value']){ echo "selected"; $img_url = $value['img']; } ?>><?php echo $value['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="eat-img-selector-media eat-image-placeholder">
				<img src="<?php echo esc_url($img_url); ?>" />
			</div>
		</div>
		<div class="eat-style-label">Hide/Show settings</div>
		<div class="eat-options-wrap">
			<label for="eat-custom-login-hide-wordpress-logo"><?php _e( 'Hide wordpress logo?', 'everest-admin-theme-lite' ); ?></label>
			<input type="checkbox" id='eat-custom-login-hide-wordpress-logo' name='everest_admin_theme[custom_login][login_form][wordpress-logo][hide]' class='eat-hide-wordpress-logo' <?php if(isset($plugin_settings['custom_login']['login_form']['wordpress-logo']['hide'])){ ?> checked <?php } ?> />
			<label for='eat-custom-login-hide-wordpress-logo'></label>
		</div>

		<div class="eat-options-wrap">
			<label for="eat-custom-login-hide-remember-me-checkbox"><?php _e( 'Hide remember me checkbox?', 'everest-admin-theme-lite' ); ?></label>
			<input type="checkbox" id='eat-custom-login-hide-remember-me-checkbox' name='everest_admin_theme[custom_login][login_form][remember-me-checkbox][hide]' class='eat-custom-login-hide-remember-me-checkbox' <?php if(isset($plugin_settings['custom_login']['login_form']['remember-me-checkbox']['hide'])){ ?> checked <?php } ?> />
			<label for='eat-custom-login-hide-remember-me-checkbox'></label>
		</div>
	</div>
</div>