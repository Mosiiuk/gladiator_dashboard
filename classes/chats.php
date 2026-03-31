<?php

class Chat_Booster_Customer
{
    private $chat_post_type='chat_bstr_cstmr';
    private $chat_message_post_type='chat_bstrcstmr_msg'; // The varchar(20) maximum
    private $allowed_tags_message=[
        'a' => [
            'href' => [],
        ]
    ];
    private $Notifications_Instance;

    public function __construct()
    {
        $this->Notifications_Instance = new gladiator_dashboard_notification();
    }


    public function find_chat_before_create($order_id=0,$product_id=0)
    {
        $args = array(
            'post_type'     => $this->chat_post_type,
            'posts_per_page' => 1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'order_id',
                    'value' => $order_id,
                    'compare' => '=',
                    'type' => 'NUMERIC',
                ),
                array(
                    'key' => 'product_id',
                    'value' => $product_id,
                    'compare' => '=',
                    'type' => 'NUMERIC',
                ),
            ),
        );

        $query = new WP_Query($args);
        $posts = $query->posts;

        if (count($posts))
        {
            return $posts[0]->ID;
        }

        return 0;
    }

    /**
     * Create a new chat Booster_Customer
     * $order_id - woocomerce order id, $product_id - woocomerce product id
     * Status  1 - active(default), 2 - inactive
     *
     * @param int $boosters_id
     * @param int $order_id
     * @param int $product_id
     * @param int $applicant_id
     * @param int $status
     * @return array
     */
    public function create($boosters_id=0,$order_id=0,$product_id=0,$applicant_id=0,$status=1,$default_msg='')
    {
        $order = wc_get_order($order_id);

        if ($order)
        {
            $customer_id = $order->get_user_id();
            $find_chat_id = $this->find_chat_before_create($order_id,$product_id);
            if ( !$find_chat_id )
            {
                $post_data = array(
                    'post_title' => "Chat_Booster_Customer " . date('Y_m_d H_i_s'),
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_type' => $this->chat_post_type,
                );

                $new_post_id = wp_insert_post($post_data);
            }
            else
            {
                $new_post_id = $find_chat_id;
            }

            update_post_meta($new_post_id, 'boosters_id', $boosters_id);
            update_post_meta($new_post_id, 'customer_id', $customer_id?$customer_id:0);
            update_post_meta($new_post_id, 'allowed_users', [$boosters_id,$customer_id]);

            update_post_meta($new_post_id, 'order_id', $order_id);
            update_post_meta($new_post_id, 'product_id', $product_id);
            update_post_meta($new_post_id, 'applicant_id', $applicant_id);
            update_post_meta($new_post_id, 'status', $status);
            update_post_meta($new_post_id, 'default_msg', $default_msg);

        }

        return [
            'order'=>$order,
            'chat_id'=>$new_post_id,
            'boosters_id'=>$boosters_id,
            'customer_id'=>$customer_id?$customer_id:0,
            'order_id'=>$order_id,
            'applicant_id'=>$applicant_id,
            'status'=>$status,
        ];
    }

    /**
     * Get list chats for current booster
     *
     * @return array
     */
    public function get_list_chats_current_booster()
    {
        $current_user = wp_get_current_user();
        $boosters_id = $current_user->ID;
        $result=[];

        if (in_array(booster_role_name, $current_user->roles))
        {
            /*$args = array(
                'post_type'     => $this->chat_post_type,
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'boosters_id',
                        'value' => $boosters_id,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                    array(
                        'key' => 'status',
                        'value' => 1,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                ),
                'orderby' => 'date',
                'order' => 'DESC',
            );*/

            $args = array(
                'post_type'     => $this->chat_post_type,
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'status',
                        'value' => 1,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                    array(
                        'key' => 'boosters_id',
                        'value' => $boosters_id,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                ),
                'orderby' => 'date',
                'order' => 'DESC',
            );

            $query = new WP_Query($args);
            $posts = $query->posts;

           // echo $query->request;

            if (is_array($posts) && count($posts))
            {
                foreach ($posts as $key => $item)
                {
                     $boosters_id = get_post_meta($item->ID,'boosters_id',1);
                     $product_id = get_post_meta($item->ID,'product_id',1);
                     $order_id = get_post_meta($item->ID,'order_id',1);
                     $customer_id = get_post_meta($item->ID,'customer_id',1);
                     $applicant_id = get_post_meta($item->ID,'applicant_id',1);
                     $applicant_status = get_post_meta($applicant_id,'booster_status',1);
                     $allowed_users = get_post_meta($item->ID,'allowed_users',1);

                     if ( !in_array($boosters_id,$allowed_users))
                     {
                         continue;
                     }

                     $result[]=[
                        'chat_id'=>$item->ID,
                        'boosters_id'=>$boosters_id,
                        'booster_info'=>$this->_get_booster_info($boosters_id),
                        'customer_info'=>$this->_get_customer_info($customer_id),
                        'customer_id'=>$customer_id,
                        'order_id'=>get_post_meta($item->ID,'order_id',1),
                        'product_id'=>$product_id,
                        'product_info'=>$this->_get_product_info($order_id,$product_id),
                        'applicant_id'=> $applicant_id,
                        'applicant_status'=>$applicant_status,
                        'status'=>get_post_meta($item->ID,'status',1),
                        'count_message'=>$this->get_count_message_chat($item->ID),
                        'allowed_users'=>$allowed_users,
                        'chat_users_info'=>$this->_chat_users_info($allowed_users),
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * Get list chats for current customer
     *
     * @return array
     */
    public function get_list_chats_current_customer()
    {
        $current_user = wp_get_current_user();
        $customer_id = $current_user->ID;
        $result=[];

        if (in_array('subscriber', $current_user->roles) || in_array('customer', $current_user->roles) )
        {
            /*$args = array(
                'post_type'     => $this->chat_post_type,
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'customer_id',
                        'value' => $customer_id,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                    array(
                        'key' => 'status',
                        'value' => 1,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                ),
                'orderby' => 'date',
                'order' => 'DESC',
            );*/

            $args = array(
                'post_type'     => $this->chat_post_type,
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'status',
                        'value' => 1,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                    array(
                        'key' => 'customer_id',
                        'value' => $customer_id,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                ),
                'orderby' => 'date',
                'order' => 'DESC',
            );

            $query = new WP_Query($args);
            $posts = $query->posts;

            if (is_array($posts) && count($posts))
            {
                foreach ($posts as $key => $item)
                {
                    $boosters_id = get_post_meta($item->ID,'boosters_id',1);
                    $product_id = get_post_meta($item->ID,'product_id',1);
                    $order_id = get_post_meta($item->ID,'order_id',1);
                    $customer_id = get_post_meta($item->ID,'customer_id',1);
                    $allowed_users = get_post_meta($item->ID,'allowed_users',1);
                    $default_msg = get_post_meta($item->ID,'default_msg',1);

                    if ( !in_array($customer_id,$allowed_users))
                    {
                        continue;
                    }

                    $result[]=[
                        'chat_id'=>$item->ID,
                        'boosters_id'=>$boosters_id,
                        'booster_info'=>$this->_get_booster_info($boosters_id),
                        'customer_info'=>$this->_get_customer_info($customer_id),
                        'customer_id'=>$customer_id,
                        'order_id'=>get_post_meta($item->ID,'order_id',1),
                        'product_id'=>$product_id,
                        'product_info'=>$this->_get_product_info($order_id,$product_id),
                        'applicant_id'=>get_post_meta($item->ID,'applicant_id',1),
                        'status'=>get_post_meta($item->ID,'status',1),
                        'default_msg'=>$default_msg?$default_msg:'',
                        'count_message'=>$this->get_count_message_chat($item->ID),
                        'allowed_users'=>$allowed_users,
                        'chat_users_info'=>$this->_chat_users_info($allowed_users),
                    ];
                }
            }
        }

        return $result;
    }

    public function get_chat_by_order_id($order_id=0)
    {
        $current_user = wp_get_current_user();
        $customer_id = $current_user->ID;
        $result=0;

        if ($customer_id)
        {
            $args = array(
                'post_type'     => $this->chat_post_type,
                'posts_per_page' => 1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'order_id',
                        'value' => $order_id,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                    array(
                        'key' => 'customer_id',
                        'value' => $customer_id,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                ),
                'orderby' => 'date',
                'order' => 'DESC',
            );

            $query = new WP_Query($args);
            $posts = $query->posts;

            if (is_array($posts) && count($posts))
            {
                foreach ($posts as $key => $item) {
                    $result=$item->ID;
                }
            }
        }

        return $result;
    }

    private function get_count_message_chat($chat_id=0)
    {
        $args = array(
            'post_type'     => $this->chat_message_post_type,
            'posts_per_page' => -1,
            'post_parent'    => $chat_id,
        );

        $query = new WP_Query($args);

        return $query->found_posts;
    }

    private function set_message_status_readed($chat_id=0,$author_id=0)
    {
        $args = array(
            'post_type'     => $this->chat_message_post_type,
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'author_id',
                    'value' => $author_id,
                    'compare' => '=',
                    'type' => 'NUMERIC',
                ),
            ),
            'post_parent'    => $chat_id,
        );

        $query = new WP_Query($args);
        $posts = $query->posts;

        if (is_array($posts) && count($posts)) {
            foreach ($posts as $key => $item) {
                $change = update_post_meta($item->ID, 'status', 3);
            }
        }
    }

    /**
     * Booster get message by chat id
     * @param int $chat_id
     * @return array
     */
    public function get_list_chat_messages_current_booster($chat_id=0)
    {
        $current_user = wp_get_current_user();
        $boosters_id = $current_user->ID;
        $result=[];

        if (in_array(booster_role_name, $current_user->roles))
        {
            $args = array(
                'post_type'     => $this->chat_message_post_type,
                'posts_per_page' => -1,
                'post_parent'    => $chat_id,
                'orderby' => 'ID',
                'order'  => 'ASC',
            );

            $query = new WP_Query($args);
            $posts = $query->posts;

           // echo $query->request;

            if (is_array($posts) && count($posts))
            {
                foreach ($posts as $key => $item)
                {
                    $boosters_id = get_post_meta($item->ID,'boosters_id',1);
                    $customer_id = get_post_meta($item->ID,'customer_id',1);
                    $author_id = get_post_meta($item->ID,'author_id',1);
                    $sender_type = get_post_meta($item->ID,'sender_type',1);

                    $this->set_message_status_readed($chat_id,$customer_id);
                    $status = get_post_meta($item->ID,'status',1);

                    $allowed_users = get_post_meta($chat_id,'allowed_users',1);
                    if ( !in_array($boosters_id,$allowed_users))
                    {
                        continue;
                    }

                    $self_msg=0;
                    if ( $boosters_id==$author_id )
                        $self_msg = 1;


                    $result[]=[
                        'chat_id'=>$chat_id,
                        'author_id'=>(int)$author_id,
                        'sender_type'=>$sender_type,
                        'self_msg'=>$self_msg,
                        'status'=>$status,
                        'booster_info'=>$this->_get_booster_info($boosters_id),
                        'customer_info'=>$this->_get_customer_info($customer_id),
                        'item'=>$item,
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * Customer get message by chat id
     *
     * @param int $chat_id
     * @return array
     */
    public function get_list_chat_messages_current_customer($chat_id=0)
    {
        $current_user = wp_get_current_user();
        $customer_id = $current_user->ID;
        $result=[];

        if (in_array('subscriber', $current_user->roles) || in_array('customer', $current_user->roles) )
        {
            $args = array(
                'post_type'     => $this->chat_message_post_type,
                'posts_per_page' => -1,
                'post_parent'    => $chat_id,
                'orderby' => 'ID',
                'order'  => 'ASC',
            );

            $query = new WP_Query($args);
            $posts = $query->posts;

            if (is_array($posts) && count($posts))
            {
                foreach ($posts as $key => $item)
                {
                    $boosters_id = get_post_meta($item->ID,'boosters_id',1);
                    $customer_id = get_post_meta($item->ID,'customer_id',1);
                    $author_id = get_post_meta($item->ID,'author_id',1);

                    $sender_type = get_post_meta($item->ID,'sender_type',1);


                    $this->set_message_status_readed($chat_id,$boosters_id);
                    $status = get_post_meta($item->ID,'status',1);

                    $allowed_users = get_post_meta($chat_id,'allowed_users',1);
                    if ( !in_array($customer_id,$allowed_users))
                    {
                        continue;
                    }

                    $self_msg=0;
                    if ( $customer_id==$author_id )
                        $self_msg = 1;

                    $result[]=[
                        'chat_id'=>$chat_id,
                        'author_id'=>(int)$author_id,
                        'sender_type'=>$sender_type,
                        'self_msg'=>$self_msg,
                        'status'=>$status,
                        'booster_info'=>$this->_get_booster_info($boosters_id),
                        'customer_info'=>$this->_get_customer_info($customer_id),
                        'item'=>$item,
                    ];
                }
            }
        }

        return $result;
    }

    private function _chat_users_info($allowed_users=[])
    {
        $result=[];
        if (is_array($allowed_users) && count($allowed_users))
        {
            foreach ($allowed_users as $user_id )
            {
                $user_info = get_userdata($user_id);
                $custom_profile_image = get_field('custom_profile_image','user_'.$user_id);

                $result[]=[
                    'user_id'=>$user_id,
                    'first_name'=>$user_info->first_name,
                    'last_name'=>$user_info->last_name,
                    'user_login'=>$user_info->user_login,
                    'activity_timestamp' => (int)get_user_meta($user_id, 'chat_activity_timestamp', true),
                    'is_window_active' => get_user_meta($user_id, 'is_window_active', true),
                    'time_activity' => $this->time_activity($user_id),
                    'on_status' => 1,
                    'custom_profile_image'=>$custom_profile_image?$custom_profile_image:plugins_url().'/gladiator_dashboard/img/no_avatar.png',
                ];
            }
        }

        return $result;
    }

    private function _get_booster_info($boosters_id=0)
    {
        $user_info = get_userdata($boosters_id);
        $custom_profile_image = get_field('custom_profile_image','user_'.$boosters_id);

        return [
            'boosters_id'=>$boosters_id,
            'first_name'=>$user_info->first_name,
            'last_name'=>$user_info->last_name,
            'user_login'=>$user_info->user_login,
            'user_info'=>$user_info,
            'activity_timestamp' => (int)get_user_meta($boosters_id, 'chat_activity_timestamp', true),
            'is_window_active' => get_user_meta($boosters_id, 'is_window_active', true),
            'time_activity' => $this->time_activity($boosters_id),
            'on_status' => 1,
            'custom_profile_image'=>$custom_profile_image?$custom_profile_image:plugins_url().'/gladiator_dashboard/img/no_avatar.png',
        ];
    }

    private function _get_customer_info($customer_id=0)
    {
        $user_info = get_userdata($customer_id);
        $custom_profile_image = get_field('avatar','user_'.$customer_id);

        return [
            'customer_id'=>$customer_id,
            'first_name'=>$user_info->first_name,
            'last_name'=>$user_info->last_name,
            'user_login'=>$user_info->user_login,
            'activity_timestamp' => (int)get_user_meta($customer_id, 'chat_activity_timestamp', true),
            'is_window_active' => get_user_meta($customer_id, 'is_window_active', true),
            'time_activity' => $this->time_activity($customer_id),
            'on_status' => 1,
            'custom_profile_image'=>$custom_profile_image?$custom_profile_image:plugins_url().'/gladiator_dashboard/img/no_avatar.png',
        ];
    }

    private function _get_product_info($order_id=0,$product_id=0)
    {
        $order = wc_get_order($order_id);
        $items = $order->get_items();

        $product_info = [];
        foreach ($items as $pitem)
        {
            $p_id = $pitem->get_product_id();
            if ( $p_id == $product_id)
            {
                $product = wc_get_product($product_id);
                $product_name = $product->get_name();

                $meta = $pitem->get_formatted_meta_data();
                if ($meta)
                {
                    $product_attributes = $pitem->get_product()->get_attributes();
                    foreach ($product_attributes as $attribute_name => $attribute)
                    {
                        if ($attribute_name == 'region') {
                            continue;
                        }
                        $attribute_label = wc_attribute_label($attribute_name);
                        $formatted_attribute_label = ucfirst($attribute_label);

                        $meta[] = [
                            'display_key' => $formatted_attribute_label,
                            'value' => $attribute,
                        ];
                    }
                }

                $product_info=[
                    'product_name'=>$product_name,
                    'meta'=>$meta,
                ];
                break;
            }
        }

        return $product_info;
    }

    /**
     * Add message to chat Booster_Customer sender Booster
     * $status: 1 - sent, 2 - delivered, 3-read
     *
     * @param int $chat_id
     * @param string $message
     * @param int $status
     * @return array
     */
    public function booster_add_message($chat_id=0,$message="", $customer_id=0, $status=2)
    {
        $current_user = wp_get_current_user();
        $boosters_id = $current_user->ID;

        if (in_array(booster_role_name, $current_user->roles)) {
            $sanitized_content = wp_kses($message, $this->allowed_tags_message);

            if (trim($sanitized_content)=="")
            {
                return [
                    'chat_id'=>$chat_id,
                    'author_id'=>(int)$boosters_id,
                    'boosters_id'=>$boosters_id,
                    'customer_id'=>$customer_id,
                    'message_id'=>0,
                    'sender_type'=>1,
                    'status'=>$status,
                ];
            }

            $post_data = array(
                'post_title' => "Chat_Booster_Customer_Message #" . date('Y_m_d H_i_s'),
                'post_content' => $sanitized_content,
                'post_parent' => $chat_id,
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => $this->chat_message_post_type,
            );

            $new_post_id = wp_insert_post($post_data);

            update_post_meta($new_post_id, 'boosters_id', $boosters_id);
            update_post_meta($new_post_id, 'customer_id', $customer_id);
            update_post_meta($new_post_id, 'author_id', $boosters_id);
            update_post_meta($new_post_id, 'sender_type', 1); // if 1 booster sended message, 2 - customer , 3 - admin
            update_post_meta($new_post_id, 'status', $status);
            update_post_meta($new_post_id, 'chat_id', $chat_id);

            //----- for customer notify -----
            $this->Notifications_Instance->create("You have a new chat message.",0,$customer_id,3,1);

        }

        return [
            'chat_id'=>$chat_id,
            'boosters_id'=>$boosters_id,
            'customer_id'=>$customer_id,
            'message_id'=>$new_post_id,
            'sender_type'=>1,
            'status'=>$status,
        ];
    }

    /**
     * Customer add message to chat
     *
     * @param int $chat_id
     * @param string $message
     * @param int $boosters_id
     * @param int $status
     * @return array
     */
    public function customer_chat_send_message($chat_id=0,$message="", $boosters_id=0, $status=2)
    {
        $current_user = wp_get_current_user();
        $customer_id = $current_user->ID;

        if (in_array('subscriber', $current_user->roles) || in_array('customer', $current_user->roles) )
        {
            $sanitized_content = wp_kses($message, $this->allowed_tags_message);

            if (!$boosters_id)
            {
                $boosters_id = (int)get_post_meta($chat_id,'boosters_id',true);
            }

            if (trim($sanitized_content)=="")
            {
                return [
                    'chat_id'=>$chat_id,
                    'boosters_id'=>$boosters_id,
                    'author_id'=>(int)$customer_id,
                    'customer_id'=>$customer_id,
                    'message_id'=>0,
                    'sender_type'=>2,// if 1 booster sended message, 2 - customer , 3 - admin
                    'status'=>$status,
                ];
            }

            $post_data = array(
                'post_title' => "Chat_Booster_Customer_Message #" . date('Y_m_d H_i_s'),
                'post_content' => $sanitized_content,
                'post_parent' => $chat_id,
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => $this->chat_message_post_type,
            );

            $new_post_id = wp_insert_post($post_data);

            update_post_meta($new_post_id, 'boosters_id', $boosters_id);
            update_post_meta($new_post_id, 'customer_id', $customer_id);
            update_post_meta($new_post_id, 'author_id', $customer_id);
            update_post_meta($new_post_id, 'sender_type', 2); // if 1 booster sended message, 2 - customer , 3 - admin
            update_post_meta($new_post_id, 'status', $status);
            update_post_meta($new_post_id, 'chat_id', $chat_id);

            $this->Notifications_Instance->create("You have a new chat message from the client Chat ID #$chat_id",0,$boosters_id,3,1);
        }

        return [
            'chat_id'=>$chat_id,
            'boosters_id'=>$boosters_id,
            'customer_id'=>$customer_id,
            'message_id'=>$new_post_id,
            'author_id'=>(int)$customer_id,
            'sender_type'=>2,
            'status'=>$status,
        ];
    }

    /**
     * Booster delete message
     *
     * @param int $message_id
     * @param int $booster_id
     * @return bool
     */
    public function booster_delete_message($message_id=0)
    {
        $current_user = wp_get_current_user();
        $autor_id = $current_user->ID;

        $boosters_id = get_post_meta($message_id,'boosters_id',1);
        $customer_id = get_post_meta($message_id,'customer_id',1);

        if ($autor_id == $boosters_id )
        {
            return wp_delete_post($message_id, true);
        }

        return false;
    }


    /**
     * Customer delete message
     *
     * @param int $message_id
     * @param int $customer_id
     * @return bool
     */
    public function customer_delete_message($message_id=0)
    {
        $current_user = wp_get_current_user();
        $autor_id = $current_user->ID;

        $boosters_id = get_post_meta($message_id,'boosters_id',1);
        $customer_id = get_post_meta($message_id,'customer_id',1);

        if ($autor_id == $customer_id )
        {
            return wp_delete_post($message_id, true);
        }

        return false;
    }

    /**
     * Update booster chat message
     *
     * @param int $message_id
     * @param int $booster_id
     * @param string $new_massage
     * @return bool
     */
    public function booster_update_message($message_id=0,$new_massage='')
    {
        $current_user = wp_get_current_user();
        $author_id = $current_user->ID;

        $boosters_id = get_post_meta($message_id,'boosters_id',1);
        $customer_id = get_post_meta($message_id,'customer_id',1);

        if ($author_id == $boosters_id )
        {
            $sanitized_content = wp_kses($new_massage, $this->allowed_tags_message);

            if (trim($sanitized_content)=="")
            {
                return false;
            }

            $post_data = array(
                'ID'           => $message_id,
                'post_content' => $sanitized_content,
            );
            return wp_update_post($post_data);
        }
        return false;
    }

    public function customer_update_message($message_id=0,$new_massage='')
    {
        $current_user = wp_get_current_user();
        $author_id = $current_user->ID;

        $boosters_id = get_post_meta($message_id,'boosters_id',1);
        $customer_id = get_post_meta($message_id,'customer_id',1);

        if ($author_id == $customer_id )
        {
            $sanitized_content = wp_kses($new_massage, $this->allowed_tags_message);

            if (trim($sanitized_content)=="")
            {
                return false;
            }

            $post_data = array(
                'ID'           => $message_id,
                'post_content' => $sanitized_content,
            );
            return wp_update_post($post_data);
        }
        return false;
    }

    public function set_message_status($message_id=0,$status=1)
    {
        $current_user = wp_get_current_user();
        $author_id = $current_user->ID;

        $boosters_id = get_post_meta($message_id,'boosters_id',1);
        $customer_id = get_post_meta($message_id,'customer_id',1);

        $change=false;
        if ( $author_id == $customer_id || $author_id == $boosters_id )
        {
            $change = update_post_meta($message_id, 'status', $status);
        }

        return [
            'message_id'=>$message_id,
            'change'=>$change,
        ];
    }

    /**
     * Set timestamp for activity user chat
     *
     * @return array
     */
    public function chat_user_activity($is_window_active=true)
    {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        if (  $user_id )
        {
            $currentTimestamp = time();
            update_user_meta($user_id, 'chat_activity_timestamp', $currentTimestamp);
            update_user_meta($user_id, 'is_window_active', $is_window_active);
        }

        return [
            'timestamp'=> $currentTimestamp
        ];
    }

    public function get_chat_user_activity($users_ids=[])
    {
        $result=[];

        foreach ($users_ids as $user_id)
        {
            $result[]=$this->time_activity($user_id);
        }

        return $result;
    }

    private function time_activity($user_id=0)
    {
        $currentTimestamp = time();
        $timestamp = (int)get_user_meta($user_id, 'chat_activity_timestamp', true);
        if ($timestamp) {
            $secondsDifference = $currentTimestamp - $timestamp;
            $minutesDifference = $secondsDifference / 60;

            return [
                'user_id' => $user_id,
                'second' => $secondsDifference,
                'minutes' => $minutesDifference,
                'is_window_active' => get_user_meta($user_id, 'is_window_active', true),
            ];
        }
        else
        {
            return [
                'user_id' => $user_id,
                'second' => 0,
                'minutes' => 0,
                'is_window_active' => 'false',
            ];
        }
    }

    //---------------------------------------------------------

    /**
     * Admin get list all chats
     * @return array
     */
    public function get_list_chats_admin()
    {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $result=[];

        if ( in_array('administrator', $current_user->roles) || in_array(admin_panel_role_name, $current_user->roles) )
        {
            $args = array(
                'post_type'     => $this->chat_post_type,
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'status',
                        'value' => 1,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                ),
                'orderby' => 'date',
                'order' => 'DESC',
            );

            $query = new WP_Query($args);
            $posts = $query->posts;

            if (is_array($posts) && count($posts))
            {
                foreach ($posts as $key => $item)
                {
                    $boosters_id = get_post_meta($item->ID,'boosters_id',1);
                    $product_id = get_post_meta($item->ID,'product_id',1);
                    $order_id = get_post_meta($item->ID,'order_id',1);
                    $customer_id = get_post_meta($item->ID,'customer_id',1);
                    $applicant_id = get_post_meta($item->ID,'applicant_id',1);
                    $applicant_status = get_post_meta((int)$applicant_id,'booster_status',1);
                    $allowed_users = get_post_meta($item->ID,'allowed_users',1);

                    $result[]=[
                        'chat_id'=>$item->ID,
                        'boosters_id'=>$boosters_id,
                        'booster_info'=>$this->_get_booster_info($boosters_id),
                        'customer_info'=>$this->_get_customer_info($customer_id),
                        'customer_id'=>$customer_id,
                        'order_id'=>get_post_meta($item->ID,'order_id',1),
                        'product_id'=>$product_id,
                        'product_info'=>$this->_get_product_info($order_id,$product_id),
                        'applicant_id'=> $applicant_id,
                        'applicant_status'=>$applicant_status,
                        'status'=>get_post_meta($item->ID,'status',1),
                        'count_message'=>$this->get_count_message_chat($item->ID),
                        'allowed_users'=>$allowed_users,
                        'chat_users_info'=>$this->_chat_users_info($allowed_users),
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * Admin get list messages in selected chat
     * @param int $chat_id
     * @return array
     */
    public function get_list_chat_messages_admin($chat_id=0)
    {
        $current_user = wp_get_current_user();
        $user_id  = $current_user->ID;
        $result=[];

        if (in_array('administrator', $current_user->roles))
        {
            $args = array(
                'post_type'     => $this->chat_message_post_type,
                'posts_per_page' => -1,
                'post_parent'    => $chat_id,
                'orderby' => 'ID',
                'order'  => 'ASC',
            );

            $query = new WP_Query($args);
            $posts = $query->posts;

            // echo $query->request;

            if (is_array($posts) && count($posts))
            {
                foreach ($posts as $key => $item)
                {
                    $boosters_id = get_post_meta($item->ID,'boosters_id',1);
                    $customer_id = get_post_meta($item->ID,'customer_id',1);
                    $author_id = get_post_meta($item->ID,'author_id',1);
                    $sender_type = get_post_meta($item->ID,'sender_type',1);

                    $this->set_message_status_readed($chat_id,$customer_id);
                    $status = get_post_meta($item->ID,'status',1);

                    $self_msg=0;
                    if ( $user_id==$author_id )
                        $self_msg = 1;

                    $result[]=[
                        'chat_id'=>$chat_id,
                        'author_id'=>(int)$author_id,
                        'sender_type'=>$sender_type,
                        'self_msg'=>$self_msg,
                        'status'=>$status,
                        'booster_info'=>$this->_get_booster_info($boosters_id),
                        'customer_info'=>$this->_get_customer_info($customer_id),
                        'item'=>$item,
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * Admin send messsage to chat
     *
     * @param int $chat_id
     * @param string $message
     * @param int $status
     * @return array
     */
    public function admin_chat_send_message($chat_id=0,$message="", $status=2)
    {
        $current_user = wp_get_current_user();
        $user_id  = $current_user->ID;
        $result=[];

        $boosters_id = get_post_meta($chat_id, 'boosters_id', true);
        $customer_id = get_post_meta($chat_id, 'customer_id', true);

        if (in_array('administrator', $current_user->roles) || in_array(admin_panel_role_name, $current_user->roles) )
        {
            $sanitized_content = wp_kses($message, $this->allowed_tags_message);

            if (trim($sanitized_content)=="")
            {
                return [
                    'chat_id'=>$chat_id,
                    'author_id'=>$user_id,
                    'boosters_id'=>$boosters_id,
                    'customer_id'=>$customer_id,
                    'message_id'=>0,
                    'sender_type'=>1,
                    'status'=>$status,
                ];
            }

            $post_data = array(
                'post_title' => "Chat_Booster_Customer_Message #" . date('Y_m_d H_i_s'),
                'post_content' => $sanitized_content,
                'post_parent' => $chat_id,
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => $this->chat_message_post_type,
            );

            $new_post_id = wp_insert_post($post_data);

            update_post_meta($new_post_id, 'boosters_id', $boosters_id);
            update_post_meta($new_post_id, 'customer_id', $customer_id);
            update_post_meta($new_post_id, 'author_id', $user_id);
            update_post_meta($new_post_id, 'sender_type', 3); // if 1 booster sended message, 2 - customer , 3 - admin
            update_post_meta($new_post_id, 'status', $status);
            update_post_meta($new_post_id, 'chat_id', $chat_id);


            $this->Notifications_Instance->create("You have a new chat message from the client Chat ID #$chat_id",0,$boosters_id,3,1);
            $this->Notifications_Instance->create("You have a new chat message from the client Chat ID #$chat_id",0,$customer_id,3,1);

        }

        return [
            'chat_id'=>$chat_id,
            'boosters_id'=>$boosters_id,
            'customer_id'=>$customer_id,
            'message_id'=>$new_post_id,
            'sender_type'=>1,
            'status'=>$status,
        ];

    }

    /**
     * Admin edit any message in chat
     *
     * @param int $message_id
     * @param string $new_massage
     * @return bool
     */
    public function admin_chat_edit_message($message_id=0,$new_massage='')
    {
        $current_user = wp_get_current_user();
        $user_id  = $current_user->ID;

        if (in_array('administrator', $current_user->roles) || in_array(admin_panel_role_name, $current_user->roles) )
        {
            $sanitized_content = wp_kses($new_massage, $this->allowed_tags_message);

            if (trim($sanitized_content)=="")
            {
                return false;
            }

            $post_data = array(
                'ID'           => $message_id,
                'post_content' => $sanitized_content,
            );
            return wp_update_post($post_data);
        }

        return false;
    }

    /**
     * Admin delete any message in chat
     *
     * @param int $message_id
     * @return bool
     */
    public function admin_delete_message($message_id=0)
    {
        $current_user = wp_get_current_user();
        $autor_id = $current_user->ID;

        $boosters_id = get_post_meta($message_id,'boosters_id',1);
        $customer_id = get_post_meta($message_id,'customer_id',1);

        if (in_array('administrator', $current_user->roles) || in_array(admin_panel_role_name, $current_user->roles) )
        {
            return wp_delete_post($message_id, true);
        }

        return false;
    }

    public function admin_delete_chat($chat_id=0)
    {
        $current_user = wp_get_current_user();
        $user_id  = $current_user->ID;
        $result=[];

        if (in_array('administrator', $current_user->roles) || in_array(admin_panel_role_name, $current_user->roles) ) {
            $args = array(
                'post_type' => $this->chat_message_post_type,
                'posts_per_page' => -1,
                'post_parent' => $chat_id,
                'orderby' => 'ID',
                'order' => 'ASC',
            );

            $query = new WP_Query($args);
            $posts = $query->posts;

            // DELETE ALL MESSAGE FROM CHAT
            if (is_array($posts) && count($posts)) {
                foreach ($posts as $key => $item) {
                    wp_delete_post($item->ID, true);
                }
            }

            return wp_delete_post($chat_id, true);
        }

        return false;
    }

    public function admin_get_users_list()
    {
        $users = get_users(array(
            'role__in' => array('administrator', 'boosters', 'customer'),
            'orderby'  => 'login',
            'order'    => 'ASC'
        ));

        $user_data = array();

        foreach ($users as $user) {
            $first_name = get_user_meta($user->ID, 'first_name', true);
            $last_name = get_user_meta($user->ID, 'last_name', true);
            $user_data[] = array(
                'ID' => $user->ID,
                'user_login' => $user->user_login,
                'user_email' => $user->user_email,
                'user_role' => $user->roles[0],
                'first_name' => $first_name,
                'last_name' => $last_name
            );
        }

        return $user_data;
    }

    public function admin_add_user_to_chat($user_id=0,$chat_id=0)
    {
        $current_user = wp_get_current_user();
        $admin_id  = $current_user->ID;

        if (in_array('administrator', $current_user->roles) || in_array(admin_panel_role_name, $current_user->roles))
        {
           $allowed_users = get_post_meta($chat_id, 'allowed_users', true);

           if ( $allowed_users && is_array($allowed_users) && !in_array($user_id,$allowed_users))
           {
               $allowed_users[]=$user_id;
           }
           else
           {
               $allowed_users=[];
               $allowed_users[]=$user_id;
           }

            $user_info = get_userdata($user_id);
            if ($user_info)
            {
                $user_roles = $user_info->roles;
                $user_role = $user_roles[0];

                if ($user_role=='boosters')
                {
                    update_post_meta($chat_id,'boosters_id',$user_id);
                }
            }

           return update_post_meta($chat_id, 'allowed_users',$allowed_users);
        }
    }

    public function admin_remove_user_from_chat($chat_id=0,$user_id=0)
    {
        $current_user = wp_get_current_user();
        $admin_id  = $current_user->ID;

        if (in_array('administrator', $current_user->roles) || in_array(admin_panel_role_name, $current_user->roles))
        {
            $boosters_id = get_post_meta($chat_id,'boosters_id',1);
            $customer_id = get_post_meta($chat_id,'customer_id',1);

            if (  $customer_id==$user_id )
            {
                return false;
            }
            else
            {
                $allowed_users = get_post_meta($chat_id,'allowed_users',1);

                if ( in_array($user_id,$allowed_users))
                {
                    $del_key = array_search($user_id, $allowed_users);
                    if (isset($allowed_users[$del_key]))
                    {
                        unset($allowed_users[$del_key]);
                    }
                }

                return update_post_meta($chat_id,'allowed_users',$allowed_users);
               // return true;
            }
        }

        return false;
    }
}

?>