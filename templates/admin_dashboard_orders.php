<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<?php
$filename = __DIR__.'/notification/notification_popap.php';
if (file_exists($filename)) {
    include $filename;
}
?>

<div id="gladiator_dashboard_admin_dash" class="gladiator_dashboard_admin_dash" >
    <h1><?php echo __('Order list','gladiator-theme');?></h1>

    <?php
    $filename = __DIR__.'/_gladiator_dashboard_tabs.php';
    if (file_exists($filename)) {
        include $filename;
    }
    ?>

    <div class="search_chat">
        <input type="text" v-model="find_chat_order_id" placeholder="Order ID" >
        <button class="dashboard_button button" v-on:click="find_chat" >Find chat</button>
        <button class="dashboard_button button red_button" v-on:click="reset_find_chat" >Reset</button>
    </div>

    <!-- ORDER LIST TABLE -->
    <div class="list_order_table" >
        <div class="list_order_table_head" >
            <div class="table_head_line" ><?php echo __('Order info','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Region','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Total cost','gladiator-theme');?></div>
            <div class="table_head_line" ><?php echo __('Action','gladiator-theme');?></div>
        </div>
        <div :class="[
                'list_order_table_body_tr',
                {
                    'dividtr': shouldAddClass(index),
                    'order-grouped': isGroupedOrder(index),
                    'order-group-start': isGroupedOrderStart(index),
                    'order-group-end': isGroupedOrderEnd(index)
                }
            ]"  v-for="info, index in order_list"  >
            <div class="table_td_line" >
                {{info.product_name}} ( #{{info.order_id}} )
                <ul class="product_params">
                    <li v-for="meta, mindex in info.meta" > - {{meta.display_key}} {{meta.value}} </li>
                </ul>

                <div v-if="info.discord_id" style="margin-top:10px; white-space: pre-wrap;" >
                    <span style="color: #f90;">Discord id:</span> <br>{{info.discord_id}}
                </div>

                <div v-if="info.customer_note" style="margin-top:10px; white-space: pre-wrap;" >
                    <span style="color: #f90;">Customer provided note:</span> <br>{{info.customer_note}}
                </div>

            </div>
            <div class="table_td_line" >{{info.region}}</div>
            <div class="table_td_line" >{{info.order_info_add._order_currency}} {{info.order_info_add._order_total}} x {{info.qty}} </div>
            <div class="table_td_line" >
                <input type="text" v-bind:disabled="(info.boosters_products_prices>0)?true:false" class="input_price_order" :data-order_id="info.order_id" :data-product_id="info.id"  :value="info.boosters_products_prices" placeholder="0"  >
                <button class="dashboard_button button set_price" v-if="(info.boosters_products_prices>0)?false:true" :data-product_id="info.id"   :data-order_id="info.order_id" v-on:click="set_order_price($event,index)" >

                    <?php echo __('Set price','gladiator-theme');?>
                </button>
                <button class="dashboard_button button red_button clear_price" v-if="(info.boosters_products_prices>0)?true:false" :data-product_id="info.id"  :data-order_id="info.order_id" v-on:click="clear_order_price($event,index)" >
                    <?php echo __('Cancel','gladiator-theme');?>
                </button>
            </div>
        </div>
    </div>
    <!-- /ORDER LIST TABLE -->

    <div class="load_more_block" >
        <button class="dashboard_button load_more_order button mb-3 mt-3" v-on:click="load_more_order" >
            <?php echo __('Load more','gladiator-theme');?>
        </button>
    </div>
</div>


<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ] -->
