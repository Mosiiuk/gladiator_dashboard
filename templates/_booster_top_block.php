<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<!-- Top block -->
<?php
    $current_user = wp_get_current_user();
    if ( $current_user->first_name && $current_user->last_name ) {
        $full_name = $current_user->first_name . ' ' . $current_user->last_name;
    } else {
        $full_name = $current_user->user_nicename;
    }

    if (!isset($balance))
    {
        $balance = $core_class->Booster_Instance->get_balance($current_user->ID);
    }
?>

<script>
    jQuery(document).ready(function(){
        if (jQuery('.booster_panel_container').length) { // Corrected here
            if (!jQuery('div.booster_bottom_message').length) { // And here
                jQuery('.booster_panel_container').append('<div class="booster_bottom_message bhide"></div>');
            }
        }
    });
</script>
<div class="booster_dashboard_container_top" >
    <div class="booster_dashboard_title" >
        <h1><?php echo $inc_title;?></h1>
    </div>
    <div class="booster_dashboard_top_icons" >
        <div class="booster_dashboard_notifications" >
            <div class="booster_dashboard_notifications_icon booster_dashboard_icon"></div>
            <div class="booster_dashboard_notifications_text_container">
                <div class="booster_dashboard_notifications_text">
                  <?php echo __('Notifications','gladiator-theme');?></div>
                <div class="booster_dashboard_notifications_value">
                    <span id="count_notifications" >0</span>
                  <span>
                    <?php echo __('new','gladiator-theme');?>
                  </span>
                </div>
            </div>
        </div>
        <div class="booster_dashboard_balance" >
            <div class="booster_dashboard_balance_icon booster_dashboard_icon"></div>
            <div class="booster_dashboard_balance_text_container">
                <div class="booster_dashboard_balance_text">
                  <?php echo __('Balance','gladiator-theme');?>
                </div>
                <div class="booster_dashboard_balance_value">
                    <span>$</span>
                    <span data-booster_balance="1" >
                      <?php echo $balance;?>
                    </span>
                </div>
            </div>
        </div>
        <div class="booster_dashboard_name" >
            <div class="booster_dashboard_name_icon booster_dashboard_icon"></div>
            <div class="booster_dashboard_name_text_container">
                <div class="booster_dashboard_name_text"><?php echo __('Hello','gladiator-theme');?></div>
                <div class="booster_dashboard_name_value">
                    <span><?php echo $full_name;?></span>
                </div>
            </div>

        </div>
    </div>
</div>
<?php
$filename = __DIR__.'/notification/notification_popap.php';
if (file_exists($filename)) {
    include $filename;
}
?>

<!-- /Top block -->
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ]-->