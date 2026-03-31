<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<div class="booster_panel_container" >


    <?php
        $filename = __DIR__.'/_booster_left_menu.php';
        if (file_exists($filename)) {
            include $filename;
        }
    ?>

    <div class="booster_dashboard_container" >

        <?php
            $inc_title = get_the_title();
            $filename = __DIR__.'/_booster_top_block.php';
            if (file_exists($filename)) {
                include $filename;
            }
        ?>

        <div class="booster_dashboard_legal" >
            <?php
                echo get_field('display_content',get_the_ID());
            ?>
        </div>

    </div>
</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ]-->