<?php
    class Gladiator_Dashboard_TelegramBot
    {
       // private $turl='t.me/GladiatorTest_bot';
        private $turl='t.me/GladBoost_BOT';

        //private $token='6944090125:AAHwAQ9_aWgOXewNJiiFKV2B4aDE9msnfqQ';
        private $token='6962880036:AAEZIwKKWtZX7kEpxbRE6F2BjjtSMVcD4X8';
        private $apiUrl='';
        public $option_is_set_hook='gladiator_dashboard_set_tg_hook';
        public $user_meta_chat_id='gdtg_chat_id';

        public function __construct() {
            $this->apiUrl = "https://api.telegram.org/bot$this->token/";
        }

        /**
         * Cheack if webhook is created
         * @return mixed
         */
        public function is_set_hook()
        {
            return get_option($this->option_is_set_hook);
        }

        /**
         * Set url webhook
         */
        public function set_hook()
        {
            $site_url = home_url();
            $hook_url = $site_url.'/?gladiator_tg_hook=1001';

            $url = "https://api.telegram.org/bot$this->token/setWebhook?url=$hook_url";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            $responseArray = json_decode($response, true);

            if ($responseArray['ok'] === true) {
                update_option($this->option_is_set_hook, true);
            } else {
                update_option($this->option_is_set_hook, false);
            }
        }

        /*
         * Hook call telegram API, send data from bot
         */
        public function update_hook()
        {
            $update = file_get_contents('php://input');
            $response =json_decode($update, true);

            $chat_id = (int)$response['message']['chat']['id'];
            $text = trim($response['message']['text']);

            // Check if this new chat

            if ($this->get_user_by_tg_chat_ID($chat_id)===false && $text=="" )
            {
                $msg='To confirm your identity, you need to send your e-mail using the command /check_email example@gmail.com';
                $this->sendMessage($chat_id,$msg);
            }

            $text = preg_replace('/\s+/', ' ', $text);

            $commandParts = explode(' ', $text);
            $command = $commandParts[0]; // command name
            $parameter = $commandParts[1] ?? ''; // params

            $response_txt='';
            switch ($command)
            {
                case '/start':
                    $response_txt = 'Welcome to the GladiatorBoost chat. To start work, you need to verify your E-Mail, run the command /check_email youremail@example.com.';
                break;
                case '/check_email':

                    if (!empty($parameter) && $this->validateEmail($parameter)==false)
                    {
                        $response_txt = "Your E-Mail: $parameter, is not correct.";
                    }
                    else
                    {
                        $user_id = $this->check_user_email($parameter);
                        if ( $user_id===false )
                        {
                            $response_txt = "User with this E-Mail $parameter was not found. Register on the site : ".home_url()." and resend the E-Mail.";
                        }
                        else
                        {
                            update_user_meta($user_id, $this->user_meta_chat_id, $chat_id);
                            $response_txt = 'Thank you, you have successfully passed the verification. You will now be able to receive messages.';
                        }
                    }
                break;
                default:
                    $response_txt = 'Command not found.';
            }

            if ($response_txt) {
                $this->sendMessage($chat_id, $response_txt);
            }

            $f = fopen($_SERVER['DOCUMENT_ROOT'] . '/tel_log.txt', 'w');
            fwrite($f, print_r([
                '$chat_id'=>$chat_id,
                '$response'=>$response,
                '$response_txt'=>$response_txt,
            ], 1));
            fclose($f);

           /* $f = fopen($_SERVER['DOCUMENT_ROOT'] . '/gladiator_panel/'.date('H_i_').'tglog.txt', 'w');
            fwrite($f, print_r(json_decode($update, true), 1));
            fclose($f);*/

            //echo 'OK';
           // return json_decode($update, true);
           //http_response_code(200);
            return;
        }

        private function check_user_email($email='')
        {
            $user = get_user_by('email', $email);

            if ($user) {
                $user_id = $user->ID;
                return $user_id;
            } else {
                return false;
            }
        }

        private function validateEmail($email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return true;
            } else {
                return false;
            }
        }

        public function get_user_by_tg_chat_ID($chat_id=0)
        {
            $users = get_users(array(
                'meta_key' => $this->user_meta_chat_id,
                'meta_value' => $chat_id,
            ));

            if (!empty($users)) {
                $user_id = $users[0]->ID;
                return $user_id;
            } else {
               return false;
            }
        }

        public function sendMessage($chat_id, $message) {
            $data = http_build_query(array(
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode'=>'HTML',
            ));
            $url = $this->apiUrl . "sendMessage?" . $data;
            file_get_contents($url);
        }
    }
?>