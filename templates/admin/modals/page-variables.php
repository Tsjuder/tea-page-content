<form id="tpc-call-item-options-modal" class="tpc-dialog hidden" autocomplete="off">
	<div class="tpc-note">
		<?php _e('Here you can <b>override</b> each parameter of selected page that you see below. For example, you can set specific thumbnail for widget even if you set another thumbnail for page via edit post interface before.', 'tea-page-content'); ?>
	</div>

	<?php if(isset($page_variables) && is_array($page_variables)) : ?>
		<?php foreach ($page_variables as $variable => $params) : ?>
			<div class="tpc-dialog-ui-wrapper">
				<label class="tpc-modal-param-title" for="<?php echo $variable ?>">
					<?php echo ucwords($params['title']) ?>
				</label>

				<?php
				
				switch ($params['type']) :
					case 'text': ?>
						<input class="tpc-modal-ui-element" type="text" name="<?php echo $variable ?>" id="tpc-dialog-<?php echo $variable ?>" value="" />
					<?php 
					break;
					
					case 'textarea': ?>
						<textarea class="tpc-modal-ui-element" name="<?php echo $variable ?>" id="tpc-dialog-<?php echo $variable ?>"></textarea>
					<?php 
					break;
					
					case 'media': ?>
						<div class="tpc-modal-ui-section tpc-modal-media-element is-empty" data-preview-area="tpc-preview-area-<?php echo $variable ?>" data-storage="tpc-dialog-<?php echo $variable ?>">
							<div class="tpc-modal-ui-preview" id="tpc-preview-area-<?php echo $variable ?>"></div>

							<div class="tpc-button-group tpc-top-stacked">
								<button class="tpc-modal-ui-element button button-primary" type="button" data-target="media-open">
									<?php _e('Select image...', 'tea-page-content') ?>
								</button>

								<button type="button" data-target="media-delete" class="tpc-modal-ui-element tpc-delete-button button">
									<span class="tpc-dashicons tpc-dashicons-middled dashicons dashicons-no-alt"></span>
								</button>
							</div>

							<input class="is-resetable" type="hidden" name="<?php echo $variable ?>" id="tpc-dialog-<?php echo $variable ?>" value="" data-meaning="data-thumbnail-url" data-thumbnail-url="" />
						</div>
					<?php break;
				endswitch; 

				?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</form>