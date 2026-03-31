<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->

<div class="gladiator_dashboard_tabs" >
    <ul>
        <?php
        $current_template = get_page_template_slug(get_the_ID());

        //$core_class->_test_create_boosters_app();
       // $core_class->_test_withdrawal();


        $pages_dash = $core_class->_get_list_dashboard_pages();

        $postion = [
            'gladiator_dashboard_admin_orders_tmpl.php',
            'gladiator_dashboard_admin_choose_app_tmpl.php',
            'gladiator_dashboard_admin_order_completion_tmpl.php',
            'gladiator_dashboard_admin_withdrawal_requests_tmpl.php',
            //'gladiator_dashboard_admin_dashboard_view_chats_tmpl.php',
        ];

        foreach ($pages_dash as $item_page) {
            if ($item_page['template'] == 'gladiator_dashboard_admin_tmpl.php') {
                continue;
            }
            if ( in_array($item_page['template'],$postion) ) {
                $active = ($item_page['template'] == $current_template) ? 'actv' : '';
                $title = explode('-', $item_page['title']);
                echo "
                <li class='$active'>
                    <a href='{$item_page['url']}'>
                        {$title[1]}
                    </a>
                </li>
            ";
            }
        }

        ?>
        <li>
            <a href="#" class="show_notifications"> <?php echo __('Notifications','gladiator-theme');?>  ( <span id="count_notifications" >0</span> <span><?php echo __('new','gladiator-theme');?></span> ) </a>
        </li>
    </ul>
</div>

<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?>] -->