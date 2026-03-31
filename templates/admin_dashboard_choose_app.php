<?php

?>
<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<?php
$filename = __DIR__.'/notification/notification_popap.php';
if (file_exists($filename)) {
    include $filename;
}
?>

<div id="gladiator_dashboard_admin_choose_app" class="gladiator_dashboard_admin_dash" >
    <h1><?php echo __('Choosing applicant for order','gladiator-theme');?></h1>

    <?php
    $filename = __DIR__.'/_gladiator_dashboard_tabs.php';
    if (file_exists($filename)) {
        include $filename;
    }
    ?>

    <!-- ORDER LIST TABLE -->
    <div class="list_order_table" >

        <div class="list_order_table_head" >
            <div class="table_head_line" ><?php echo __('Order info','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Region','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Total cost','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Action','gladiator-theme');?></div>
        </div>
        <div :class="['list_order_table_body_tr', { 'dividtr': shouldAddClass(index) }]"  v-for="info, index in order_list" v-if="!info.applicants_info || !info.applicants_info.booster_status || info.applicants_info.booster_status !== 2"  >
            <div class="table_td_line" >
                {{info.product_name}} ( #{{info.order_id}} )
                <ul class="product_params">
                    <li v-for="meta, mindex in info.meta" > - {{meta.display_key}} {{meta.value}} </li>
                </ul>
            </div>
            <div class="table_td_line" >{{info.region}}</div>
            <div class="table_td_line" >{{info.order_info_add._order_currency}} {{info.order_info_add._order_total}} </div>
            <div class="table_td_line" >
                <button class="dashboard_button set_price button" v-on:click="view_applicants(info.order_id,info.id)" ><?php echo __('View applicants ','gladiator-theme');?></button>
            </div>
        </div>


    </div>
    <!-- /ORDER LIST TABLE -->

    <!--<div class="load_more_block" >
        <button class="dashboard_button load_more_order button mb-3 mt-3" v-on:click="load_more_order" ><?php /*echo __('Load more','gladiator-theme');*/?></button>
    </div>-->

    <!-- POPAP Booster -->

    <div id="booster_popap" >
        <div class="cl_close_booster_popap" v-on:click="close_view_applicants" >Close</div>
        <div class="list_boosters">
            <div class="booster_item" v-for="data_booster, index in boosters_list" :data-booster_applicants_id="data_booster.ID"  >
                <span>#{{data_booster.order_id}} </span>
                <span>{{data_booster.boosters.display_name}}</span>
                <span><?php echo __('Start time:','gladiator-theme');?> {{data_booster.start_time}}</span>
                <span><?php echo __('Completion ETA:','gladiator-theme');?> {{data_booster.completion_eta}}</span>
                <button class="dashboard_button chooise_applicants button" v-on:click="chooise_applicants(data_booster.boosters_id, data_booster.ID,data_booster.order_id,data_booster.product_id)" ><?php echo __('Choose this applicant','gladiator-theme');?></button>
            </div>
        </div>
    </div>

    <!-- /POPAP Booster -->

</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ] -->