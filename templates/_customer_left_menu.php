<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<div id="booster_left_menu" >
    <ul>
        <?php
            global $post;
            $pages = $core_class->_get_list_dashboard_pages('customer');
            $current_template = get_page_template_slug($post->ID);

            if ( is_array($pages) && count($pages)) {
                $postion = [
                    'gladiator_dashboard_customer_panel_tmpl.php',
                    'gladiator_dashboard_customer_order_list_tmpl.php',
                    'gladiator_dashboard_customer_profile_setting_tmpl.php',
                    //'gladiator_dashboard_customer_chats_tmpl.php',
                ];

                $pageMap = [];
                foreach ($pages as $key => $item) {
                    $pageMap[$item['template']] = $item;
                }

                $sortedPages = [];
                foreach ($postion as $template) {
                    if (isset($pageMap[$template])) {
                        $sortedPages[] = $pageMap[$template];
                    }
                }


                foreach ($sortedPages as $key => $item) {
                    $active = ($item['template'] == $current_template) ? 'actv' : '';
                    $title = explode('-', $item['title']);
                    echo "<li class='$active' ><a href='$item[url]'>{$title[1]}</a></li>\n";
                }
            }
        ?>
        <li>
            <a href="<?php echo wp_logout_url(); ?>" > <?php echo __('Log out','gladiator-theme');?> </a>
        </li>
    </ul>
</div>
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ]-->