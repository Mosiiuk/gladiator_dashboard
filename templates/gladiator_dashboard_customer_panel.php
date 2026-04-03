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

    <div class="booster_dashboard_container_blocks customer_dashboard_two_blocks">
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

      <div class="style_rectangle booster_dashboard_info_block customer_dashboard_intro_block">
        <div class="booster_dashboard_info_name">
          <h5>
          <span class="yellow_text">
            <?php echo $full_name; ?>
          </span>
            <span>
            <?php echo __( 'Have, a good day today!', 'gladiator-theme' ); ?>
          </span>
          </h5>
                      <a href="<?php echo site_url(); ?>" class="dashboard_button w-100 button">
          							<?php echo __( 'Browse Offers', 'gladiator-theme' ); ?>
                      </a>
        </div>

      </div>
      <div class="style_rectangle booster_dashboard_info_balance_block booster_dashboard_info_discord_block">
        <div class="booster_dashboard_info_discord">
          <h5>
				    <?php echo __( 'Connect on Discord', 'gladiator-theme' ); ?>
          </h5>
          <p>
				    <?php echo __( 'Join our Discord and contact the team anytime for order help and account support.', 'gladiator-theme' ); ?>
          </p>
          <div class="booster_dashboard_info_discord_btn">
            <a
              href="https://discord.gg/ZVa5Npz3kR"
              class="dashboard_button w-100 button js_customer_dashboard_discord_link"
              target="_blank"
              rel="noopener noreferrer"
            >
				      <?php echo __( 'Contact our 24/7 Discord', 'gladiator-theme' ); ?>
            </a>
          </div>
        </div>
      </div>

    </div>

    <div class="customer_dashboard_discord_popup_overlay" id="customer_dashboard_discord_popup_overlay" aria-hidden="true">
      <div class="customer_dashboard_discord_popup" role="dialog" aria-modal="true" aria-labelledby="customer_dashboard_discord_popup_title">
        <button type="button" class="customer_dashboard_discord_popup_close" id="customer_dashboard_discord_popup_close" aria-label="<?php echo esc_attr__( 'Close popup', 'gladiator-theme' ); ?>">X</button>
        <h3 id="customer_dashboard_discord_popup_title"><?php echo __( 'Next steps on Discord', 'gladiator-theme' ); ?></h3>
        <p><?php echo __( 'After joining our Discord server, please follow these steps:', 'gladiator-theme' ); ?></p>
        <ol class="customer_dashboard_discord_popup_steps">
          <li><?php echo __( 'Open the welcome or support channel and read the server instructions.', 'gladiator-theme' ); ?></li>
          <li><?php echo __( 'Create a ticket or message the support team with your order details.', 'gladiator-theme' ); ?></li>
          <li><?php echo __( 'Share your order number and a short description so we can help you faster.', 'gladiator-theme' ); ?></li>
        </ol>
        <p class="customer_dashboard_discord_popup_note"><?php echo __( 'Our team is available there 24/7.', 'gladiator-theme' ); ?></p>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  (function () {
    var discordLink = document.querySelector('.js_customer_dashboard_discord_link');
    var popupOverlay = document.getElementById('customer_dashboard_discord_popup_overlay');
    var popupClose = document.getElementById('customer_dashboard_discord_popup_close');

    if (!discordLink || !popupOverlay || !popupClose) {
      return;
    }

    var openPopup = function () {
      popupOverlay.classList.add('active');
      popupOverlay.setAttribute('aria-hidden', 'false');
    };

    var closePopup = function () {
      popupOverlay.classList.remove('active');
      popupOverlay.setAttribute('aria-hidden', 'true');
    };

    discordLink.addEventListener('click', function () {
      openPopup();
    });

    popupClose.addEventListener('click', function () {
      closePopup();
    });

    popupOverlay.addEventListener('click', function (event) {
      if (event.target === popupOverlay) {
        closePopup();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closePopup();
      }
    });
  })();
</script>
<!--  <?php echo str_replace( $_SERVER['DOCUMENT_ROOT'], '', __FILE__ ); ?> ]-->
