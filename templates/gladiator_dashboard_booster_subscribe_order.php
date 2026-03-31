<!-- [ <?php echo str_replace( $_SERVER['DOCUMENT_ROOT'], '', __FILE__ ); ?> -->
<div class="booster_panel_container">


	<?php
	$filename = __DIR__ . '/_booster_left_menu.php';
	if ( file_exists( $filename ) ) {
		include $filename;
	}

	$_subscribe_categorys = $core_class->booster_subscribe_category_Instance->get_subscribe_category();
	?>
  <script>
      const booster_subscribe_category =<?php echo ( count( $_subscribe_categorys ) ) ? json_encode( $_subscribe_categorys ) : json_encode( [] );?>;
  </script>

  <div class="booster_dashboard_container">
		<?php
		$inc_title = __( 'Subscribe to Orders from Categories', 'gladiator-theme' );
		$filename  = __DIR__ . '/_booster_top_block.php';
		if ( file_exists( $filename ) ) {
			include $filename;
		}
		?>

    <!-- LIST CATEGORY -->
    <div id="app_dashboard_booster_subscribe_order" class="booster_subscribe_order_container">
			<?php
			echo $core_class->booster_subscribe_category_Instance->list_cat();
			?>
      <button class="dashboard_button d-block button w-25 mx-auto mt-3" v-on:click="save_subscribe_order">
				<?php echo __( 'Save', 'gladiator-theme' ); ?>
      </button>
    </div>

    <!-- /LIST CATEGORY -->
  </div>
</div>
<!--  <?php echo str_replace( $_SERVER['DOCUMENT_ROOT'], '', __FILE__ ); ?> ]-->