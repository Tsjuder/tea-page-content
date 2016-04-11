<?php
/**
 * @param caption caption "This template is deprecated. Use 'default' instead."
 */

?>

<?php if(isset($entries) && count($entries)) : ?>

<section class="tpc-block tpc-default-padded">
	<?php foreach ($entries as $key => $entry) : ?>
		<article class="tpc-entry-block">
			<?php if(isset($instance['show_page_thumbnail']) && $instance['show_page_thumbnail'] && $entry['thumbnail']) : ?>
			<?php if(array_key_exists('thumbnail', $instance) && !$instance['thumbnail']) :
				// @deprecated thumbnail param since 1.1
			else : ?>
				<div class="tpc-thumbnail">
					<?php echo $entry['thumbnail'] ?>
				</div>
			<?php endif; ?>
			<?php endif; ?>

			<div class="tpc-body">
				<h3 class="tpc-title"><?php echo $entry['title'] ?></h3>
				<div class="tpc-content post-content">
					<?php echo $entry['content'] ?>
				</div>
			</div>
			
		</article>
	<?php endforeach; ?>
</section>

<?php endif;