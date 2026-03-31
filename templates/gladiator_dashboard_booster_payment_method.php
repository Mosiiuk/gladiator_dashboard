<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<div class="booster_panel_container" >


    <?php
    $filename = __DIR__.'/_booster_left_menu.php';
    if (file_exists($filename)) {
        include $filename;
    }
    ?>

    <?php
        $payment_methods = $core_class->Booster_Instance->get_payment_methods();
    ?>
    <script>
        const booster_payment_methods=<?php echo (count($payment_methods)>0)?json_encode($payment_methods):'{}';?>
    </script>

    <div  class="booster_dashboard_container" >
        <?php
        $inc_title = __('Payment methods','gladiator-theme');
        $filename = __DIR__.'/_booster_top_block.php';
        if (file_exists($filename)) {
            include $filename;
        }
        ?>

        <!-- Payment methods -->
        <div id="app_dashboard_booster_payment_methods" class="list_order_table payment_methods_list_order_table" >
            <div class="list_order_table_body_tr">
                <div class="table_td_line" >
                    <?php echo __('PayPal','gladiator-theme');?><br>
                    <small><?php echo __('Entry: PayPal Email:','gladiator-theme');?></small><br>
                    <input class="payment_fields" v-model="payment_fields.booster_paypal" type="text" placeholder="<?php echo __('PayPal Email','gladiator-theme');?>" >
                </div>
            </div>
            <div class="list_order_table_body_tr">
                <div class="table_td_line" >
                    <?php echo __('Wise','gladiator-theme');?><br>
                    <small><?php echo __('Entry: Wise Email:','gladiator-theme');?></small><br>
                    <input class="payment_fields" v-model="payment_fields.booster_wiseemail" type="text" placeholder="<?php echo __('Wise Email','gladiator-theme');?>" >
                </div>
            </div>
            <div class="list_order_table_body_tr">
                <div class="table_td_line" >
                    <?php echo __('USDT TRC20','gladiator-theme');?><br>
                    <small><?php echo __('Wallet Address:','gladiator-theme');?></small><br>
                    <input class="payment_fields" v-model="payment_fields.booster_usdt_trc20" type="text" placeholder="<?php echo __('Wallet Address','gladiator-theme');?>" >
                </div>
            </div>
            <div class="list_order_table_body_tr">
                <div class="table_td_line" >
                   <button class="dashboard_button button" v-on:click="save_payment" ><?php echo __('Save','gladiator-theme');?></button>
                </div>
            </div>

        </div>

        <!-- /Payment methods -->
    </div>
</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ]-->