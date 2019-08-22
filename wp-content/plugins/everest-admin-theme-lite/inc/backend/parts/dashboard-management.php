<?php defined('ABSPATH') or die("No script kiddies please!"); ?>
<div class="eat-tab-content eat-tab-dashboard-management" style="display: none;">
	<div class="eat-tab-content-header">
		<div class="eat-tab-content-header-title"><?php _e('Dashboard Management', 'everest-admin-theme-lite'); ?></div>
	</div>
	<div class="eat-tab-content-body">
		<div class="eat-hide-show-wrap">
			<div class="eat-style-label"><?php _e('Hide/Show options', 'everest-admin-theme-lite'); ?></div>
			<div class="eat-options-wrap">
				<label for='eat-dashboard-hide-welcome-panel-widget'><?php _e("Hide 'Welcome Panel' widget", 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap">
				<input type="checkbox" id='eat-dashboard-hide-welcome-panel-widget' name='everest_admin_theme[dashboard][hide_welcome_panel]' class='eat-dashboard-hide-welcome-panel-widget' <?php if(isset($plugin_settings['dashboard']['hide_welcome_panel'])){ ?> checked <?php } ?> />
				<label for='eat-dashboard-hide-welcome-panel-widget'></label>
				</div>
			</div>

			<div class="eat-options-wrap">
				<label for='eat-dashboard-hide-wordpress-news-events-widget'><?php _e("Hide 'Wordpress events and news' widget", 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap">
					<input type="checkbox" id='eat-dashboard-hide-wordpress-news-events-widget' name='everest_admin_theme[dashboard][hide_wordpress_events_news]' class='eat-dashboard-hide-wordpress-news-events-widget' <?php if(isset($plugin_settings['dashboard']['hide_wordpress_events_news'])){ ?> checked <?php } ?> />
					<label for='eat-dashboard-hide-wordpress-news-events-widget'></label>
				</div>
			</div>

			<div class="eat-options-wrap">
				<label for='eat-dashboard-hide-quick-draft'><?php _e("Hide 'Quick draft' widget", 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap">
					<input type="checkbox" id='eat-dashboard-hide-quick-draft' name='everest_admin_theme[dashboard][hide_quick_draft]' class='eat-dashboard-hide-quick-draft' <?php if(isset($plugin_settings['dashboard']['hide_quick_draft'])){ ?> checked <?php } ?> />
					<label for='eat-dashboard-hide-quick-draft'></label>
				</div>
			</div>

			<div class="eat-options-wrap">
				<label for='eat-dashboard-hide-at-a-glance-widget'><?php _e("Hide 'At a glance' widgets", 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap">
				<input type="checkbox" id='eat-dashboard-hide-at-a-glance-widget' name='everest_admin_theme[dashboard][hide_at_a_glance]' class='eat-dashboard-hide-at-a-glance-widget' <?php if(isset($plugin_settings['dashboard']['hide_at_a_glance'])){ ?> checked <?php } ?> />
				<label for='eat-dashboard-hide-at-a-glance-widget'></label>
				</div>
			</div>

			<div class="eat-options-wrap">
				<label for='eat-dashboard-hide-activity-widget'><?php _e("Hide 'Activity' Widget", 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap">
					<input type="checkbox" id='eat-dashboard-hide-activity-widget' name='everest_admin_theme[dashboard][hide_activity]' class='eat-dashboard-hide-activity-widget' <?php if(isset($plugin_settings['dashboard']['hide_activity'])){ ?> checked <?php } ?> />
					<label for='eat-dashboard-hide-activity-widget'></label>
				</div>
			</div>

			<div class="eat-options-wrap">
				<label for='eat-dashboard-hide-recent-draft-widget'><?php _e("Hide 'Recent Drafts' Widget", 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap">
					<input type="checkbox" id='eat-dashboard-hide-recent-draft-widget' name='everest_admin_theme[dashboard][hide_recent_draft]' class='eat-dashboard-hide-recent-draft-widget' <?php if(isset($plugin_settings['dashboard']['hide_recent_draft'])){ ?> checked <?php } ?> />
					<label for='eat-dashboard-hide-recent-draft-widget'></label>
				</div>
			</div>

			<div class="eat-options-wrap">
				<label for='eat-dashboard-hide-recent-comments-widget'><?php _e("Hide 'Recent Comments' Widget", 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap">
					<input type="checkbox" id='eat-dashboard-hide-recent-comments-widget' name='everest_admin_theme[dashboard][hide_recent_comments]' class='eat-dashboard-hide-recent-comments-widget' <?php if(isset($plugin_settings['dashboard']['hide_recent_comments'])){ ?> checked <?php } ?> />
					<label for='eat-dashboard-hide-recent-comments-widget'></label>
				</div>
			</div>

			<div class="eat-options-wrap">
				<label for='eat-dashboard-hide-incoming-links-widget'><?php _e("Hide 'Incoming Links' Widget", 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap">
					<input type="checkbox" id='eat-dashboard-hide-incoming-links-widget' name='everest_admin_theme[dashboard][hide_incoming_links]' class='eat-dashboard-hide-incoming-links-widget' <?php if(isset($plugin_settings['dashboard']['hide_incoming_links'])){ ?> checked <?php } ?> />
					<label for='eat-dashboard-hide-incoming-links-widget'></label>
				</div>
			</div>

			<div class="eat-options-wrap">
				<label for='eat-dashboard-hide-plugins-widget'><?php _e("Hide 'Plugins' Widget", 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap">
					<input type="checkbox" id='eat-dashboard-hide-plugins-widget' name='everest_admin_theme[dashboard][hide_plugins]' class='eat-dashboard-hide-plugins-widget' <?php if(isset($plugin_settings['dashboard']['hide_plugins'])){ ?> checked <?php } ?> />
					<label for='eat-dashboard-hide-plugins-widget'></label>
				</div>
			</div>

			<div class="eat-options-wrap">
				<label for='eat-dashboard-hide-wordpress-blog-widget'><?php _e("Hide 'WordPress blog' Widget", 'everest-admin-theme-lite'); ?></label>
				<div class="eat-input-field-wrap">
					<input type="checkbox" id='eat-dashboard-hide-wordpress-blog-widget' name='everest_admin_theme[dashboard][hide_wordpress_blog]' class='eat-dashboard-hide-wordpress-blog-widget' <?php if(isset($plugin_settings['dashboard']['hide_wordpress_blog'])){ ?> checked <?php } ?> />
					<label for='eat-dashboard-hide-wordpress-blog-widget'></label>
				</div>
			</div>
		</div>
	</div>
</div>