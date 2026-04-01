<?php
/*
Plugin Name: Gladiator Dashboard
Description: Creation of Dashboards for adminers, boosters and customers
Version: 1.0
Author: Webcapitan
Author URI: https://webcapitan.com
Text Domain: gladiator_dashboard
License: GPL2
*/

define( 'booster_role_name', 'boosters' );
define( 'admin_panel_role_name', 'admin_dashboard' );

require_once(plugin_dir_path(__FILE__) . 'classes/gladiator-dashboard-shortcode.php'); // and other classes

class Gladiator_Dashboard_Plugin {
    private $pages_list = [
        [
            'slug' => 'admin_dashboard',
            'title' => 'Admin Panel - Panel',
            'content' => '[gladiator_dashboard_admin_dashboard]', // shortcode
            'template' => 'gladiator_dashboard_admin_tmpl.php',
        ],
        [
            'slug' => 'admin_dashboard_orders',
            'title' => 'Admin Panel - New Orders',
            'content' => '[gladiator_dashboard_admin_dashboard_orders]', // shortcode
            'template' => 'gladiator_dashboard_admin_orders_tmpl.php',
        ],
        [
            'slug' => 'admin_dashboard_choose_app',
            'title' => 'Admin Panel - Choosing applicant for order',
            'content' => '[gladiator_dashboard_admin_choose_app]', // shortcode
            'template' => 'gladiator_dashboard_admin_choose_app_tmpl.php',
        ],
        [
            'slug' => 'admin_dashboard_order_completion',
            'title' => 'Admin Panel - Order completion',
            'content' => '[gladiator_dashboard_admin_order_completion]', // shortcode
            'template' => 'gladiator_dashboard_admin_order_completion_tmpl.php',
        ],
        [
            'slug' => 'admin_dashboard_withdrawal_requests',
            'title' => 'Admin Panel - Withdrawal requests',
            'content' => '[gladiator_dashboard_admin_withdrawal_requests]', // shortcode
            'template' => 'gladiator_dashboard_admin_withdrawal_requests_tmpl.php',
        ],
        [
            'slug' => 'admin_dashboard_view_chats',
            'title' => 'Admin Panel - View chats',
            'content' => '[gladiator_dashboard_admin_dashboard_view_chats]', // shortcode
            'template' => 'gladiator_dashboard_admin_dashboard_view_chats_tmpl.php',
        ],

        //-------- Booster pages ----------
        [
            'slug' => 'booster_panel',
            'title' => 'Booster Panel - Dashboard',
            'content' => '[gladiator_dashboard_booster_panel]', // shortcode
            'template' => 'gladiator_dashboard_booster_panel_tmpl.php',
            'type' =>'booster'
        ],
        [
            'slug' => 'booster_find_orders',
            'title' => 'Booster Find orders - Find orders',
            'content' => '[gladiator_dashboard_booster_find_orders]', // shortcode
            'template' => 'gladiator_dashboard_booster_find_orders_tmpl.php',
            'type' =>'booster'
        ],
        [
            'slug' => 'booster_my_orders',
            'title' => 'Booster My orders - My orders',
            'content' => '[gladiator_dashboard_booster_my_orders]', // shortcode
            'template' => 'gladiator_dashboard_booster_my_orders_tmpl.php',
            'type' =>'booster'
        ],
        [
            'slug' => 'booster_withdrawal_requests',
            'title' => 'Booster Withdrawal- Withdrawal',
            'content' => '[gladiator_dashboard_booster_withdrawal_requests]', // shortcode
            'template' => 'gladiator_dashboard_booster_withdrawal_requests_tmpl.php',
            'type' =>'booster'
        ],
        [
            'slug' => 'booster_subscribe_order',
            'title' => 'Booster Subscribe to Orders - Subscribe to Orders',
            'content' => '[gladiator_dashboard_booster_subscribe_order]', // shortcode
            'template' => 'gladiator_dashboard_booster_subscribe_order_tmpl.php',
            'type' =>'booster'
        ],
        [
            'slug' => 'booster_payment_method',
            'title' => 'Booster Payment methods - Payment methods',
            'content' => '[gladiator_dashboard_booster_payment_method]', // shortcode
            'template' => 'gladiator_dashboard_booster_payment_method_tmpl.php',
            'type' =>'booster'
        ],
        [
            'slug' => 'booster_legal',
            'title' => 'Booster Legal - Legal',
            'content' => '[gladiator_dashboard_booster_legal]', // shortcode
            'template' => 'gladiator_dashboard_booster_legal_tmpl.php',
            'type' =>'booster'
        ],
        [
            'slug' => 'booster_chats',
            'title' => 'Booster Chats - Chats',
            'content' => '[gladiator_dashboard_booster_chats]', // shortcode
            'template' => 'gladiator_dashboard_booster_chats_tmpl.php',
            'type' =>'booster'
        ],
        //-------- /Booster pages ----------

        //-------- CUSTOMER PAGES ----------
        [
            'slug' => 'customer_panel',
            'title' => 'Customer Panel - Dashboard',
            'content' => '[gladiator_dashboard_customer_panel]', // shortcode
            'template' => 'gladiator_dashboard_customer_panel_tmpl.php',
            'type' =>'customer'
        ],
        [
            'slug' => 'customer_order_list',
            'title' => 'Customer My orders  - My orders',
            'content' => '[gladiator_dashboard_customer_order_list]', // shortcode
            'template' => 'gladiator_dashboard_customer_order_list_tmpl.php',
            'type' =>'customer'
        ],
        [
            'slug' => 'customer_profile_setting',
            'title' => 'Customer Profile - Profile',
            'content' => '[gladiator_dashboard_customer_profile_setting]', // shortcode
            'template' => 'gladiator_dashboard_customer_profile_setting_tmpl.php',
            'type' =>'customer'
        ],
        [
            'slug' => 'customer_chats',
            'title' => 'Customer Chats - Chats',
            'content' => '[gladiator_dashboard_customer_chats]', // shortcode
            'template' => 'gladiator_dashboard_customer_chats_tmpl.php',
            'type' =>'customer'
        ],
        //-------- /CUSTOMER PAGES ---------
    ];

