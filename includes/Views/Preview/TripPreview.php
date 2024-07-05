<div class="tm_trip_preview_wrapper">
    <div class="tm_preview_header">
        <div class="tm_preview_left_header">
            <div class="tm_trip_title">
                <?php echo esc_html($title); ?>
            </div>
            <div class="tm_trip_actions">
                <a href="<?php echo esc_html($edit_url); ?>" class="tm_trip_edit_link">
                    <span class="dashicons dashicons-edit"></span>
                    Edit
                </a>
            </div>
        </div>

        <div class="tm_preview_right_header">
            <div class="tm_trip_shortcode">
                <?php echo esc_html($shortcode); ?>
            </div>
        </div>
    </div>

    <div class="tm_preview_content">
        <?php echo  do_shortcode($shortcode); ?>
    </div>
</div>