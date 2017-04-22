<?php 
/**
 * @param caption caption "Simple template with column-based layout."
 * 
 * @param column-count jqueryui:spinner 1-24
 * @param ordering-type select horizontal|vertical
 */

$column_count = (int)$template_variables['column-count'];

$items_per_col = floor($count / $column_count);
$remainder = $count - ($items_per_col * $column_count);

$width = 100 / $column_count; // 100% divided on column count

$counter = 0; ?>

<?php if(isset($instance['title']) && $caller === 'shortcode') : ?>
    <h2 class="tpc-shortcode-main-title"><?php echo $instance['title']; ?></h2>
<?php endif; ?>

<section class="tpc-block tpc-waterfall tpc-waterfall-<?php echo $column_count ?>">

<?php if($template_variables['ordering-type'] === 'horizontal') :

    for ($i = 0; $i < $column_count; $i++) : ?>

    <div class="tpc-waterfall-col" style="width: <?php echo $width?>%">
        <?php 
        $incol_limit = $items_per_col;
        if($remainder) {
            $incol_limit++;
            $remainder--;
        }

        for ($y = 0; $y < $incol_limit; $y++) :

            $entry = $entries[$counter];
         ?>
            <article class="tpc-entry-block">
                <?php if(isset($instance['show_page_thumbnail']) && $instance['show_page_thumbnail'] && $entry['thumbnail']) : ?>
                    <div class="tpc-thumbnail">
                        <?php if($instance['linked_page_thumbnail'] && $entry['link']) : ?>
                            <a class="tpc-thumbnail-link" href="<?php echo $entry['link'] ?>"><?php echo $entry['thumbnail'] ?></a>
                        <?php else : ?>
                            <?php echo $entry['thumbnail'] ?>
                        <?php endif; ?>
                    </div>
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
        <?php $counter++; endfor; ?>
    </div>

    <?php endfor; ?>

<?php elseif($template_variables['ordering-type'] === 'vertical') : 

    for ($cols = 0; $cols < $column_count; $cols++) : 

        $incrementor = 0;
        $decrementor = 1;
        ?>

    <div class="tpc-waterfall-col" style="width: <?php echo $width?>%">
        <?php 
        $incol_limit = $items_per_col;

        if($remainder) {
            $incol_limit++;
            $remainder--;
        }

        for ($rows = 0; $rows < $incol_limit; $rows++) :
            $index = $cols + 1;
            if($rows > 0) {
                $index += $incrementor;
            }

            $entry = $entries[$index - 1];
         ?>
            <article class="tpc-entry-block">
                <?php if(isset($instance['show_page_thumbnail']) && $instance['show_page_thumbnail'] && $entry['thumbnail']) : ?>
                    <div class="tpc-thumbnail">
                        <?php if($instance['linked_page_thumbnail'] && $entry['link']) : ?>
                            <a class="tpc-thumbnail-link" href="<?php echo $entry['link'] ?>"><?php echo $entry['thumbnail'] ?></a>
                        <?php else : ?>
                            <?php echo $entry['thumbnail'] ?>
                        <?php endif; ?>
                    </div>
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
        <?php 

        $incrementor += $column_count;

        endfor; ?>

    </div>

    <?php 
    endfor;

endif; ?>

</section>