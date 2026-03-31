<?php
    class gladiator_dashboard_notification
    {
        private $post_type='gdb_notify_msg'; // The varchar(20) maximum
        private $allowed_tags_message=[
            'a' => [
                'href' => [],
            ]
        ];

        private $field_list=[
            'sender_id',
            'recipient_id',
            'notify_type',
            'status',
        ];
        public $Gladiator_Dashboard_TelegramBot_Instance;

        public function __construct()
        {
            $this->Gladiator_Dashboard_TelegramBot_Instance = new Gladiator_Dashboard_TelegramBot();
        }


        /**
         * Create new notification
         * $message : text messsage
         * $sender_id : 0 - system/admin, or >0 id user sender
         * $recipient_id : user id
         * $notify_type :
         *  1 - get new order,
         *  2 - after set admin price -> booster notify if products in subcr category,
         *  3 - chat if have new message
         * $status : 1 - new, 2 - readed
         *
         * @param string $message
         * @param int $sender_id
         * @param int $recipient_id
         * @param int $notify_type
         * @param int $status
         * @return array|bool
         */
        public function create($message='',$sender_id=0,$recipient_id=0,$notify_type=0,$status=1)
        {
            //$sanitized_content = wp_kses($message, $this->allowed_tags_message);
            $sanitized_content = $message;

            if (trim($sanitized_content)=="")
            {
                return false;
            }

            $post_data = array(
                'post_title' => "gdb_notify_msg " . date('Y_m_d H_i_s'),
                'post_content' =>  $sanitized_content,
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => $this->post_type,
            );

            $new_post_id = wp_insert_post($post_data);

            update_post_meta($new_post_id, 'sender_id', $sender_id); // 0 - system/admin, or >0 id user sender
            update_post_meta($new_post_id, 'recipient_id', $recipient_id); // user id
            /**
             *  1 - get new order
             *  2 - after set admin price -> booster notify if products in subcr category
             *  3 - chat if have new message
             */
            update_post_meta($new_post_id, 'notify_type', $notify_type);

            /**
             *  1 - new
             *  2 - readed
             */
            update_post_meta($new_post_id, 'status', $status);

            //--------- TELEGRAM BOT NOTIFICATIONS ----

            switch ($notify_type)
            {
                case 1:
                    $admin_users = get_users(array(
                        'role' => 'administrator'
                    ));

                    if (!empty($admin_users)) {
                        foreach ($admin_users as $user)
                        {
                            $chat_id = get_user_meta($user->ID, $this->Gladiator_Dashboard_TelegramBot_Instance->user_meta_chat_id, true);
                            if ($chat_id)
                            {
                                $this->Gladiator_Dashboard_TelegramBot_Instance->sendMessage($chat_id,$sanitized_content);
                            }
                        }
                    }
                break;
                case 2:
                    $chat_id = get_user_meta($recipient_id, $this->Gladiator_Dashboard_TelegramBot_Instance->user_meta_chat_id, true);
                    if ($chat_id)
                    {
                        $this->Gladiator_Dashboard_TelegramBot_Instance->sendMessage($chat_id,$sanitized_content);
                    }
                break;
                case 3:
                    $chat_id = get_user_meta($recipient_id, $this->Gladiator_Dashboard_TelegramBot_Instance->user_meta_chat_id, true);
                    if ($chat_id)
                    {
                        $this->Gladiator_Dashboard_TelegramBot_Instance->sendMessage($chat_id,$sanitized_content);
                    }
                break;
            }
            //--------- /TELEGRAM BOT NOTIFICATIONS ---

            return [
                'notify_id'=>$new_post_id,
                'sender_id'=>$sender_id,
                'recipient_id'=>$recipient_id,
                'notify_type'=>$notify_type,
                'status'=>$status,
            ];
        }

        /*
         * Set notify status
         * $status : 1 - new, 2 - readed
         */
        public function set_status($notify_id=0,$status=0)
        {
            return update_post_meta($notify_id, 'status', $status);
        }

        /**
         * Delete notification
         *
         * @param int $notify_id
         * @return mixed
         */
        public function delete_notify($notify_id=0)
        {
            $current_user = wp_get_current_user();
            $recipient_id  = $current_user->ID;

            if ( $recipient_id )
            {
                $recipient_id_notify = get_post_meta($notify_id,'recipient_id',1);
                if ( $recipient_id == $recipient_id_notify ) {
                    return wp_delete_post($notify_id, true);
                }

                if (in_array('administrator', $current_user->roles) )
                {
                    return wp_delete_post($notify_id, true);
                }
            }

            return false;
        }

        /**
         * AJAX request get notification for current user
         *
         * @return array
         */
        public function get_notification($inp_notify_type=0)
        {
            $current_user = wp_get_current_user();
            $recipient_id  = $current_user->ID;
            $result=[];
            if ($recipient_id)
            {
                $args = array(
                    'post_type'     => $this->post_type,
                    'posts_per_page' => -1,
                    'orderby' => 'ID',
                    'order'  => 'DESC',
                );

                if ( $inp_notify_type==1) // for admin notification
                {
                    $args['meta_query']=[
                        array(
                            'key' => 'notify_type',
                            'value' => $inp_notify_type,
                            'compare' => '=',
                            'type' => 'NUMERIC',
                        ),
                    ];
                }
                else
                {
                    $args['meta_query']=[
                        'relation'=>'AND',
                        array(
                            'key' => 'recipient_id',
                            'value' => $recipient_id,
                            'compare' => '=',
                            'type' => 'NUMERIC',
                        ),
                        array(
                            'key' => 'notify_type',
                            'value' => [2,3],
                            'compare' => 'IN',
                            'type' => 'NUMERIC',
                        ),
                    ];
                }

               /* switch ($inp_notify_type)
                {
                    case 3:
                        $args['meta_query']=[
                            'relation'=>'AND',
                            array(
                                'key' => 'recipient_id',
                                'value' => $recipient_id,
                                'compare' => '=',
                                'type' => 'NUMERIC',
                            ),
                            array(
                                'key' => 'notify_type',
                                'value' => $inp_notify_type,
                                'compare' => '=',
                                'type' => 'NUMERIC',
                            ),
                        ];
                    break;
                    case 1:
                        $args['meta_query']=[
                            array(
                                'key' => 'notify_type',
                                'value' => $inp_notify_type,
                                'compare' => '=',
                                'type' => 'NUMERIC',
                            ),
                        ];
                    break;
                }*/

                $query = new WP_Query($args);
                $posts = $query->posts;

                if (is_array($posts) && count($posts)) {
                    foreach ($posts as $key => $item) {
                        $field_data = $this->get_data_notify($item->ID);
                        $result[]=[
                            'notify_id'=>$item->ID,
                            'item'=>$item,
                            'info'=>$field_data,
                        ];
                    }
                }
            }

            return $result;
        }

        private function get_data_notify($notify_id=0)
        {
           $list_data=[];
           foreach ($this->field_list as $field_name)
           {
               $list_data[$field_name] = get_post_meta($notify_id,$field_name,1);
           }
           return $list_data;
        }

        public function read_notify_all()
        {
            $current_user = wp_get_current_user();
            $recipient_id  = $current_user->ID;
            if ($recipient_id  )
            {
                if (in_array('administrator', $current_user->roles))
                {
                    $args = array(
                        'post_type' => $this->post_type,
                        'posts_per_page' => -1,
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'recipient_id',
                                'value' => 0,
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
                        'orderby' => 'ID',
                        'order' => 'DESC',
                    );
                }
                else {
                    $args = array(
                        'post_type' => $this->post_type,
                        'posts_per_page' => -1,
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'recipient_id',
                                'value' => $recipient_id,
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
                        'orderby' => 'ID',
                        'order' => 'DESC',
                    );
                }

                $query = new WP_Query($args);
                $posts = $query->posts;

                if (is_array($posts) && count($posts)) {
                    foreach ($posts as $key => $item) {
                        $this->set_status($item->ID,2);
                    }
                }
            }
            return true;
        }

        public function delete_notify_all()
        {
            $current_user = wp_get_current_user();
            $recipient_id  = $current_user->ID;
            if ($recipient_id  )
            {
                if (in_array('administrator', $current_user->roles))
                {
                    $args = array(
                        'post_type' => $this->post_type,
                        'posts_per_page' => -1,
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'recipient_id',
                                'value' => 0,
                                'compare' => '=',
                                'type' => 'NUMERIC',
                            ),
                        ),
                        'orderby' => 'ID',
                        'order' => 'DESC',
                    );
                }
                else {
                    $args = array(
                        'post_type' => $this->post_type,
                        'posts_per_page' => -1,
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'recipient_id',
                                'value' => $recipient_id,
                                'compare' => '=',
                                'type' => 'NUMERIC',
                            ),
                        ),
                        'orderby' => 'ID',
                        'order' => 'DESC',
                    );
                }

                $query = new WP_Query($args);
                $posts = $query->posts;

                if (is_array($posts) && count($posts)) {
                    foreach ($posts as $key => $item) {
                       // $this->set_status($item->ID,2);
                        wp_delete_post($item->ID, true);
                    }
                }
            }
            return true;
        }
    }
?>