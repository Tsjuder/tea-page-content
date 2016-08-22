<?php if(!empty($template_variables)) : ?>

<div class="tpc-template-params-inner">
	<h4><?php _e('Template Variables', 'tea-page-content') ?></h4>
	<?php foreach ($template_variables as $variable) : ?>
		<?php if(isset($mask)) : ?>
			<?php $variableID = str_replace('{mask}', $variable['name'], $mask); ?>
			<?php $variableName = str_replace('{mask}', $variable['name'], $mask); ?>
		<?php elseif(isset($bind)) : ?>
			<?php $variableID = $bind->get_field_id($variable['name']); ?>
			<?php $variableName = $bind->get_field_name($variable['name']); ?>
		<?php else : continue; endif; ?>
		
		<?php $variableValue = ''; $isDefault = false; $isExists = false;
		if(isset($instance) && isset($instance['template_variables']) && array_key_exists($variable['name'], $instance['template_variables'])) {
			$isExists = true;
			$variableValue = $instance['template_variables'][$variable['name']];
		} else {
			$isDefault = true;
			$variableValue = reset($variable['defaults']);
		}?>
		
		<p>
			<?php if($variable['type'] !== 'caption') : ?>
				<label for="<?php echo $variableID ?>">
					<?php echo ucwords(str_replace(array('-','_'), ' ', $variable['name'])) ?>:
				</label>
			<?php endif; ?>
			
			<?php switch ($variable['type']) : default: break; ?>
				<?php case 'caption': ?>
					<span class="tpc-template-caption">
						<?php echo trim($variableValue, '"') ?>
					</span>
				<?php break; ?>

				<?php case 'text': ?>
					<input type="text" class="widefat" id="<?php echo $variableID ?>" name="<?php echo $variableName ?>" value="<?php echo $variableValue ?>" />
				<?php break; ?>

				<?php case 'textarea': ?>
					<textarea class="widefat" id="<?php echo $variableID ?>" name="<?php echo $variableName ?>"><?php echo $variableValue ?></textarea>
				<?php break; ?>

				<?php case 'checkbox': ?>
					<?php $checked = '';
					if
						(
							(isset($instance['template_variables'][$variable['name']]) && !empty($variableValue)) 
							OR
							($variableValue && $isDefault && !$isExists)
						) 
					{
						$checked = 'checked';
					} ?>

					<input type="checkbox" class="widefat" id="<?php echo $variableID ?>" name="<?php echo $variableName ?>" value="<?php echo $variable['name'] ?>" <?php echo $checked ?> />
				<?php break; ?>

				<?php case 'select': ?>
					<select class="widefat" id="<?php echo $variableID ?>" name="<?php echo $variableName ?>">
					<?php foreach ($variable['defaults'] as $value) : ?>
						<?php $selected = ($variableValue == $value) ? 'selected="selected"' : ''; ?>
						<option value="<?php echo $value ?>" <?php echo $selected ?>>
							<?php echo $value ?>
						</option>
					<?php endforeach; ?>
					</select>
				<?php break; ?>

			<?php endswitch; ?>
		</p>

	<?php endforeach; ?>
</div>

<?php endif;