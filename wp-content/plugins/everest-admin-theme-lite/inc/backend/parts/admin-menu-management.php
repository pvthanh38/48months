<?php defined('ABSPATH') or die("No script kiddies please!"); ?>
<div class="eat-tab-content eat-tab-admin-menu-management" style="display: none;">
	<div class="eat-tab-content-header">
		<div class="eat-tab-content-header-title"><?php _e('Admin Menu Management', 'everest-admin-theme-lite'); ?></div>
	</div>
	<div class='eat-tab-content-body'>
	<?php
	///////////////////////////////////////////////////////////////////////////////////////////////
	$eat_admin_menu_slug_list =array();

//////////////////////////////////////////////////////////////////////////////////////////////
	?>
		<div class="eat-admin-menu-settings-wrap">
			<div class="eat-admin-menu-display-settings">
				<div class="eat-style-label"><?php _e('Display Settings', 'everest-admin-theme-lite'); ?></div>
				<div class="eat-select-wrap">
					<div class="eat-options-wrap">
						<label for='eat-admin-bar-backend-settings'><?php _e('Outer wrap menu background settings', 'everest-admin-theme-lite'); ?></label>
						<div class="eat-input-field-wrap eat-options-select-outer-wrap">
							<select name='everest_admin_theme[admin_menu][outer_background_settings][menu][type]' class="eat-selectbox-wrap eat-options-select-wrap">
								<option value ='default' <?php if(isset($plugin_settings['admin_menu']['outer_background_settings']['menu']['type']) ) { selected( $plugin_settings['admin_menu']['outer_background_settings']['menu']['type'], 'default' ); } ?>><?php _e('Default', 'everest-admin-theme-lite'); ?></option>
								<option value ='background-color' <?php if(isset($plugin_settings['admin_menu']['outer_background_settings']['menu']['type']) ) { selected( $plugin_settings['admin_menu']['outer_background_settings']['menu']['type'], 'background-color' ); } ?>><?php _e('Background color', 'everest-admin-theme-lite'); ?></option>
							</select>
						</div>
					</div>

					<div class="eat-options-select-content-wrap">
						<div class="eat-common-content-wrap eat-background-color-content-wrap" style='display: <?php if(isset($plugin_settings['admin_menu']['outer_background_settings']['menu']['type']) && $plugin_settings['admin_menu']['outer_background_settings']['menu']['type'] =='background-color' ){ ?> block; <?php }else{ ?> none; <?php } ?>'>
							<div class="eat-options-wrap">
								<label for ="eat-background-background-color"><?php _e('Background Color', 'everest-admin-theme-lite' ); ?></label>
								<input id  ='eat-background-background-color' type="text" name='everest_admin_theme[admin_menu][outer_background_settings][menu][background-color][color]' class='eat-color-picker' data-alpha="true" value="<?php if(isset($plugin_settings['admin_menu']['outer_background_settings']['menu']['background-color']['color']) && $plugin_settings['admin_menu']['outer_background_settings']['menu']['background-color']['color'] != '' ){ echo $plugin_settings['admin_menu']['outer_background_settings']['menu']['background-color']['color']; } ?>" />
							</div>
						</div>
					</div>
				</div>

				<div class="eat-select-wrap">
					<div class="eat-options-wrap">
						<label for='eat-admin-bar-backend-settings'><?php _e( 'Outer wrap submenu background settings', 'everest-admin-theme-lite' ); ?></label>
						<div class="eat-input-field-wrap eat-options-select-outer-wrap">
							<select name='everest_admin_theme[admin_menu][outer_background_settings][sub_menu][type]' class="eat-selectbox-wrap eat-options-select-wrap">
								<option value ='default' <?php if(isset($plugin_settings['admin_menu']['outer_background_settings']['sub_menu']['type'])){ selected( $plugin_settings['admin_menu']['outer_background_settings']['sub_menu']['type'], 'default' ); } ?>><?php _e('Default', 'everest-admin-theme-lite'); ?></option>
								<option value ='background-color' <?php if(isset($plugin_settings['admin_menu']['outer_background_settings']['sub_menu']['type'])){ selected( $plugin_settings['admin_menu']['outer_background_settings']['sub_menu']['type'], 'background-color' ); } ?>><?php _e('Background color', 'everest-admin-theme-lite'); ?></option>
							</select>
						</div>
					</div>

					<div class="eat-options-select-content-wrap">
						<div class="eat-common-content-wrap eat-background-color-content-wrap" style='display: <?php if(isset($plugin_settings['admin_menu']['outer_background_settings']['sub_menu']['type']) && $plugin_settings['admin_menu']['outer_background_settings']['sub_menu']['type'] =='background-color' ){ ?> block; <?php }else{ ?> none; <?php } ?>'>
							<div class="eat-options-wrap">
								<label for ="eat-background-background-color"><?php _e('Background Color', 'everest-admin-theme-lite' ); ?></label>
								<input id  ='eat-background-background-color' type="text" name='everest_admin_theme[admin_menu][outer_background_settings][sub_menu][background-color][color]' class='eat-color-picker' data-alpha="true" value="<?php if(isset($plugin_settings['admin_menu']['outer_background_settings']['sub_menu']['background-color']['color']) && $plugin_settings['admin_menu']['outer_background_settings']['sub_menu']['background-color']['color'] != '' ){ echo $plugin_settings['admin_menu']['outer_background_settings']['sub_menu']['background-color']['color']; } ?>" />
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>