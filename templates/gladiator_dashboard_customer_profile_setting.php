<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<?php
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $usermeta = get_user_meta($user_id);
    $usermeta_ = [];
    if (is_array($usermeta) && !empty($usermeta)) {
        $usermeta_ = array_map('reset', $usermeta);
    }

    if ( !array_key_exists('avatar',$usermeta_))
    {
        $usermeta_['avatar'] = plugins_url().'/gladiator_dashboard/img/no_avatar.png';
    }
    if ( !array_key_exists('email',$usermeta_))
    {
        $usermeta_['email'] = $current_user->user_email;
    }
?>
<script>
    const usermeta = <?php echo json_encode($usermeta_);?>;
</script>
<div class="booster_panel_container" >


    <?php
        $filename = __DIR__.'/_customer_left_menu.php';
        if (file_exists($filename)) {
            include $filename;
        }
    ?>


    <div  class="booster_dashboard_container" >
        <?php
            $inc_title = __('Profile setting','gladiator-theme');
            $filename = __DIR__.'/_customer_top_block.php';
            if (file_exists($filename)) {
                include $filename;
            }
        ?>

        <!-- Payment methods -->
        <div id="app_dashboard_customer_profile_setting" class="list_order_table customer_profile_table" >


            <div class="list_order_table_body_tr">
                <div class="table_td_line d-flex flex-column" >
                    <?php echo __('Avatar','gladiator-theme');?><br>
                    <img :src="customer_profile.avatar" width="100" >
                    <input class="customer_fields" id="uploadAvatar" @change="uploadAvatar" type="file" accept="image/aces" style="display: none">

                  <label for="uploadAvatar" class="dashboard_button  button"  >
		                <?php echo __( 'Change avatar', 'gladiator-theme' ); ?>
                  </label>

                </div>


            </div>

            <div class="list_order_table_body_tr">
            <div class="table_td_line" >
			        <?php echo __('Username (for chats) ','gladiator-theme');?><br>
              <input class="customer_fields" v-model="customer_profile.chats_user_name" type="text"  >
            </div>
          </div>

          <div class="list_order_table_body_tr">
                <div class="table_td_line" >
                    <?php echo __('First name','gladiator-theme');?><br>
                    <input class="customer_fields" v-model="customer_profile.first_name" type="text"  >
                </div>
            </div>

            <div class="list_order_table_body_tr">
                <div class="table_td_line" >
                    <?php echo __('Last name','gladiator-theme');?><br>
                    <input class="customer_fields" v-model="customer_profile.last_name" type="text"  >
                </div>
            </div>

            <div class="list_order_table_body_tr">
                <div class="table_td_line" >
                    <?php echo __('E-Mail','gladiator-theme');?><br>
                    <input class="customer_fields" v-model="customer_profile.email" type="text"  >
                </div>
            </div>

            <div class="list_order_table_body_tr">
                <div class="table_td_line" >
                    <?php echo __('Discord ID','gladiator-theme');?><br>
                    <input class="customer_fields" v-model="customer_profile.account_discort_tag" type="text"  >
                </div>
            </div>

            <div class="list_order_table_body_tr">
                <div class="table_td_line" >
                    <?php echo __('Your Phone','gladiator-theme');?><br>
                    <input class="customer_fields" v-model="customer_profile.account_tel" type="text"  >
                </div>
            </div>


            <div class="list_order_table_body_tr">
                <div class="table_td_line" >
                   <button class="dashboard_button w-25 button" v-on:click="save" >
                     <?php echo __('Save','gladiator-theme');?></button>
                </div>
            </div>

        </div>

        <!-- /Payment methods -->
    </div>
</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ]-->