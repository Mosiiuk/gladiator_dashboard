<?php

?>
<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<?php
$filename = __DIR__.'/notification/notification_popap.php';
if (file_exists($filename)) {
    include $filename;
}
?>

<div id="gladiator_dashboard_admin_order_completion" class="gladiator_dashboard_admin_dash" style="display: block;" >
    <h1><?php echo __('View chats','gladiator-theme');?></h1>

    <?php
        $current_user = wp_get_current_user();
        $author_id = $current_user->ID;

        $filename = __DIR__.'/_gladiator_dashboard_tabs.php';
        if (file_exists($filename)) {
            include $filename;
        }
    ?>

    <script>
        const current_author_id = <?php echo $author_id;  ?>
    </script>

    <div class="booster_dashboard_chat" id="app_admin_chat" >
        <div class="search_chat">
            <input type="text" v-model="find_chat_order_id" @keyup="find_handleKeyUp" placeholder="Order ID" >
            <button class="dashboard_button button" v-on:click="find_chat" >Find chat</button>
            <button class="dashboard_button button red_button" v-on:click="reset_find_chat" >Reset</button>
        </div>
        <?php
        $filename = __DIR__.'/chat/chat_list.php';
        if (file_exists($filename)) {
            include $filename;
        }
        $filename = __DIR__.'/chat/chat.php';
        if (file_exists($filename)) {
            include $filename;
        }
        ?>
    </div>

</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ] -->