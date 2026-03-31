<!-- [ <?php echo str_replace( $_SERVER['DOCUMENT_ROOT'], '', __FILE__ ); ?> -->
<div class="booster_panel_container">


	<?php
	$filename = __DIR__ . '/_booster_left_menu.php';
	if ( file_exists( $filename ) ) {
		include $filename;
	}
	?>

  <div class="booster_dashboard_container">
		<?php
		$inc_title = __( 'Find orders', 'gladiator-theme' );
		$filename  = __DIR__ . '/_booster_top_block.php';
		if ( file_exists( $filename ) ) {
			include $filename;
		}
		?>

    <div id="app_dashboard_booster_find_orders">
      <!-- ORDER FILTER -->
      <div class="list_order_table filter">
        <div class="list_order_table_head filter">
          <div class="table_head_line">
            <input type="text" v-model="filter.order_id" placeholder="<?php echo __( 'Order ID', 'gladiator-theme' ); ?>">
          </div>
          <div class="table_head_line">
            <input type="text" v-model="filter.region" placeholder="<?php echo __( 'Region', 'gladiator-theme' ); ?>">
          </div>
          <div class="table_head_line">
            <input type="text" v-model="filter.character_name_server" placeholder="<?php echo __( 'Character name & server', 'gladiator-theme' ); ?>">
          </div>
          <div class="table_head_line">
            <input type="text" v-model="filter.available_hours" placeholder="<?php echo __( 'Available Hours', 'gladiator-theme' ); ?>">
          </div>
          <div class="table_head_line">
            <button class="dashboard_button button" v-on:click="exfilter">
              <?php echo __( 'Find', 'gladiator-theme' ); ?>
            </button>
          </div>
        </div>
      </div>
      <!-- /ORDER FILTER -->

      <!-- ORDER LIST TABLE -->
      <div class="list_order_table">
        <div class="list_order_table_head ">
          <div class="table_head_line">
            <?php echo __( 'Order info', 'gladiator-theme' ); ?>
          </div>
          <div class="table_head_line">
            <?php echo __( 'Region', 'gladiator-theme' ); ?>
          </div>
          <div class="table_head_line">
            <?php echo __( 'Price in USD', 'gladiator-theme' ); ?>
          </div>
          <div class="table_head_line">
            <?php echo __( 'Action', 'gladiator-theme' ); ?>
          </div>
        </div>

        <div :class="['list_order_table_body_tr', { 'dividtr': shouldAddClass(index) }]"
             v-for="info, index in order_list"
             v-if="!info.applicants_info || !info.applicants_info.booster_status || info.applicants_info.booster_status !== 2 "
             >
          <div class="table_td_line">
            {{info.product_name}} ( #{{info.order_id}} )
            <ul class="product_params">
              <li v-for="meta, mindex in info.meta"> - {{meta.display_key}} {{meta.value}}</li>
            </ul>
          </div>
          <div class="table_td_line">{{info.region}}</div>
          <div class="table_td_line">{{info.boosters_products_prices}}</div>
          <div class="table_td_line">
            <button class="dashboard_button want_btn button" v-if="info.is_applicants==false"
                    v-on:click="show_popap_want_order($event,info.order_id,info.id)">
              <?php echo __( 'I want this order', 'gladiator-theme' ); ?>
            </button>
            <button class="dashboard_button want_cancel_btn button" v-if="info.is_applicants==true"
                    v-on:click="cancel_want_order($event,info.order_id,info.id,info.booster_applicants_id)">
              <?php echo __( 'Cancel', 'gladiator-theme' ); ?>
            </button>
          </div>
        </div>


      </div>
      <!-- /ORDER LIST TABLE -->


      <!-- POPAP Booster -->

      <div id="booster_want_order_popap">
        <div class="cl_close_booster_popap" v-on:click="close_want_order">Close</div>
        <div class="want_order_container">
          <div class="order_info">
            {{order_info.product_name}} ( #{{order_info.order_id}} )
            <ul class="product_params">
              <li v-for="meta, mindex in order_info.meta"> - {{meta.display_key}} {{meta.value}}</li>
            </ul>
          </div>
          <div class="order_want_action">
            <input type="text" id="start_time" v-model="want_order.start_time"
                   placeholder="<?php echo __( 'Start time', 'gladiator-theme' ); ?>">
            <input type="text" id="eta" v-model="want_order.eta"
                   placeholder="<?php echo __( 'Completion ETA', 'gladiator-theme' ); ?>">
            <button class="dashboard_button button ex_want_order"
                    v-on:click="ex_want_order">
              <?php echo __( 'OK', 'gladiator-theme' ); ?>
            </button>
          </div>
        </div>
      </div>

      <!-- /POPAP Booster -->
    </div>
  </div>
</div>
<!--  <?php echo str_replace( $_SERVER['DOCUMENT_ROOT'], '', __FILE__ ); ?> ]-->