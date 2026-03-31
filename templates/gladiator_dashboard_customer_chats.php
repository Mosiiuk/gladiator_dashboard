<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<div class="booster_panel_container" >


    <?php
       // $core_class->clear_data(); //delete post_ype boosters_withdrawal and booster_applicant

        $current_user = wp_get_current_user();
        $author_id = $current_user->ID;

        $filename = __DIR__.'/_customer_left_menu.php';
        if (file_exists($filename)) {
            include $filename;
        }
    ?>
    <script>
        const current_author_id = <?php echo $author_id;  ?>
    </script>

    <div class="booster_dashboard_container" >

        <?php
            $inc_title = get_the_title();
            $filename = __DIR__.'/_customer_top_block.php';
            if (file_exists($filename)) {
                include $filename;
            }
        ?>

        <div class="booster_dashboard_chat" id="app_customer_chat" >

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
</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ]-->