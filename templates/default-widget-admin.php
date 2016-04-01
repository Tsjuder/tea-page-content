<p>
	<label for="<?php echo $bind->get_field_id('title') ?>">
		<?php _e('Title', 'tea-page-content'); ?>:
	</label>
	<input class="widefat" type="text" id="<?php echo $bind->get_field_id('title') ?>" name="<?php echo $bind->get_field_name('title') ?>" value="<?php echo $instance['title'] ?>" />
</p>

<?php if(is_array($entries) && count($entries)) : ?>
	<?php $instance['posts'] = unserialize($instance['posts']) ? unserialize($instance['posts']) : array();  ?>

	<span>
		<?php _e('Please, select one or more posts from lists below:', 'tea-page-content') ?>
	</span>

	<div class="tpc-posts-list">
		<?php foreach ($entries as $postType => $postsByType) : ?>
		<div class="tpc-post-type-block tpc-accordeon">
		
			<div class="tpc-accordeon-top">
				<h4><?php echo $postType ?></h4>
			</div>

			<div class="tpc-accordeon-body">
			<?php foreach ($postsByType as $postKey => $postData) : ?>
				<?php $checked = in_array($postData['id'], $instance['posts']) ? 'checked' : ''; ?>
				<label>
					<input type="checkbox" name="<?php echo $bind->get_field_name('posts') ?>[]" id="<?php echo $bind->get_field_id('posts') ?>" value="<?php echo $postData['id'] ?>" <?php echo $checked ?> />
					<span><?php echo $postData['title'] ?></span>
				</label>
			<?php endforeach; ?>
			</div>

		</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<p class="tpc-preloader is-hidden">
	<label for="<?php echo $bind->get_field_id('template') ?>">
		<?php _e('Template', 'tea-page-content'); ?>:
	</label>
	<select class="widefat tpc-template-list" data-variables-area="tpc-<?php echo $bind->get_field_id('template-variables') ?>-wrapper" id="<?php echo $bind->get_field_id('template') ?>" name="<?php echo $bind->get_field_name('template') ?>" autocomplete="off">
		<?php foreach ($templates as $alias) : ?>
			<?php $selected = $alias == $instance['template'] ? 'selected' : ''; ?>
			<option value="<?php echo $alias ?>" <?php echo $selected ?>>
				<?php echo ucwords(str_replace(array('.php', 'tpc-', '-'), ' ', $alias)) ?>
			</option>
		<?php endforeach; ?>
	</select>
</p>

<div class="tpc-template-params" id="tpc-<?php echo $bind->get_field_id('template-variables') ?>-wrapper" data-mask-name="<?php echo $bind->get_field_name('{mask}') ?>">
	<?php echo $partials['template_variables'] ?>
</div>

<p>
	<label for="<?php echo $bind->get_field_id('order') ?>">
		<?php _e('Order (by date)', 'tea-page-content'); ?>:
	</label>
	<select class="widefat" id="<?php echo $bind->get_field_id('order') ?>" name="<?php echo $bind->get_field_name('order') ?>">
		<option value="desc" <?php if($instance['order'] == 'desc') : echo 'selected'; endif; ?>>
			<?php _e('Descending', 'tea-page-content') ?>
		</option>
		<option value="asc" <?php if($instance['order'] == 'asc') : echo 'selected'; endif; ?>>
			<?php _e('Ascending', 'tea-page-content') ?>
		</option>
	</select>
</p>

<p>
	<label for="<?php echo $bind->get_field_id('show_page_thumbnail'); ?>">
		<?php 
		$checked = $instance['show_page_thumbnail'] ? 'checked' : ''; 

		// @deprecated since 1.1
		if(array_key_exists('thumbnail', $instance) && !$instance['thumbnail']) $checked = '';
		?>
		<input class="widefat" type="checkbox" id="<?php echo $bind->get_field_id('show_page_thumbnail'); ?>" name="<?php echo $bind->get_field_name('show_page_thumbnail'); ?>" value="1" <?php echo $checked ?> />
		<span><?php _e('Show page thumbnail', 'tea-page-content'); ?></span>
	</label>

	<br />

	<label for="<?php echo $bind->get_field_id('show_page_title'); ?>">
		<?php $checked = $instance['show_page_title'] ? 'checked' : ''; ?>
		<input class="widefat" type="checkbox" id="<?php echo $bind->get_field_id('show_page_title'); ?>" name="<?php echo $bind->get_field_name('show_page_title'); ?>" value="1" <?php echo $checked ?> />
		<span><?php _e('Show page title', 'tea-page-content'); ?></span>
	</label>

	<br />

	<label for="<?php echo $bind->get_field_id('show_page_content'); ?>">
		<?php $checked = $instance['show_page_content'] ? 'checked' : ''; ?>
		<input class="widefat" type="checkbox" id="<?php echo $bind->get_field_id('show_page_content'); ?>" name="<?php echo $bind->get_field_name('show_page_content'); ?>" value="1" <?php echo $checked ?> />
		<span><?php _e('Show page content', 'tea-page-content'); ?></span>
	</label>

	<br />

	<label for="<?php echo $bind->get_field_id('linked_page_title'); ?>">
		<?php $checked = $instance['linked_page_title'] ? 'checked' : ''; ?>
		<input class="widefat" type="checkbox" id="<?php echo $bind->get_field_id('linked_page_title'); ?>" name="<?php echo $bind->get_field_name('linked_page_title'); ?>" value="1" <?php echo $checked ?> />
		<span><?php _e('Linked page title (if possible)', 'tea-page-content'); ?></span>
	</label>

	<br />

	<label for="<?php echo $bind->get_field_id('linked_page_thumbnail'); ?>">
		<?php $checked = $instance['linked_page_thumbnail'] ? 'checked' : ''; ?>
		<input class="widefat" type="checkbox" id="<?php echo $bind->get_field_id('linked_page_thumbnail'); ?>" name="<?php echo $bind->get_field_name('linked_page_thumbnail'); ?>" value="1" <?php echo $checked ?> />
		<span><?php _e('Linked page thumbnail (if possible)', 'tea-page-content'); ?></span>
	</label>
</p>