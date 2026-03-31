<?php

?>
<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->

<?php
$filename = __DIR__.'/notification/notification_popap.php';
if (file_exists($filename)) {
    include $filename;
}
?>

<div id="gladiator_dashboard_admin_withdrawal_requests_app" class="gladiator_dashboard_admin_dash" style="display: block;" >
    <h1><?php echo __('Withdrawal requests','gladiator-theme');?></h1>

    <?php
        $filename = __DIR__.'/_gladiator_dashboard_tabs.php';
        if (file_exists($filename)) {
            include $filename;
        }
    ?>

    <!-- WITHDRAWAL REQUESTS -->
    <div class="list_order_table admin_list_withdrawal" >
        <div class="list_order_table_head" >
            <div class="table_head_line" ><?php echo __('ID','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Booster','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Amount','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Payment method','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Action','gladiator-theme');?></div>
        </div>
        <div :class="['list_order_table_body_tr', { 'dividtr': shouldAddClass(index) }]"  v-for="data, index in lists" v-if="data.status==1"  >
            <div class="table_td_line" >{{data.ID}}</div>
            <div class="table_td_line" >
                <img :src="data.booster.custom_profile_image" width="100" >
                {{data.booster.first_name}}  {{data.booster.last_name}}
            </div>
            <div class="table_td_line" >{{data.summ}}</div>
            <div class="table_td_line" >
                <p>Payment name: {{data.pmo}} </p><br>
                {{data.payment_method}}
            </div>
            <div class="table_td_line" >
                <button class="dashboard_button button confirm_paid" v-on:click="confirm_paid(data.ID)" >
                  <?php echo __('Confirm paid','gladiator-theme');?>
                </button>
                <button class="dashboard_button button red_button delete_paid" v-on:click="delete_paid(data.ID)" >
                  <?php echo __('Cancel paid','gladiator-theme');?>

                </button>
            </div>
        </div>
    </div>
    <!-- /WITHDRAWAL REQUESTS -->

    <div class="load_more_block" >
        <button class="dashboard_button button my-3" v-on:click="load_more_withdrawal" >
          <?php echo __('Load more','gladiator-theme');?>
        </button>
    </div>


</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ] -->