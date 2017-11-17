<?php 
/**
 * @param caption caption "Flexible template for sites builted on Bootstrap 3. <b>Important:</b> if you set column count greater than 12, do not forget increase max column count via <code>tpc_bootstrap3_max_columns</code> filter manually."
 * 
 * @param container-type select container-fluid|container
 * @param ordering-type select horizontal|transposed
 * 
 * @param use-rows checkbox 1
 * 
 * @param column-count-large jqueryui:spinner 1-24
 * @param column-count-medium jqueryui:spinner 1-24
 * @param column-count-small jqueryui:spinner 1-24
 * @param column-count-extra-small jqueryui:spinner 1-24
 * 
 * @param items-per-row-based-on select column-count-large|column-count-medium|column-count-small|column-count-extra-small
 */

// Template Logic
$maxColumns = 12;
$maxColumns = apply_filters('tpc_bootstrap3_max_columns', $maxColumns);

$columns = array(
    'lg' => 'col-lg-' . $maxColumns / $template_variables['column-count-large'],
    'md' => 'col-md-' . $maxColumns / $template_variables['column-count-medium'],
    'sm' => 'col-sm-' . $maxColumns / $template_variables['column-count-small'],
    'xs' => 'col-xs-' . $maxColumns / $template_variables['column-count-extra-small'],
);

$itemsPerRowBase = isset($template_variables['items-per-row-based-on']) ? $template_variables['items-per-row-based-on'] : 'column-count-large';
$itemsPerRow = $template_variables[$itemsPerRowBase];
$itemsCount = count($entries);

$counter = 0;

// Logic for transposed layout
if($template_variables['ordering-type'] === 'transposed') {
    $itemsPerCol = ceil($itemsCount / $itemsPerRow);

    // Count of empty cols
    $empties = ($itemsPerCol - $itemsCount / $itemsPerRow) / (1 / $itemsPerRow);
}
?>

<?php if(isset($instance['title']) && $caller === 'shortcode') : ?>
    <h2 class="tpc-shortcode-main-title"><?php echo $instance['title']; ?></h2>
<?php endif; ?>

<section class="tpc-block tpc-bootstrap <?php echo $template_variables['container-type'] ?>">
    <div class="row">

    <?php if($template_variables['ordering-type'] === 'horizontal') : ?>
        <?php foreach ($entries as $key => $entry) : ?>

            <article class="tpc-entry-block <?php echo implode(' ', array_values($columns)) ?>">

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
            $counter++;
            if($counter % $itemsPerRow === 0 && $template_variables['use-rows']) :
                $counter = 0; 
                // here optionally clear row

                echo '</div><div class="row">';

            endif; ?>
        <?php endforeach; ?>

    <?php elseif($template_variables['ordering-type'] === 'transposed') : ?>
        
        <?php for($rows = 0; $rows < $itemsPerCol; $rows++) : // count of rows if matrix
            $incrementor = 0;
            $decrementor = 1;

            for($cols = 0; $cols < $itemsPerRow; $cols++) :
                $index = $rows + 1;

                if($cols > 0) $index += $incrementor;

                if($empties && $cols > ($itemsPerRow - $empties)) {
                    $index -= $decrementor;
                    $decrementor++;
                } 

                $entry = $entries[$index - 1];
                ?>

                <article class="tpc-entry-block <?php echo implode(' ', array_values($columns)) ?>">

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

                <?php $incrementor += $itemsPerCol;

                $counter++;

                if($counter >= $itemsCount) break;

            endfor; 

            if($counter < $itemsCount && $template_variables['use-rows']) {
                echo '</div><div class="row">';
            }

        endfor;
        ?>
    <?php endif; ?>

    </div>
</section>