    private $acf_fields_create=[
        'wysiwyg'=>[
           'template'=>'gladiator_dashboard_booster_legal_tmpl.php',
           'group_name'=>'Display Content Settings',
           'field_name'=>'display_content',
           'field_label'=>'Display Content',
        ],
    ];
    public $Gladiator_Dashboard_TelegramBot_Instance;

    public function __construct() {
        $this->shortcode_handler = new Gladiator_Dashboard_Shortcode();
        $this->Gladiator_Dashboard_TelegramBot_Instance = new Gladiator_Dashboard_TelegramBot();

        // Hook activation and deactivation methods
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        // Hook the set_title_info method
        add_filter('display_post_states', [$this, 'set_title_info'], 10, 2);

        add_shortcode('gladiator_dashboard_admin_dashboard_orders', [$this->shortcode_handler, 'admin_dashboard_orders_shortcode_callback']);

        add_action('wp_enqueue_scripts', [$this,'wp_enqueue_scripts'],99);


        add_action('template_redirect',  [$this,'template_redirect']);

        add_action('wp_logout', [$this,'wp_logout_booster']);

        add_action('init', [$this,'init']);
        add_action('acf/init', [$this,'add_acf_field_to_user_profile']);


        add_filter('bulk_actions-edit-shop_order', [$this,'admin_shop_order_bulk']);
        add_action('admin_action_clear_booster_price', [$this,'handle_clear_booster_price']);
    }

    public function admin_shop_order_bulk($actions)
    {
        $actions['clear_booster_price'] = 'Clear Booster Price';
        return $actions;
    }

