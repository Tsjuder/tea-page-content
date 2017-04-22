<div class="tpc-columns-wrapper">
    <div class="tpc-column tpc-full-width">
        <p>
            <label for="<?php echo $bind->get_field_id('title') ?>">
                <?php _e('Title', 'tea-page-content'); ?>:
            </label>
            <input class="widefat" type="text" id="<?php echo $bind->get_field_id('title') ?>" name="<?php echo $bind->get_field_name('title') ?>" value="<?php echo $instance['title'] ?>" />
        </p>
    </div>

    <div class="tpc-columns-group">
        <div class="tpc-column">
            <?php if(is_array($entries) && count($entries)) : ?>
                <?php $instance['posts'] = unserialize($instance['posts']) ? unserialize($instance['posts']) : array(); ?>

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
                            <?php $checked = in_array($postData['id'], $instance['posts']) ? 'checked' : ''; ?>
                            <?php $raw_page_variables = isset($instance['page_variables'][$postData['id']]) ? $instance['page_variables'][$postData['id']] : '' ; ?>
                            <?php $item_class = trim($raw_page_variables) ? 'configured-item' : 'empty-item'; ?>
                            <?php $data_thumbnail_url = isset($page_variables[$postData['id']]['page_thumbnail']) ? $page_variables[$postData['id']]['page_thumbnail'] : ''; ?>

                            <label for="<?php echo $bind->get_field_id('posts') . '-' . $postData['id'] ?>" class="tpc-accordeon-item <?php echo $item_class ?>" id="<?php echo $bind->get_field_id('item') . '-' . $postData['id'] ?>">
                                <a href="#" data-item="<?php echo $bind->get_field_id('item') . '-' . $postData['id'] ?>" data-title="<?php echo $postData['title'] ?>" data-id="<?php echo $postData['id'] ?>" 
                                data-modal="tpc-call-item-options-modal" data-target="<?php echo $bind->get_field_id('page_variables') . '-' . $postData['id'] ?>" class="tpc-call-modal-button tpc-call-item-options-modal dashicons dashicons-admin-generic" title="<?php _e('Open page level options', 'tea-page-content') ?>"></a>

                                <input type="checkbox" name="<?php echo $bind->get_field_name('posts') ?>[]" id="<?php echo $bind->get_field_id('posts') . '-' . $postData['id'] ?>" value="<?php echo $postData['id'] ?>" <?php echo $checked ?> />

                                <input type="hidden" name="<?php echo $bind->get_field_name('page_variables') ?>[<?php echo $postData['id'] ?>]" id="<?php echo $bind->get_field_id('page_variables') . '-' . $postData['id'] ?>" value="<?php echo $raw_page_variables ?>" data-thumbnail-url="<?php echo $data_thumbnail_url ?>" autocomplete="off" />

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
                <label for="<?php echo $bind->get_field_id('order') ?>">
                    <?php _e('Order', 'tea-page-content'); ?>:
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
                <label for="<?php echo $bind->get_field_id('orderby') ?>">
                    <?php _e('Order By', 'tea-page-content'); ?>:
                </label>
                <select class="widefat" id="<?php echo $bind->get_field_id('orderby') ?>" name="<?php echo $bind->get_field_name('orderby') ?>">
                    <option value="date" <?php if($instance['orderby'] === 'date') : echo 'selected'; endif; ?>>
                        <?php _e('Date', 'tea-page-content') ?>
                    </option>
                    <option value="title" <?php if($instance['orderby'] === 'title') : echo 'selected'; endif; ?>>
                        <?php _e('Title', 'tea-page-content') ?>
                    </option>
                    <option value="type" <?php if($instance['orderby'] === 'type') : echo 'selected'; endif; ?>>
                        <?php _e('Type', 'tea-page-content') ?>
                    </option>
                    <option value="comment_count" <?php if($instance['orderby'] === 'comment_count') : echo 'selected'; endif; ?>>
                        <?php _e('Comment count', 'tea-page-content') ?>
                    </option>
                    <option value="relevance" <?php if($instance['orderby'] === 'relevance') : echo 'selected'; endif; ?>>
                        <?php _e('Relevance', 'tea-page-content') ?>
                    </option>
                    <option value="rand" <?php if($instance['orderby'] === 'rand') : echo 'selected'; endif; ?>>
                        <?php _e('Random', 'tea-page-content') ?>
                    </option>
                    <option value="post__in" <?php if($instance['orderby'] === 'post__in') : echo 'selected'; endif; ?>>
                        <?php _e('As presented', 'tea-page-content') ?>
                    </option>
                </select>
            </p>

            <p>
                <label for="<?php echo $bind->get_field_id('show_page_thumbnail'); ?>">
                    <?php $checked = $instance['show_page_thumbnail'] ? 'checked' : ''; ?>
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
        </div>
    </div>

    <div class="tpc-column tpc-full-width">
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
    </div>
    
</div>