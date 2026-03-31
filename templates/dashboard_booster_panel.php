<!-- [ <?php echo str_replace( $_SERVER['DOCUMENT_ROOT'], '', __FILE__ ); ?> -->
<?php
$current_user         = wp_get_current_user();
$custom_profile_image = get_field( 'custom_profile_image', 'user_' . $current_user->ID );


$profile_image = $custom_profile_image ? $custom_profile_image : plugins_url() . '/gladiator_dashboard/img/no_avatar.png';

$balance         = $core_class->Booster_Instance->get_balance( $current_user->ID );
$payment_methods = $core_class->Booster_Instance->get_payment_methods();

$Find_orders_page_url = $core_class->get_page_id_by_shortcode('[gladiator_dashboard_booster_find_orders]');



?>
<script>
    const user_booster = {
        avatar: '<?php echo $profile_image;?>',
    };
    const booster_payment_methods =<?php echo ( count( $payment_methods ) > 0 ) ? json_encode( $payment_methods ) : '{}';?>;
    const booster_balance = parseFloat('<?php echo $balance;?>');
</script>


<div class="booster_panel_container">

	<?php
	$filename = __DIR__ . '/_booster_left_menu.php';
	if ( file_exists( $filename ) ) {
		include $filename;
	}
	?>
  <div class="booster_dashboard_container">

		<?php
		$inc_title = __( 'Dashboard', 'gladiator-theme' );
		$filename  = __DIR__ . '/_booster_top_block.php';
		if ( file_exists( $filename ) ) {
			include $filename;
		}
		?>

    <div id="app_booster_dashboard" class="booster_dashboard_container_blocks">
      <div class="style_rectangle booster_dashboard_info_block">
        <div class="booster_dashboard_info_name">
          <h5>
          <span class="yellow_text">
            <?php echo $full_name; ?>
          </span>
          <span>
            <?php echo __( 'Have, a good day today!', 'gladiator-theme' ); ?>
          </span>
          </h5>
        </div>
        <div class="booster_avatar d-flex align-items-center my-3 justify-content-between">
          <img :src="booster.avatar" width="100">
          <input type="file" id="uploadAvatar" @change="uploadAvatar" style="display: none"/>
          <button class="dashboard_button button"
                  v-on:click="change_avatar"><?php echo __( 'Change avatar', 'gladiator-theme' ); ?>
          </button>
        </div>
        <div class="booster_dashboard_find_order_btn mt-3">
          <button  onclick="window.location.href = '<?php echo get_permalink($Find_orders_page_url);?>';" class="dashboard_button w-100 button alt">
				<?php echo __( 'Find orders', 'gladiator-theme' ); ?>
          </button>
        </div>
      </div>
      <div class="style_rectangle booster_dashboard_info_balance_block">
        <div class="booster_dashboard_info_balance">
          <h5>
            <?php echo __( 'Balance', 'gladiator-theme' ); ?>
          </h5>

          <h5 data-booster_balance="1" class="yellow_text m-0 ml-2">
            <span>$</span>
						<?php echo $balance; ?>
          </h5>
        </div>
        <div class="booster_dashboard_info_balance_withdraw mt-3">
          <button class="dashboard_button w-100 button"
                  v-on:click="show_withdraw">
            <?php echo __( 'Withdraw', 'gladiator-theme' ); ?>
          </button>
        </div>
      </div>
      <div class="style_rectangle booster_dashboard_info_notification_block">
        <div class="booster_dashboard_info_notification_title mb-3">
					<h5>
            <?php echo __( 'Notification', 'gladiator-theme' ); ?>
          </h5>
        </div>
        <div class="booster_dashboard_info_notification_container">

        </div>
        <div class="booster_dashboard_info_notification_btn">
          <button class="dashboard_button w-100 button">
            <?php echo __( 'View All', 'gladiator-theme' ); ?>
          </button>
        </div>
      </div>


        <!-- Withdraw Booster -->
        <div id="booster_popap_withdraw">
            <div class="cl_close_booster_popap" v-on:click="close_view_withdraw">Close</div>
            <div class="withdraw_container">
                <div class="withdraw_fields my-3">
                    <input v-model="withdrawal.amount" type="text" placeholder="withdrawal amount">
                </div>
                <div class="withdraw_fields my-3">
                    <h4 class="text-center">
                      <?php echo __( 'Payments methods', 'gladiator-theme' ); ?>
                    </h4>
                      <ul class="withdraw_fields_list mt-3">
                        <li v-for="([key, value], index) in Object.entries(withdrawal.payment_method_list)">
                          <input v-model="withdrawal.payment_method" :id="key" name="payment_method_list" type="radio" :value="value">
                            <label :for="key">{{value}}</label>
                        </li>
                    </ul>
                </div>
                <div class="withdraw_fields my-3 text-center">
                    <button class="dashboard_button button"
                            v-on:click="ex_withdraw">
                      <?php echo __( 'OK', 'gladiator-theme' ); ?>
                    </button>
                </div>
            </div>
        </div>
        <!-- /Withdraw Booster -->
    </div>
  </div>
</div>
<!--  <?php echo str_replace( $_SERVER['DOCUMENT_ROOT'], '', __FILE__ ); ?> ]-->