    public function handle_clear_booster_price()
    {
        $order_ids = isset($_GET['post']) ? array_map('intval', $_GET['post']) : array();

        if (empty($order_ids)) {
            return;
        }


        foreach($order_ids as $order_id)
        {
            $empty=[];
            update_post_meta($order_id, 'GDP_boosters_products_prices', $empty);
        }

        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    public function wp_logout_booster()
    {
        if (isset($_SESSION['admin_redirected'])) {
            unset($_SESSION['admin_redirected']);
        }

        if (isset($_SESSION['booster_redirected'])) {
            unset($_SESSION['booster_redirected']);
        }
        if (isset($_SESSION['customer_redirected'])) {
            unset($_SESSION['customer_redirected']);
        }
    }

    public function template_redirect()
    {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();

            if (in_array(booster_role_name, $user->roles)) {
                $booster_page_id = $this->find_page_id_by_shortcode('[gladiator_dashboard_booster_panel]');
                if (!isset($_SESSION['booster_redirected']) && $booster_page_id) {
                    $_SESSION['booster_redirected'] = true;
                    wp_redirect(get_permalink($booster_page_id));
                    exit;
                }
            }

            if (in_array(admin_panel_role_name, $user->roles)) {
                $booster_page_id = $this->find_page_id_by_shortcode('[gladiator_dashboard_admin_dashboard_orders]');
                if (!isset($_SESSION['admin_redirected']) && $booster_page_id) {
                    $_SESSION['admin_redirected'] = true;
                    wp_redirect(get_permalink($booster_page_id));
                    exit;
                }
            }

            if (in_array('subscriber', $user->roles) || in_array('customer', $user->roles)  )
            {
                $page_id = $this->find_page_id_by_shortcode('[gladiator_dashboard_customer_panel]');
                if (!isset($_SESSION['customer_redirected']) && $page_id) {
                    $_SESSION['customer_redirected'] = true;
                    wp_redirect(get_permalink($page_id));
                    exit;
                }
            }
        }
    }

    public function find_page_id_by_shortcode($shortcode) {
        $pages = get_pages();
        foreach ($pages as $page) {
            $content = $page->post_content;
            if (strpos($content, $shortcode) !== false) {
                return $page->ID;
            }
        }
        return false;
    }

    public function init()
    {

        if ( $this->Gladiator_Dashboard_TelegramBot_Instance->is_set_hook()===false )
        {
            $this->Gladiator_Dashboard_TelegramBot_Instance->set_hook();
        }

        if (isset($_GET['gladiator_tg_hook']) && (int)$_GET['gladiator_tg_hook']==1001)
        {
            $this->Gladiator_Dashboard_TelegramBot_Instance->update_hook();
            http_response_code(200);
            exit();
        }

        /*if (!session_id()) {
            session_start();
        }*/

        $this->add_users_role();
    }

