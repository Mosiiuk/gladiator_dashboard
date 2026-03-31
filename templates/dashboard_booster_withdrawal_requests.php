<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<div class="booster_panel_container" >


    <?php
    $filename = __DIR__.'/_booster_left_menu.php';
    if (file_exists($filename)) {
        include $filename;
    }
    ?>

    <div  class="booster_dashboard_container" >
        <?php
        $inc_title = __('Withdrawal','gladiator-theme');
        $filename = __DIR__.'/_booster_top_block.php';
        if (file_exists($filename)) {
            include $filename;
        }
        ?>

        <!-- WITHDRAWAL LIST TABLE -->
        <div id="app_dashboard_booster_withdrawal" class="list_order_table  withdrawal_list_order_table" >
            <div class="list_order_table_head" >
                <div class="table_head_line" >
                  <?php echo __('Data','gladiator-theme');?>
                </div>
                <div class="table_head_line" >
                  <?php echo __('ID','gladiator-theme');?>
                </div>
                <div class="table_head_line" >
                  <?php echo __('Summ','gladiator-theme');?>
                </div>
                <div class="table_head_line" >
                  <?php echo __('Payment method','gladiator-theme');?>
                </div>
                <div class="table_head_line" >
                  <?php echo __('Status','gladiator-theme');?>

                </div>
                <div class="table_head_line" >
                  <?php echo __('Action','gladiator-theme');?>
                </div>
            </div>

            <div class="list_order_table_body_tr"  v-for="info, index in list"   >
                <div class="table_td_line" >
                    {{info.date}}
                </div>
                <div class="table_td_line" >{{info.ID}}</div>
                <div class="table_td_line" >{{info.summ}}</div>
                <div class="table_td_line" >{{info.payment_method}}</div>
                <div class="table_td_line" >
                    <span v-if="info.status==1" class="withdrawal_status withdrawal_status_new"> <?php echo __('New','gladiator-theme');?> </span>
                    <span v-if="info.status==2" class="withdrawal_status withdrawal_status_execute">
                      <?php echo __('Execute','gladiator-theme');?>
                    </span>
                </div>
                <div class="table_td_line" >
                    <button v-if="info.status==1" class="dashboard_button withdrawal_delete button red_button" v-on:click="booster_withdrawal_delete(info.ID)" >
                      <?php echo __('Cancel','gladiator-theme');?>
                    </button>
                </div>
            </div>
        </div>

        <!-- /WITHDRAWAL LIST TABLE -->
    </div>
</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ]-->