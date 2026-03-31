<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<!-- LIST CHAT -->
<div class="list_chats" >
    <ul>

        <li
                :class="{
                    'active': chat_data.selected,
                    'new': chat_data.applicant_status === '1',
                    'in_work': chat_data.applicant_status === '2',
                    'complected': chat_data.applicant_status === '3',
                    'admin_confirmed': chat_data.applicant_status === '4',
                  }"
                v-for="chat_data in chat_list" v-on:click="select_chat(chat_data.chat_id)"
                :data-order_id="chat_data.order_id"
                :data-chat_id="chat_data.chat_id"
        >

            <p class="name_status" v-if="chat_type==1||chat_type==3" :data-status="chat_data.applicant_status" >
                <span class="name_status_title">
                    Status: {{
                        chat_data.applicant_status === '1' ? 'New' :
                        chat_data.applicant_status === '2' ? 'In work' :
                        chat_data.applicant_status === '3' ? 'Complected':
                        chat_data.applicant_status === '4' ? 'Admin Confirmed':''
                    }}
                    {{chat_data.applicant_status}}
                </span>
                <span v-if="chat_type==3" class="delete_chat" v-on:click="admin_delete_chat(chat_data.chat_id)" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24" fill="none">
<path d="M10 12V17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M14 12V17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M4 7H20" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                </span>
            </p>
            <p>{{chat_data.product_info.product_name}} (#{{chat_data.order_id}})</p>
            <ul class="product_params">
                <li v-for="prod_data in chat_data.product_info.meta" > - {{prod_data.display_key}} {{prod_data.value}} </li>
            </ul>

            <ul class="users_chat">

                <li v-for="user_chat in chat_data.chat_users_info" v-if="user_chat.user_id!=0" >
                    <div
                        :class="{
                          'user_status on_line': user_chat.on_status === 1,
                          'user_status afk_line': user_chat.on_status === 2,
                          'user_status off_line': user_chat.on_status === 3,
                        }"
                    >

                    </div>
                    <div class="user_avatar" :title="user_chat.first_name" >
                        {{user_chat.first_name}} {{user_chat.last_name}}
                        <span v-if="chat_type==3" class="delete_user_chat" v-on:click="admin_delete_user_chat(chat_data.chat_id,user_chat.user_id)" >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24" fill="none">
<path d="M10 12V17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M14 12V17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M4 7H20" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                        </span>
                        <img :src="user_chat.custom_profile_image" width="100">
                    </div>
                </li>



            </ul>
            <!--<p class="chat_info" > Count message - {{ chat_data.count_message }}</p>-->
        </li>
    </ul>
</div>
<!-- /LIST CHAT -->
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ] -->