<?php defined('ABSPATH') or die("No script kiddies please!"); ?>
<div class='eat-tab-content eat-tab-general-management' style='display: none;'>
	<div class="eat-tab-content-header">
		<div class='eat-tab-content-header-title'><?php _e('General Management' , 'everest-admin-theme-lite'); ?></div>
	</div>
	<div class='eat-tab-content-body'>
		<div class="eat-general-settings-options-wrap eat-options-wrap-outer">
			<div class="eat-style-label"><?php _e("Dashboard Settings", 'everest-admin-theme-lite'); ?></div>
			<div class="eat-options-wrap">
				<label for="eat-background-option"><?php _e('Background selection', 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap eat-background-select-wrap">
					<select id='eat-background-options' name='everest_admin_theme[general-settings][background][type]' class="eat-selectbox-wrap eat-select-options eat-background-selector">
						<option value='' ><?php _e( 'Default', 'everest-admin-theme-lite' ); ?></option>
						<option value='background-color' <?php selected( $plugin_settings['general-settings']['background']['type'], 'background-color' ); ?>><?php _e( 'Background Color', 'everest-admin-theme-lite'); ?></option>
					</select>
				</div>
			</div>
			<div class="eat-background-select-content">
				<div class="eat-background-color-content eat-background-color eat-common-content-wrap" style="display: <?php if(isset($plugin_settings['general-settings']['background']['type']) && $plugin_settings['general-settings']['background']['type'] =='background-color' ){ ?> block; <?php }else{ ?> none; <?php } ?>">
					<div class="eat-background-color-content-wrap">
						<div class="eat-options-wrap">
							<label for="eat-background-background-color"><?php _e('Background Color', 'everest-admin-theme-lite' ); ?></label>
							<input id='eat-background-background-color' type="text" name='everest_admin_theme[general-settings][background][background-color][color]' class='eat-color-picker' data-alpha="true" value='<?php if(isset($plugin_settings['general-settings']['background']['background-color']['color']) && $plugin_settings['general-settings']['background']['background-color']['color'] != '' ){ echo $plugin_settings['general-settings']['background']['background-color']['color']; } ?>' />
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="eat-style-label" ><?php _e('Favicon Settings', 'everest-admin-theme-lite'); ?></div>
		<div class="eat-image-selection-wrap">
			<div class="eat-general-settings-options-wrap eat-options-wrap">
				<label for='eat-general-settings'><?php _e('Custom Favicon', 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap">
					<input type="url" id='favicon-upload' name='everest_admin_theme[general-settings][favicon][url]' class='eat-image-upload-url' value="<?php if(isset($plugin_settings['general-settings']['favicon']['url']) && $plugin_settings['general-settings']['favicon']['url'] != '' ){ echo esc_url($plugin_settings['general-settings']['favicon']['url']); } ?>" />
					<input type="button" class='eat-button eat-image-upload-button' value="<?php _e('Upload Image', 'everest-admin-theme-lite'); ?>" />
				</div>
			</div>
			<div class="eat-image-preview eat-image-placeholder">
				<?php
				if(isset($plugin_settings['general-settings']['favicon']['url']) && $plugin_settings['general-settings']['favicon']['url'] != '' ){
					$image_url = esc_url($plugin_settings['general-settings']['favicon']['url']);
				}else{
					$image_url = ' ';
				} ?>
				<img src="<?php echo $image_url; ?>" alt='site favicon'/>
			</div>
		</div>
	</div>
</div>