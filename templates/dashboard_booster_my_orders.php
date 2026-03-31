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
        $inc_title = __('My orders','gladiator-theme');
        $filename = __DIR__.'/_booster_top_block.php';
        if (file_exists($filename)) {
            include $filename;
        }
        ?>

        <!-- ORDER LIST TABLE -->
        <div id="app_dashboard_booster_my_orders" class="list_order_table my_orders" >

            <!-- screen shot -->
            <input type="file" id="order_screen_shot" @change="choice_order_screen_shot" style="display: none" />
            <!-- /screen shot -->

            <div class="list_order_table_head" >
                <div class="table_head_line" >
                  <?php echo __('Order info','gladiator-theme');?>
                </div>
                <div class="table_head_line" >
                  <?php echo __('Region','gladiator-theme');?>
                </div>
                <div class="table_head_line" >
                  <?php echo __('Cost','gladiator-theme');?>
                </div>
                <div class="table_head_line" >
                  <?php echo __('Time','gladiator-theme');?>
                </div>
                <div class="table_head_line" >
                  <?php echo __('Action','gladiator-theme');?>
                </div>
            </div>

            <div :class="['list_order_table_body_tr', { 'dividtr': shouldAddClass(index) }]"  v-for="info, index in order_list" v-if="info.applicants_info.booster_status == 2"  >
                <div class="table_td_line" >
                    {{info.product_name}} ( #{{info.order_id}} )
                    <ul class="product_params">
                        <li v-for="meta, mindex in info.meta" > - {{meta.display_key}} {{meta.value}} </li>
                    </ul>
                </div>
                <div class="table_td_line" >{{info.region}}</div>
                <div class="table_td_line" >{{info.boosters_products_prices}}</div>
                <div class="table_td_line" >
                    <?php echo __('Start time: ','gladiator-theme');?> : {{info.applicants_info.start_time}}<br>
                    <?php echo __('Completion ETA: ','gladiator-theme');?> : {{info.applicants_info.completion_eta}}<br>
                </div>
                <div class="table_td_line" >
                    <button class="dashboard_button button set_order_completed"  v-on:click="booster_order_completed($event,info.booster_applicants_id)" >
                      <?php echo __('Set order completed','gladiator-theme');?>
                    </button>
                </div>
            </div>
        </div>

        <!-- /ORDER LIST TABLE -->
    </div>
</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ]-->