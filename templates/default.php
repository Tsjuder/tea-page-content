<?php 
/**
 * @param is-padded checkbox 0
 */

// prevention for old version of this plugin, @deprecated since 1.1
$isPadded = false;
if(isset($template_variables['is-padded']) && $template_variables['is-padded']) {
	$isPadded = true;
}
?>
<?php if(isset($entries) && $count) : ?>

<section class="tpc-block tpc-default<?php if($isPadded) echo '-padded'; ?>">
	<?php foreach ($entries as $key => $entry) : ?>
		<article class="tpc-entry-block">
			<?php if(isset($instance['show_page_thumbnail']) && $instance['show_page_thumbnail'] && $entry['thumbnail']) : ?>
			<?php if(array_key_exists('thumbnail', $instance) && !$instance['thumbnail']) :
				// @deprecated thumbnail param since 1.1
			else : ?>
				<div class="tpc-thumbnail">
					<?php if($instance['linked_page_thumbnail'] && $entry['link']) : ?>
						<a href="<?php echo $entry['link'] ?>"><?php echo $entry['thumbnail'] ?></a>
					<?php else : ?>
						<?php echo $entry['thumbnail'] ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php endif; ?>

			<?php if($instance['show_page_title'] || $instance['show_page_content']) : ?>
			<div class="tpc-body">
				<?php if($instance['show_page_title']) : ?>
					<h3 class="tpc-title">
					<?php if($instance['linked_page_title'] && $entry['link']) : ?>
						<a href="<?php echo $entry['link'] ?>"><?php echo $entry['title'] ?></a>
					<?php else : ?>
						<?php echo $entry['title'] ?>
					<?php endif; ?>
					</h3>
				<?php endif; ?>

				<?php if($instance['show_page_content']) : ?>
					<div class="tpc-content post-content">
						<?php echo $entry['content'] ?>
					</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			
		</article>
	<?php endforeach; ?>
</section>

<?php endif;