<!-- [ <?php echo str_replace( $_SERVER['DOCUMENT_ROOT'], '', __FILE__ ); ?> -->
<?php
$current_user         = wp_get_current_user();
$custom_profile_image = get_field( 'custom_profile_image', 'user_' . $current_user->ID );

$profile_image = $custom_profile_image ? $custom_profile_image : plugins_url() . '/gladiator_dashboard/img/no_avatar.png';

$balance = get_field( 'gbcoin', 'user_' . $current_user->ID );
$balance = (float) $balance ? $balance : 0;
?>

<div class="booster_panel_container">

	<?php
	$filename = __DIR__ . '/_customer_left_menu.php';
	if ( file_exists( $filename ) ) {
		include $filename;
	}
	?>

  <div class="booster_dashboard_container">

		<?php
		$inc_title = __( 'Dashboard', 'gladiator-theme' );
		$filename  = __DIR__ . '/_customer_top_block.php';
		if ( file_exists( $filename ) ) {
			include $filename;
		}
		?>

    <div class="booster_dashboard_container_blocks">
<!--      <div class="style_rectangle booster_dashboard_info_block">-->

<!--        <div class="booster_dashboard_find_order_btn">-->
<!--          <a href="--><?php //echo get_site_url(); ?><!--">-->
<!--            <button class="dashboard_button">-->
<!--							--><?php //echo __( 'Browse Offers', 'gladiator-theme' ); ?>
<!--            </button>-->
<!--          </a>-->
<!--        </div>-->
<!--      </div>-->
<!--      <div class="style_rectangle booster_dashboard_info_balance_block">-->
<!--        <div class="booster_dashboard_info_balance">-->
<!--          <span>--><?php //echo __( 'Balance', 'gladiator-theme' ); ?><!--</span>-->
<!--          <span>$</span>-->
<!--          <span data-booster_balance="1">--><?php //echo $balance; ?><!--</span>-->
<!--        </div>-->
<!--      </div>-->
<!--      <div class="style_rectangle booster_dashboard_info_notification_block">-->
<!--        <div class="booster_dashboard_info_notification_title">-->
<!--					--><?php //echo __( 'Notification', 'gladiator-theme' ); ?>
<!--        </div>-->
<!--        <div class="booster_dashboard_info_notification_container">-->
<!---->
<!--        </div>-->
<!--        <div class="booster_dashboard_info_notification_btn">-->
<!--          <button class="dashboard_button ">--><?php //echo __( 'View All', 'gladiator-theme' ); ?><!--</button>-->
<!--        </div>-->
<!--      </div>-->

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
                      <a href="<?php echo site_url(); ?>" class="dashboard_button mt-5 w-100 button">
          							<?php echo __( 'Browse Offers', 'gladiator-theme' ); ?>
                      </a>
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

    </div>
  </div>
</div>
<!--  <?php echo str_replace( $_SERVER['DOCUMENT_ROOT'], '', __FILE__ ); ?> ]-->