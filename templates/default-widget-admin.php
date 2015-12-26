<p>
	<label for="<?php echo $bind->get_field_id('title'); ?>">
		<?php _e('Title', 'tea-page-content'); ?>:
	</label>
	<input class="widefat" type="text" id="<?php echo $bind->get_field_id('title'); ?>" name="<?php echo $bind->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
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
					<input type="checkbox" name="<?php echo $bind->get_field_name('posts'); ?>[]" id="<?php echo $bind->get_field_id('posts'); ?>" value="<?php echo $postData['id'] ?>" <?php echo $checked ?> />
					<span><?php echo $postData['title'] ?></span>
				</label>
			<?php endforeach; ?>
			</div>

		</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<p>
	<label for="<?php echo $bind->get_field_id('template'); ?>">
		<?php _e('Template', 'tea-page-content'); ?>:
	</label>
	<select class="widefat" id="<?php echo $bind->get_field_id('template'); ?>" name="<?php echo $bind->get_field_name('template'); ?>">
		<?php foreach ($templates as $alias) : ?>
			<?php $selected = $alias == $instance['template'] ? 'selected' : ''; ?>
			<option value="<?php echo $alias ?>" <?php echo $selected ?>>
				<?php echo ucwords(str_replace(array('.php', 'tpc-', '-'), ' ', $alias)) ?>
			</option>
		<?php endforeach; ?>
	</select>
</p>

<p>
	<label for="<?php echo $bind->get_field_id('order'); ?>">
		<?php _e('Order (by date)', 'tea-page-content'); ?>:
	</label>
	<select class="widefat" id="<?php echo $bind->get_field_id('order'); ?>" name="<?php echo $bind->get_field_name('order'); ?>">
		<option value="desc" <?php if($instance['order'] == 'desc') : echo 'selected'; endif; ?>>
			<?php _e('Descending', 'tea-page-content') ?>
		</option>
		<option value="asc" <?php if($instance['order'] == 'asc') : echo 'selected'; endif; ?>>
			<?php _e('Ascending', 'tea-page-content') ?>
		</option>
	</select>
</p>

<p>
	<label for="<?php echo $bind->get_field_id('thumbnail'); ?>">
		<?php $checked = $instance['thumbnail'] ? 'checked' : ''; ?>
		<input class="widefat" type="checkbox" id="<?php echo $bind->get_field_id('thumbnail'); ?>" name="<?php echo $bind->get_field_name('thumbnail'); ?>" value="1" <?php echo $checked ?> />
		<span><?php _e('Thumbnail', 'tea-page-content'); ?></span>
	</label>
</p>