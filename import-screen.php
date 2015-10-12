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

		<?php $import_url = 'tools.php?page=elephant-import-page'; ?>

		<a href="<?php echo esc_url( $import_url ); ?>" class="page-title-action">
			<?php _e( 'Select Demo', '' ); ?>
		</a>

	</h2>
	<div id="elephant-instruction-card">
		<p>
		<strong>Heads Up!</strong>

			<?php 
				_e(
					'Demo will overwrite your post, pages, and other settings. Please 
					do not import the demo on a live website. Use the demo to kick start 
					your development process. The demo is not substitute for back-ups and other
					related functionalities. During the demo, please do not close your browser 
					and kindly wait for the process to finish. Thank you!',
					'elephant'		
				); 
			?>
		</p>
	</div>
	
	<hr>

	<form enctype="multipart/form-data" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
	
		<h4>
			<label for="demo_file">
				<?php _e('Demo Zip File:', 'elephant'); ?>
			</label>
		</h4>
		<p>
			<input id="demo_file" type="file" name="demo_file"/>

			<input type="hidden" name="action" value="elephant_import" />
			<input type="submit" value="Upload" class="button button-primary" /><br/><br/>

			<span class="description">
				<?php 
					_e(
						'Use the \'upload field\' above to upload the zip file of the demo 
						you\'ve previously downloaded in the select demo screen', 'elephant'
					); 
				?>
			</span>
		</p>
	</form>
</div>