<?php
defined('ABSPATH') or die("No script kiddies please!");
include('common/header.php');
	if(isset($_SESSION['appts_message'])){ ?>
		<div class='eat-message'><?php echo $_SESSION['appts_message']; ?></div>
		<?php
	unset($_SESSION['appts_message']);
	}
?>

<?php
if(isset($_GET['message']) && $_GET['message'] !=''){ ?>
	<div class='eat-message'>
		<?php
		$msgid = $_GET['message'];
		switch ($msgid) {
			case '1':
			?>
			<span class='eat-success'><?php _e("Table data imported successfully.", 'everest-admin-theme-lite'); ?></span>
			<?php
			break;

			case '2':
			?>
			<span class='eat-error'><?php _e("Something went wrong. Please try again later.",'everest-admin-theme-lite'); ?></span>
			<?php
			break;

			case '3':
			?>
			<span class='eat-error'><?php _e('Something went wrong. Please try again later.','everest-admin-theme-lite'); ?></span>
			<?php
			break;

			case '4':
			?>
			<span class='eat-error'><?php _e('Something went wrong. Please check the write permission of tmp folder inside the plugin\'s folder','everest-admin-theme-lite'); ?></span>
			<?php
			break;

			case '5':
			?>
			<span class='eat-error'><?php _e('Invalid File Extension.','everest-admin-theme-lite'); ?></span>
			<?php
			break;

			case '6':
			?>
			<span class='eat-success'><?php _e('table saved Successfully.','everest-admin-theme-lite'); ?></span>
			<?php
			break;

			default:
			?>
			<span class='eat-error'><?php _e('Something went wrong. Please try again.','everest-admin-theme-lite'); ?></span>
			<?php
			break;
		}
		?>
	</div>
	<?php
} ?>

<div class="eat-export-import-outer-wrapper">
	<div class='eat-export-outer-wrapper'>
		<div class="eat-export-wrapper">
			<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
			<div class="eat-export-inner-wrap">
				<input type="hidden" name="action" value="eat_export_plugin_settings_action"/>
		        <?php wp_nonce_field( 'eat-export-nonce', 'eat_export_nonce_field' ); ?>
				<label><?php _e('Export Plugin Settings', 'everest-admin-theme-lite'); ?></label>
				<input type="submit" name="export-pricing-table" value="Export"  />
			</div>
			</form>
		</div>
	</div>
	<div class='eat-import-outer-wrapper'>
		<div class='eat-import-wrapper'>
			<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" enctype="multipart/form-data">
                <input type="hidden" name="action" value="eat_import_plugin_settings_action"/>
                <?php wp_nonce_field( 'eat-import-nonce', 'eat_import_nonce_field' ); ?>
                <div class="eat-file-upload-field-wrapper">
                    <label><?php _e( 'Upload JSON File', 'everest-admin-theme-lite'); ?></label>
                    <div class="eat-field">
                        <input type="file" name="import_file"/>
                        <input type="submit" value="<?php _e( 'Import plugin settings', 'everest-admin-theme-lite' ); ?>"/></div>
                </div>
            </form>
		</div>
	</div>
</div>
<?php include('common/footer.php');