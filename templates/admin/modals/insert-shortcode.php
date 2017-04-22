<form id="tpc-call-shortcode-modal" class="tpc-dialog tpc-dialog-insert-shortcode hidden tpc-preloader is-hidden" autocomplete="off">
    <div class="tpc-columns-wrapper">
        <div class="tpc-column tpc-full-width">
            <p>
                <label for="tpc-title">
                    <?php _e('Title', 'tea-page-content'); ?>:
                </label>
                <input class="widefat" type="text" id="tpc-title" name="title" value="" />
            </p>
        </div>

        <div class="tpc-columns-group">
            <div class="tpc-column">
                <?php if(is_array($entries) && count($entries)) : ?>
                    <span class="tpc-column-label">
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

                                <label for="tpc-posts-<?php echo $postData['id'] ?>" class="tpc-accordeon-item" id="tpc-item-<?php echo $postData['id'] ?>">
                                    <a href="#" data-item="tpc-item-<?php echo $postData['id'] ?>" data-title="<?php echo $postData['title'] ?>" data-id="<?php echo $postData['id'] ?>" data-modal="tpc-call-item-options-modal" data-target="tpc-page_variables-<?php echo $postData['id'] ?>" class="tpc-call-modal-button tpc-call-item-options-modal dashicons dashicons-admin-generic" title="<?php _e('Open page level options', 'tea-page-content') ?>"></a>

                                    <input type="checkbox" name="posts[]" id="tpc-posts-<?php echo $postData['id'] ?>" value="<?php echo $postData['id'] ?>" />

                                    <input type="hidden" name="page_variables[<?php echo $postData['id'] ?>]" id="tpc-page_variables-<?php echo $postData['id'] ?>" value="" data-thumbnail-url="" autocomplete="off" />

                                    <span class="tpc-item-title" title="<?php echo $postData['title'] ?>"><?php echo $postData['title'] ?></span>
                                </label>
                            <?php endforeach; ?>
                            </div>

                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tpc-column">
                <p>
                    <label for="tpc-order">
                        <?php _e('Order', 'tea-page-content'); ?>:
                    </label>
                    <select class="widefat" id="tpc-order" name="order">
                        <option value="desc">
                            <?php _e('Descending', 'tea-page-content') ?>
                        </option>
                        <option value="asc">
                            <?php _e('Ascending', 'tea-page-content') ?>
                        </option>
                    </select>
                </p>

                <p>
                    <label for="tpc-orderby">
                        <?php _e('Order by', 'tea-page-content'); ?>:
                    </label>
                    <select class="widefat" name="orderby">
                        <option value="date">
                            <?php _e('Date', 'tea-page-content') ?>
                        </option>
                        <option value="title">
                            <?php _e('Original title', 'tea-page-content') ?>
                        </option>
                        <option value="type">
                            <?php _e('Post type', 'tea-page-content') ?>
                        </option>
                        <option value="comment_count">
                            <?php _e('Comment count', 'tea-page-content') ?>
                        </option>
                        <option value="relevance">
                            <?php _e('Relevance', 'tea-page-content') ?>
                        </option>
                        <option value="rand">
                            <?php _e('Random', 'tea-page-content') ?>
                        </option>
                        <option value="post__in">
                            <?php _e('As Is (as typed)', 'tea-page-content') ?>
                        </option>
                    </select>
                </p>

                <p>
                    <label for="tpc-show_page_thumbnail">
                        <?php $checked = (isset($instance['show_page_thumbnail']) && $instance['show_page_thumbnail']) ? 'checked' : '';  ?>

                        <input class="widefat" type="checkbox" id="tpc-show_page_thumbnail" name="show_page_thumbnail" value="1" <?php echo $checked ?> />
                        <span><?php _e('Show page thumbnail', 'tea-page-content'); ?></span>
                    </label>

                    <br />

                    <label for="tpc-show_page_title">
                        <?php $checked = (isset($instance['show_page_title']) && $instance['show_page_title']) ? 'checked' : '';  ?>

                        <input class="widefat" type="checkbox" id="tpc-show_page_title" name="show_page_title" value="1" <?php echo $checked ?> />
                        <span><?php _e('Show page title', 'tea-page-content'); ?></span>
                    </label>

                    <br />

                    <label for="tpc-show_page_content">
                        <?php $checked = (isset($instance['show_page_content']) && $instance['show_page_content']) ? 'checked' : '';  ?>

                        <input class="widefat" type="checkbox" id="tpc-show_page_content" name="show_page_content" value="1" <?php echo $checked ?> />
                        <span><?php _e('Show page content', 'tea-page-content'); ?></span>
                    </label>

                    <br />

                    <label for="tpc-linked_page_title">
                        <?php $checked = (isset($instance['linked_page_title']) && $instance['linked_page_title']) ? 'checked' : '';  ?>

                        <input class="widefat" type="checkbox" id="tpc-linked_page_title" name="linked_page_title" value="1" <?php echo $checked ?> />
                        <span><?php _e('Linked page title (if possible)', 'tea-page-content'); ?></span>
                    </label>

                    <br />

                    <label for="tpc-linked_page_thumbnail">
                        <?php $checked = (isset($instance['linked_page_thumbnail']) && $instance['linked_page_thumbnail']) ? 'checked' : '';  ?>
                        
                        <input class="widefat" type="checkbox" id="tpc-linked_page_thumbnail" name="linked_page_thumbnail" value="1" <?php echo $checked ?> />
                        <span><?php _e('Linked page thumbnail (if possible)', 'tea-page-content'); ?></span>
                    </label>
                </p>
            </div>
        </div>

        <div class="tpc-column tpc-full-width">

            <p class="tpc-preloader is-hidden">
                <label for="tpc-template">
                    <?php _e('Template', 'tea-page-content'); ?>:
                </label>
                <select class="widefat tpc-template-list" data-variables-area="tpc-template-variables-wrapper" id="tpc-template" name="template" autocomplete="off">
                    <?php foreach ($templates as $alias) : ?>
                        <?php $selected = $alias == $instance['template'] ? 'selected' : ''; ?>
                        <option value="<?php echo $alias ?>" <?php echo $selected ?>>
                            <?php echo ucwords(str_replace(array('.php', 'tpc-', '-'), ' ', $alias)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>

            <div class="tpc-template-params" id="tpc-template-variables-wrapper" data-mask-name="{mask}">
                <?php echo $partials['template_variables'] ?>
            </div>
        </div>
    </div>
</form>