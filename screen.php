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
		<a href="#" class="page-title-action">
			<?php _e( 'Export as New Demo', '' ); ?>
		</a>
	</h2>
	<div id="elephant-instruction-card">
		<p>
			Hello! You are currently on the demo selection screen. 
			Hover through each demo screenshot and click 'Download' 
			button to download your favorite demo. Once you are finished 
			downloading the zip file, upload the file using the 'Upload' 
			field. After the file has already been uploaded, select the 
			file in the 'Dropdown' field and click 'export'.
		</p>
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
						</div>
					</div>

				</li>
			<?php } ?>
		</ul>

	</div>
</div>