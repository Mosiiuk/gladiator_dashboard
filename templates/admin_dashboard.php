<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<div class="gladiator_dashboard" >
    <h1><?php echo get_the_title();?></h1>
    <?php
        $filename = __DIR__.'/_gladiator_dashboard_tabs.php';
        if (file_exists($filename)) {
            include $filename;
        }
    ?>
</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ] -->