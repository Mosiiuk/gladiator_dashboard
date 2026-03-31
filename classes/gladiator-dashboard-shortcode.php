<?php

require_once(plugin_dir_path(__FILE__) . '/gladiator-dashboard-core.php');

class Gladiator_Dashboard_Shortcode {
    private $core_class;

    public function __construct() {
        $this->core_class = new Gladiator_Dashboard_Core();
        add_shortcode('gladiator_dashboard_admin_dashboard', [$this, 'gladiator_dashboard_admin_dashboard_callback']);

        add_shortcode('gladiator_dashboard_admin_dashboard_orders', [$this, 'admin_dashboard_orders_shortcode_callback']);
        add_shortcode('gladiator_dashboard_admin_choose_app', [$this, 'admin_gladiator_dashboard_admin_choose_app']);
        add_shortcode('gladiator_dashboard_admin_order_completion', [$this, 'admin_gladiator_dashboard_admin_order_completion']);
        add_shortcode('gladiator_dashboard_admin_withdrawal_requests', [$this, 'admin_gladiator_dashboard_admin_withdrawal_requests']);
        add_shortcode('gladiator_dashboard_admin_dashboard_view_chats', [$this, 'admin_gladiator_dashboard_admin_dashboard_view_chats']);

        add_shortcode('gladiator_dashboard_booster_panel', [$this, 'gladiator_dashboard_booster_panel_callback']);
        add_shortcode('gladiator_dashboard_booster_find_orders', [$this, 'gladiator_dashboard_booster_find_orders_callback']);
        add_shortcode('gladiator_dashboard_booster_my_orders', [$this, 'gladiator_dashboard_booster_my_orders_callback']);
        add_shortcode('gladiator_dashboard_booster_legal', [$this, 'gladiator_dashboard_booster_legal_callback']);
        add_shortcode('gladiator_dashboard_booster_withdrawal_requests', [$this, 'gladiator_dashboard_booster_withdrawal_requests_callback']);
        add_shortcode('gladiator_dashboard_booster_payment_method', [$this, 'gladiator_dashboard_booster_payment_method_callback']);
        add_shortcode('gladiator_dashboard_booster_subscribe_order', [$this, 'gladiator_dashboard_booster_subscribe_order_callback']);
        add_shortcode('gladiator_dashboard_booster_chats', [$this, 'gladiator_dashboard_booster_chats_callback']);

        add_shortcode('gladiator_dashboard_customer_panel', [$this, 'gladiator_dashboard_customer_panel_callback']);
        add_shortcode('gladiator_dashboard_customer_order_list', [$this, 'gladiator_dashboard_customer_order_list_callback']);
        add_shortcode('gladiator_dashboard_customer_profile_setting', [$this, 'gladiator_dashboard_customer_profile_setting_callback']);
        add_shortcode('gladiator_dashboard_customer_chats', [$this, 'gladiator_dashboard_customer_chats_callback']);


    }

    //----------- CUSTOMER PANEL -------

    public function gladiator_dashboard_customer_chats_callback()
    {
        wp_redirect(home_url());
        exit;

        $this->access_page_customer();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/gladiator_dashboard_customer_chats.php');
        $output = ob_get_clean();

        return $output;
    }

    public function gladiator_dashboard_customer_profile_setting_callback()
    {
        $this->access_page_customer();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/gladiator_dashboard_customer_profile_setting.php');
        $output = ob_get_clean();

        return $output;
    }

    public function gladiator_dashboard_customer_order_list_callback()
    {
        $this->access_page_customer();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/gladiator_dashboard_customer_order_list.php');
        $output = ob_get_clean();

        return $output;
    }

    public function gladiator_dashboard_customer_panel_callback()
    {
        $this->access_page_customer();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/gladiator_dashboard_customer_panel.php');
        $output = ob_get_clean();

        return $output;
    }

    //----------- /CUSTOMER PANEL -------

    //----------- BOOSTER PANEL -------
    public function gladiator_dashboard_booster_subscribe_order_callback()
    {
        $this->access_page_booster();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/gladiator_dashboard_booster_subscribe_order.php');
        $output = ob_get_clean();

        return $output;
    }

    public function gladiator_dashboard_booster_payment_method_callback()
    {
        $this->access_page_booster();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/gladiator_dashboard_booster_payment_method.php');
        $output = ob_get_clean();

        return $output;
    }

    public function gladiator_dashboard_booster_withdrawal_requests_callback()
    {
        $this->access_page_booster();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/dashboard_booster_withdrawal_requests.php');
        $output = ob_get_clean();

        return $output;
    }

    public function gladiator_dashboard_booster_my_orders_callback()
    {
        $this->access_page_booster();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/dashboard_booster_my_orders.php');
        $output = ob_get_clean();

        return $output;
    }

    public function gladiator_dashboard_booster_legal_callback()
    {
        $this->access_page_booster();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/dashboard_booster_legal.php');
        $output = ob_get_clean();

        return $output;
    }

    public function gladiator_dashboard_booster_find_orders_callback()
    {
        $this->access_page_booster();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/dashboard_booster_find_orders.php');
        $output = ob_get_clean();

        return $output;
    }

    public function gladiator_dashboard_booster_panel_callback()
    {
        $this->access_page_booster();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/dashboard_booster_panel.php');
        $output = ob_get_clean();

        return $output;
    }

    public function gladiator_dashboard_booster_chats_callback()
    {
        wp_redirect(home_url());
        exit;

        $this->access_page_booster();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/dashboard_booster_chats.php');
        $output = ob_get_clean();

        return $output;
    }
    //----------- /BOOSTER PANEL ------


    public function admin_gladiator_dashboard_admin_dashboard_view_chats()
    {
        wp_redirect(home_url());
        exit;

        $this->access_page();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/admin_dashboard_view_chats.php');
        $output = ob_get_clean();

        return $output;
    }

    public function admin_gladiator_dashboard_admin_withdrawal_requests()
    {
        $this->access_page();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/admin_withdrawal_requests.php');
        $output = ob_get_clean();

        return $output;
    }

    public function admin_gladiator_dashboard_admin_order_completion()
    {
        $this->access_page();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/admin_order_completion.php');
        $output = ob_get_clean();

        return $output;
    }

    public function admin_gladiator_dashboard_admin_choose_app()
    {
        $this->access_page();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/admin_dashboard_choose_app.php');
        $output = ob_get_clean();

        return $output;
    }

    public function gladiator_dashboard_admin_dashboard_callback()
    {
        $this->access_page();

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/admin_dashboard.php');
        $output = ob_get_clean();

        return $output;
    }

    public function admin_dashboard_orders_shortcode_callback($atts, $content = '') {
        $this->access_page();

        $atts = shortcode_atts([
            'some' => '',
        ], $atts);

        ob_start();
        $core_class= $this->core_class;
        include(plugin_dir_path(__FILE__) . '/../templates/admin_dashboard_orders.php');
        $output = ob_get_clean();

        return $output;
    }

    private function access_page()
    {
        // || !current_user_can('admin_dashboard')
        if (!is_user_logged_in() && (!current_user_can('administrator') || !current_user_can('admin_dashboard') )  )
        {
            wp_redirect(home_url());
            exit;
        }
    }

    private function access_page_booster()
    {
        if (!is_user_logged_in() || !current_user_can('boosters')) {
            wp_redirect(home_url());
            exit;
        }
    }

    private function access_page_customer()
    {
        if (!is_user_logged_in() || (!current_user_can('subscriber') && !current_user_can('customer'))) {
            wp_redirect(home_url());
            exit;
        }
    }

}

?>