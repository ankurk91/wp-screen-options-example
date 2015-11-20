<?php
$options = get_option(self::PLUGIN_OPTION_NAME);
?>
<h5>Sample Screen Options</h5>
<div class="metabox-prefs wpsco_meta_box">
    <label for="wpsco_option_1">
        <input type="checkbox" value="1" name="wpsco_options[check_box_1]" id="wpsco_option_1" <?php checked($options['check_box_1'],1) ?>> Option one
    </label>
    <label for="wpsco_option_2">
        <input type="checkbox" value="1" name="wpsco_options[check_box_2]" id="wpsco_option_2" <?php checked($options['check_box_2'],1) ?>> Option two
    </label>
</div>
<div class="screen-options">
    <label for="wpsco_option_3">Option three:</label>
    <input type="number" step="1" min="1" max="999" name="wpsco_options[number_1]" id="wpsco_option_3" maxlength="3"
           value="<?php echo esc_attr($options['number_1'])?>">
    <input type="button" class="button" value="Save Changes">
</div>
<input type="hidden" name="action" value="<?php echo self::AJAX_ACTION ?>"/>
<?php
wp_nonce_field(self::AJAX_ACTION, '_wpnonce-wpsco_meta_form');
?>