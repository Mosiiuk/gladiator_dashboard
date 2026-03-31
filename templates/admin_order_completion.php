<?php

?>
<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->

<?php
$filename = __DIR__.'/notification/notification_popap.php';
if (file_exists($filename)) {
    include $filename;
}
?>

<div id="gladiator_dashboard_admin_order_completion_app" class="gladiator_dashboard_admin_dash" style="display: block;" >
    <h1><?php echo __('Order completion','gladiator-theme');?></h1>

    <?php
    $filename = __DIR__.'/_gladiator_dashboard_tabs.php';
    if (file_exists($filename)) {
        include $filename;
    }
    ?>

    <!-- APPLICANT ORDER LIST COMPLETED -->
    <div class="list_order_table" >
        <div class="list_order_table_head" >
            <div class="table_head_line" ><?php echo __('Booster','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Booster screen','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Order info','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Action','gladiator-theme');?></div>
        </div>
        <div :class="['list_order_table_body_tr', { 'dividtr': shouldAddClass(index) }]"  v-for="data, index in lists"  >
            <div class="table_td_line" >
                <img :src="data.booster.custom_profile_image" width="100" >
                {{data.booster.first_name}}  {{data.booster.last_name}}
            </div>

            <div class="table_td_line" >
                <img :src="data.screen_url" width="100" >
            </div>

            <div class="table_td_line" >
                {{data.product_info.product_name}} (#{{data.order_id}})
                <ul class="product_params">
                    <li v-for="meta, mindex in data.product_info.meta" > - {{meta.display_key}} {{meta.value}} </li>
                </ul>
            </div>
            <div class="table_td_line" >
                <button class="dashboard_button confirm_completion button" v-on:click="confirm_completion(data.id,data.order_id,data.product_id)" ><?php echo __('Confirm Completion','gladiator-theme');?></button>
            </div>
        </div>
    </div>
    <!-- /APPLICANT ORDER LIST COMPLETED -->
</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ] -->