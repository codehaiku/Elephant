<?php
/**
 * This file handles the management screen for exporting
 * and importing demo under 'Tools' > 'Import Demo'
 *
 * @since 1.0
 * @author dunhakdis
 */
?>
<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2>
		
		<?php _e('Import Demo', 'elephant'); ?>
		
		<?php $export_process_screen = ''; ?>
		<?php $export_url = 'admin-ajax.php?action=elepant_processor&method=export'; ?>

		<a href="<?php echo esc_url( $export_url ); ?>" class="page-title-action">
			<?php _e( 'Export as New Demo', '' ); ?>
		</a>

	</h2>
	<div id="elephant-instruction-card">
		<p>
			<?php 
				_e(
					'Hello! You are currently on the demo selection screen. 
					Hover through each demo screenshot and click \'Download\' 
					button to download your favorite demo. Once you are finished 
					downloading the zip file, upload the file using the \'Upload\' 
					field. After the file has already been uploaded, select the 
					file in the \'Dropdown\' field and click \'export\'.',
					'elephant'		
				); 
			?>
			
		</p>
		<?php if ( get_option('elephant_exported_zip_file') ) { ?>

			<p id="elephant-exported-zip-link">
				
				<span class="dashicons dashicons-media-archive"></span>
					
				<?php $last_exported = get_option('elephant_exported_zip_file_timestamp', '') ?>

				<?php $file_time_diff = human_time_diff( strtotime( $last_exported ), current_time('timestamp') ) . ' ago'; ?>

				<?php echo sprintf( __('Demo Last Exported on %s: ', 'elephant'), '<u>'. $file_time_diff .'</u>' ); ?>

				<a href="<?php echo get_option('elephant_exported_zip_file'); ?>">(Download) </a>
				|
				<?php $delete_url = admin_url('admin-ajax.php?action=elepant_processor&method=delete'); ?>
				
				<a onclick="return confirm('You are about to delete the export file. Please confirm. Thanks!');" href="<?php echo esc_url( $delete_url ); ?>">Delete</a>
			</p>

		<?php } ?>
	</div>

	<div id="elephant-demo-selection-list">
		
		<ul>
			<?php foreach ( elephant_demos() as $demo ) { ?>

				<li class="list-item">
					
					<div class="list-item-thumbnail">
						<img src="<?php echo $demo['thumbnail']; ?>" alt="<?php echo $demo['label']; ?>" />
					</div>

					<div class="list-item-details">
						
						<div class="list-item-description">
							<h3><?php echo $demo['label']; ?></h3>
								<p><?php echo $demo['desc']; ?></p>
						</div>

						<div class="list-item-actions">
							<a href="#" class="button button-primary list-item-download">Download</a>
							<a href="#" class="button list-item-download">Preview</a>
						</div>
					</div>

				</li>
			<?php } ?>
		</ul>

	</div>
</div>