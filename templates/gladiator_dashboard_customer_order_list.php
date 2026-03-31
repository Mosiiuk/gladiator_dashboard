<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<div class="booster_panel_container" >
    <?php
        $filename = __DIR__.'/_customer_left_menu.php';
        if (file_exists($filename)) {
            include $filename;
        }
    ?>

    <div  class="booster_dashboard_container" >
        <?php
            $inc_title = __('My orders','gladiator-theme');
            $filename = __DIR__.'/_customer_top_block.php';
            if (file_exists($filename)) {
                include $filename;
            }
        ?>

        <!-- ORDER LIST TABLE -->
        <div class="list_order_table" id="app_dashboard_customer_my_orders" >
            <div class="list_order_table_head" >
                <div class="table_head_line" ><?php echo __('Order info','gladiator-theme');?></div>
                <div class="table_head_line" ><?php echo __('Region','gladiator-theme');?></div>
                <div class="table_head_line" ><?php echo __('Cost','gladiator-theme');?></div>
                <div class="table_head_line" ><?php echo __('Status','gladiator-theme');?></div>
            </div>

            <div :class="['list_order_table_body_tr', { 'dividtr': shouldAddClass(index) }]"  v-for="info, index in order_list"  >
                <div class="table_td_line" >
                    {{info.product_name}} ( #{{info.order_id}} )
                    <ul class="product_params">
                        <li v-for="meta, mindex in info.meta" > - {{meta.display_key}} {{meta.value}} </li>
                    </ul>
                </div>
                <div class="table_td_line" >{{info.region}}</div>
                <div class="table_td_line" >{{info.product_price}}</div>
                <div class="table_td_line" >

                    <span v-if="info.status=='on-hold'" class="order_status on_hold">{{info.status}}</span>
                    <span v-if="info.status=='completed'" class="order_status completed"> {{info.status}}</span>
                    <span v-if="info.status=='cancelled'" class="order_status cancelled"> {{info.status}}</span>
                    <span v-if="info.status=='failed'" class="order_status failed"> {{info.status}} </span>
                    <span v-if="info.status=='processing'" class="order_status processing"> {{info.status}} </span>
                    <span v-if="info.status=='pending'" class="order_status pending"> {{info.status}}</span>

                </div>
            </div>

            <div class="load_more_block" >
                <button class="dashboard_button load_more_order button mt-5 w-md-25 mx-auto d-block" v-on:click="load_more_order" >
                  <?php echo __('Load more','gladiator-theme');?>
                </button>
            </div>
        </div>

        <!-- /ORDER LIST TABLE -->



    </div>
</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ]-->