    /**
     * Call by add_action('acf/init', [$this,'add_acf_field_to_user_profile']);
     */
    public function add_acf_field_to_user_profile()
    {
        if( function_exists('acf_add_local_field_group') ) {
            acf_add_local_field_group(array(
                'key' => 'group_boosters_profile_fields', // Unique key for the field group
                'title' => 'Additional Fields for Boosters', // Field group title
                'location' => array(
                    array(
                        array(
                            'param' => 'user_role', // Condition for user role
                            'operator' => '==',
                            'value' => 'boosters', // User role
                        ),
                    ),
                ),
                'fields' => array(
                    array(
                        'key' => 'field_custom_profile_image', // Unique key for the field
                        'label' => 'Profile Image', // Field label
                        'name' => 'custom_profile_image', // Name to store the value
                        'type' => 'image', // Field type
                        'instructions' => 'Upload a profile image', // User instructions
                        'required' => false, // Is the field required
                        'return_format' => 'url', // Value return format (image URL)
                        'mime_types' => 'jpg, jpeg, png', // Allowed image MIME types
                    ),
                    // You can add other fields here in a similar manner
                    array(
                        'key' => 'field_booster_balance',
                        'label' => 'Booster balance',
                        'name' => 'booster_balance',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),

                    // Booster Payments fields

                    array(
                        'key' => 'field_booster_paypal',
                        'label' => 'PayPal',
                        'name' => 'booster_paypal',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),

                    array(
                        'key' => 'field_booster_wiseemail',
                        'label' => 'Wise Email',
                        'name' => 'booster_wiseemail',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),

                    array(
                        'key' => 'field_booster_usdt_trc20',
                        'label' => 'USDT TRC20',
                        'name' => 'booster_usdt_trc20',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                ),
                'position' => 'normal', // Field group position on the user profile page
                'menu_order' => 0, // Menu order
                'style' => 'default', // Field group style
                'label_placement' => 'top', // Field label placement
                'instruction_placement' => 'label', // Instruction placement
                'hide_on_screen' => array( // Hide on specific admin screens
                    'the_content',
                    'excerpt',
                    'discussion',
                    'comments',
                    'slug',
                ),
            ));
        }
    }

    private function add_users_role() {
        add_role(
            booster_role_name,
            'Boosters',
            array(
                'read'         => true,
                'edit_posts'   => false,
                'upload_files' => false,
            )
        );

        add_role(
            admin_panel_role_name,
            'Admin dashboard',
            array(
                'read'         => true,
                'edit_posts'   => false,
                'upload_files' => false,
            )
        );
    }

    public function wp_enqueue_scripts()
    {
        global $post;
        $current_template = get_page_template_slug($post->ID);

        foreach ($this->pages_list as $page) {
            if ($current_template === $page['template']) {
                wp_enqueue_style('gladiator_dashboard-style', plugin_dir_url(__FILE__) . 'css/gladiator_dashboard.css?time='.time());
                wp_enqueue_style('gladiator_dashboard-jquery-ui', plugin_dir_url(__FILE__) . 'css/jquery.datetimepicker.css');
                wp_enqueue_script('gladiator_dashboard_vue', plugin_dir_url(__FILE__) . 'js/vue.js', NULL, NULL, true);
                wp_enqueue_script('gladiator_dashboard_jquery-datetimepicker', plugin_dir_url(__FILE__) . 'js/jquery.datetimepicker.full.js', NULL, NULL, true);

                wp_enqueue_script('gladiator_dashboard_action', plugin_dir_url(__FILE__) . 'js/dashboard_action.js', NULL, NULL, true);
                wp_enqueue_script('gladiator_dashboard_admin', plugin_dir_url(__FILE__) . 'js/admin_dashboard.js', NULL, NULL, true);
                wp_enqueue_script('gladiator_dashboard_booster', plugin_dir_url(__FILE__) . 'js/booster_dashboard.js', NULL, NULL, true);
                wp_enqueue_script('gladiator_dashboard_customer', plugin_dir_url(__FILE__) . 'js/customer_dashboard.js', NULL, NULL, true);

                if ( !isset($page['type'])) {
                   /* wp_enqueue_script('gladiator-dashboard-admin_js', '', array(), null, true); //  "true" - to footer
                    $custom_script = '
                       const is_gladiator_dashboard_admin = true;
                    ';
                    wp_add_inline_script('gladiator-dashboard-admin_js', $custom_script);*/

                    echo "<script> const is_gladiator_dashboard_admin = true;</script>";
                }

                break; // No need to continue checking other templates
            }
        }

        wp_localize_script( 'jquery', 'DashboardPlugin', array(
            'url' => admin_url('admin-ajax.php'),
            'site_url' => get_site_url(),
            'plugins_url' => plugins_url(),
        ) );
    }

    public function activate() {
        foreach ($this->pages_list as $page) {
            $existing_page = get_page_by_path($page['slug']);

            if (!$existing_page) {
                $new_page_id = wp_insert_post([
                    'post_title' => $page['title'],
                    'post_name' => $page['slug'],
                    'post_content' => $page['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                ]);

                if ($new_page_id) {
                    add_option('gladiator_dashboard_' . $page['slug'] . '_id', $new_page_id);
                    $this->createTemplateAndAssign($new_page_id,$page['title'],$page['template']);
                    update_post_meta($new_page_id, 'is_dashboards', $new_page_id);

                    if (isset($page['type']))
                    {
                        update_post_meta($new_page_id, 'gladiator_dash_board_type', $page['type']);
                    }
                }
            } else {
                // Page already exists, change status to publish
                if ($existing_page->post_status !== 'publish') {
                    $existing_page->post_status = 'publish';
                    wp_update_post($existing_page);
                }
            }
        }
    }

    /**
     * Add ACF to pages
     */
    public function create_acf_for_page()
    {
        if (isset($this->acf_fields_create) && is_array($this->acf_fields_create) && count($this->acf_fields_create)) {
            foreach ($this->acf_fields_create as $key => $item) {

                switch ($key)
                {
                    case 'wysiwyg':
                        $this->create_acf_wysiwyg($item['template'],$item['group_name'],$item['field_name'],$item['field_label']);
                    break;
                }

            }
        }
    }

    /**
     * Create ACF field type wysiwyg, assign to page template
     */
    private function create_acf_wysiwyg($page_template='',$group_name='',$field_name='',$field_label='')
    {
        if( function_exists('acf_add_local_field_group') ) {

            acf_add_local_field_group(array(
                'key' => 'group_'.md5($group_name),
                'title' => $group_name,
                'fields' => array(
                    array(
                        'key' => 'field_'.md5($field_name),
                        'label' => $field_label,
                        'name' => $field_name,
                        'type' => 'wysiwyg',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 1,
                        'delay' => 0,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'page_template',
                            'operator' => '==',
                            'value' => $page_template,
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
            ));

        }
    }

    /**
     * Create page template
     *
     * @param $page_id
     * @param string $template_name
     * @param string $template
     */
    private function createTemplateAndAssign($page_id,$template_name='',$template='') {
        $template_path = get_template_directory() . '/'.$template;
        if (!file_exists($template_path)) {
            $t='$_SERVER["DOCUMENT_ROOT"]';
            $template_content = <<<EOT
<?php
/**
 * Template Name: $template_name
 */
get_header();
?>
<!-- [ <?php echo str_replace($t, '', __FILE__); ?> -->
<?php echo do_shortcode(get_the_content()); ?>
<!-- <?php echo str_replace($t, '', __FILE__); ?> ] -->
<?php
get_footer();
?>
EOT;
            file_put_contents($template_path, $template_content);
        }
        if ($page_id) {
            update_post_meta($page_id, '_wp_page_template', $template);
        }
    }

    /**
     * Action deactivate plugin
     */
    public function deactivate() {
        foreach ($this->pages_list as $page) {
            $page_id = get_option('gladiator_dashboard_' . $page['slug'] . '_id');

            if ($page_id) {
                $existing_page = get_page($page_id);

                if ($existing_page && $existing_page->post_status === 'publish') {
                    $existing_page->post_status = 'draft';
                    wp_update_post($existing_page);
                }
            }
        }
    }

    /**
     * Out post_states page
     *
     * @param $post_states
     * @param $post
     * @return mixed
     */
    public function set_title_info($post_states, $post) {
        foreach ($this->pages_list as $page) {
            if ($post->post_name === $page['slug']) {
                $post_states[] = 'Dashboard pages';
                break;
            }
        }
        return $post_states;
    }
}

// Instantiate the plugin class
$gladiator_dashboard_plugin = new Gladiator_Dashboard_Plugin();

$gladiator_dashboard_plugin->create_acf_for_page();


