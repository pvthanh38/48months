<?php defined('ABSPATH') or die("No script kiddies please!"); ?>
<div class="eat-tab-content eat-tab-admin-bar-management" style="display: none;">
	<div class="eat-tab-content-header">
		<div class="eat-tab-content-header-title"><?php _e('Admin bar Management', 'everest-admin-theme-lite'); ?></div>
	</div>
	<div class="eat-tab-content-body">
		<div class="eat-options-wrap">
			<label for='eat-admin-bar-template-selection'><?php _e('Admin bar position', 'everest-admin-theme-lite'); ?></label>
			<div class="eat-input-field-wrap">
				<select id='eat-admin-bar-template-selection' name='everest_admin_theme[admin_bar][layout]' class="eat-selectbox-wrap eat-selectbox-wrap eat-admin-bar-template-selection eat-select-options eat-dropdown-selector" >
					<option value='fixed' <?php selected($plugin_settings['admin_bar']['layout'], 'fixed'); ?> ><?php _e( 'Fixed', 'everest-admin-theme-lite' ); ?></option>
					<option value='absolute' <?php selected($plugin_settings['admin_bar']['layout'], 'absolute'); ?>> <?php _e( 'Absolute', 'everest-admin-theme-lite'); ?></option>
				</select>
			</div>
		</div>

		<div class="eat-options-wrap">
			<label for='eat-admin-bar-hide-in-frontend'><?php _e('Hide Admin bar in frontend', 'everest-admin-theme-lite'); ?></label>
			<div class="eat-input-field-wrap">
				<div class="eat-input-field-wrap">
					<input type="checkbox" name="everest_admin_theme[admin_bar][hide_in_frontend]" <?php if(isset($plugin_settings['admin_bar']['hide_in_frontend'])){ ?> checked <?php } ?> class="eat-admin-bar-enable-option ec-checkbox-enable-option" id="eat-admin-bar-hide-in-frontend" value="1">
				<label for="eat-admin-bar-hide-in-frontend"></label>
				</div>
			</div>
		</div>

		<div class="eat-admin-bar-display-settings">
			<div class="eat-admin-bar-outer-backend-settings">
				<div class="eat-style-label"><?php _e('Outer wrapper Menu background Settings', 'everest-admin-theme-lite'); ?></div>
				<div class="eat-select-wrap">
					<div class="eat-options-wrap">
						<label for='eat-admin-bar-backend-settings'><?php _e('Menu Background selection', 'everest-admin-theme-lite'); ?></label>
						<div class="eat-input-field-wrap eat-options-select-outer-wrap">
							<select name='everest_admin_theme[admin_bar][outer_background_settings][menu][background_selection][type]' class="eat-selectbox-wrap eat-options-select-wrap">
								<option value ='default' <?php if(isset($plugin_settings['admin_bar']['outer_background_settings']['menu']['background_selection']['type'])){ selected( $plugin_settings['admin_bar']['outer_background_settings']['menu']['background_selection']['type'], 'default' ); } ?>><?php _e('Default', 'everest-admin-theme-lite'); ?></option>
								<option value ='background-color' <?php if(isset($plugin_settings['admin_bar']['outer_background_settings']['menu']['background_selection']['type'])){ selected( $plugin_settings['admin_bar']['outer_background_settings']['menu']['background_selection']['type'], 'background-color' ); } ?>><?php _e('Background color', 'everest-admin-theme-lite'); ?></option>
							</select>
						</div>
					</div>

					<div class="eat-options-select-content-wrap">
						<div class="eat-common-content-wrap eat-background-color-content-wrap" style='display: <?php if(isset($plugin_settings['admin_bar']['outer_background_settings']['menu']['background_selection']['type']) && $plugin_settings['admin_bar']['outer_background_settings']['menu']['background_selection']['type'] =='background-color' ){ ?> block; <?php }else{ ?> none; <?php } ?>'>
							<div class="eat-options-wrap">
								<label for="eat-background-background-color"><?php _e('Background Color', 'everest-admin-theme-lite' ); ?></label>
								<input id='eat-background-background-color' type="text" name='everest_admin_theme[admin_bar][outer_background_settings][menu][background_selection][background-color][color]' class='eat-color-picker' data-alpha="true" value='<?php if(isset($plugin_settings['admin_bar']['outer_background_settings']['menu']['background_selection']['background-color']['color']) && $plugin_settings['admin_bar']['outer_background_settings']['menu']['background_selection']['background-color']['color'] != '' ){ echo $plugin_settings['admin_bar']['outer_background_settings']['menu']['background_selection']['background-color']['color']; } ?>' />
							</div>
						</div>
					</div>
				</div>

				<div class="eat-style-label"><?php _e('Outer wrapper Submenu background Settings', 'everest-admin-theme-lite'); ?></div>
				<div class="eat-select-wrap">
					<div class="eat-options-wrap">
						<label for='eat-admin-bar-backend-settings'><?php _e('Submenu Background selection', 'everest-admin-theme-lite'); ?></label>
						<div class="eat-input-field-wrap eat-options-select-outer-wrap">
							<select name='everest_admin_theme[admin_bar][outer_background_settings][sub_menu][background_selection][type]' class="eat-selectbox-wrap eat-options-select-wrap">
								<option value ='default' <?php if(isset($plugin_settings['admin_bar']['outer_background_settings']['sub_menu']['background_selection']['type'])){ selected( $plugin_settings['admin_bar']['outer_background_settings']['sub_menu']['background_selection']['type'], 'default' ); } ?>><?php _e('Default', 'everest-admin-theme-lite'); ?></option>
								<option value ='background-color' <?php if(isset($plugin_settings['admin_bar']['outer_background_settings']['sub_menu']['background_selection']['type'])){ selected( $plugin_settings['admin_bar']['outer_background_settings']['sub_menu']['background_selection']['type'], 'background-color' ); } ?>><?php _e('Background color', 'everest-admin-theme-lite'); ?></option>
							</select>
						</div>
					</div>

					<div class="eat-options-select-content-wrap">
						<div class="eat-common-content-wrap eat-background-color-content-wrap" style='display: <?php if(isset($plugin_settings['admin_bar']['outer_background_settings']['sub_menu']['background_selection']['type']) && $plugin_settings['admin_bar']['outer_background_settings']['sub_menu']['background_selection']['type'] =='background-color' ){ ?> block; <?php }else{ ?> none; <?php } ?>'>
							<div class="eat-options-wrap">
								<label for="eat-background-background-color"><?php _e('Background Color', 'everest-admin-theme-lite' ); ?></label>
								<input id='eat-background-background-color' type="text" name='everest_admin_theme[admin_bar][outer_background_settings][sub_menu][background_selection][background-color][color]' class='eat-color-picker' data-alpha="true" value='<?php if(isset($plugin_settings['admin_bar']['outer_background_settings']['sub_menu']['background_selection']['background-color']['color']) && $plugin_settings['admin_bar']['outer_background_settings']['sub_menu']['background_selection']['background-color']['color'] != '' ){ echo $plugin_settings['admin_bar']['outer_background_settings']['sub_menu']['background_selection']['background-color']['color']; } ?>' />
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>