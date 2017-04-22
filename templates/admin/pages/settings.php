<div class="wrap tpc-settings-page">
    <h2>Tea Page Content - <?php _e('Settings', 'tea-page-content') ?></h2>

    <form method="post" novalidate="novalidate" autocomplete="off">

        <?php foreach ($settings as $setting_alias => $setting_params) : ?>
            <div class="tpc-form-group">
                <?php $default = $setting_params['default']; ?>
                <label>
                    <span><?php echo $setting_params['label'] ?></span>
                    <br />
                    <small><?php echo $setting_params['description'] ?></small>
                    <br />

                    <?php switch ($setting_params['structure']) {
                        case 'select': ?>
                            
                        <select name="<?php echo $setting_alias ?>">
                            <?php switch ($setting_params['type']) {
                                case 'switch':

                                $attributes = array();
                                if(!$default) {
                                    $attributes[] = 'selected';
                                } ?>
                                
                                <option value="1"><?php _e('Yes', 'tea-page-content') ?></option>
                                <option value="0" <?php echo implode(' ', $attributes) ?>><?php _e('No', 'tea-page-content') ?></option>

                                <?php break;

                                default:  ?>



                                <?php break;
                                
                            } ?>
                        </select>

                        <?php break;
                        
                    } ?>
                </label>
            </div>
        <?php endforeach; ?>

        <input type="hidden" name="tpc_settings_update" value="Y" />

        <p class="submit tpc-submit-group">
            <input name="submit" id="submit" class="button button-primary" value="<?php _e('Submit', 'tea-page-content') ?>" type="submit">
        </p>
    </form>
</div>