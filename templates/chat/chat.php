<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<!-- CHAT -->
<div class="chat_container" >
  <div class="top" >
    <div class="chat_info">
      <span v-if="current_chat_info.product_info" >Chat: {{current_chat_info.product_info.product_name}} (#{{current_chat_info.order_id}})</span>
      <span v-else ><?php echo __('Select chat','gladiator-theme');?></span>
    </div>

    <!-- for booster btn -->
    <div class="chat_buttons" v-if="current_chat_info.product_info && chat_type==1 && current_chat_info.applicant_status==2 " >
      <button class="chat_btn_confirm_order" v-on:click="booster_order_completed(current_chat_info.applicant_id)" ><?php echo __('Confirm Order Completion','gladiator-theme');?> </button>
    </div>
    <!-- /for booster btn -->

    <!-- admin add users to chat -->
    <div class="chat_buttons" v-if="current_chat_info.product_info && chat_type==3" >
      <select id="user_add" v-model="user_add" >
        <option value="" selected><?php echo __('Select user','gladiator-theme');?></option>
        <option v-for="user, index in user_add_list" :value="user.ID"  >{{user.user_login}}</option>
      </select>
      <button class="chat_btn_add_user" v-on:click="add_user_chat" ><?php echo __('Add user to chat','gladiator-theme');?> </button>
    </div>
    <!-- /admin add users to chat -->

  </div>
  <div class="content" id="chat_items_container" @scroll="handleScroll" >

      <!-- Welcom messsage -->
      <div  v-if="current_chat_info.default_msg" class="wel_message_content" v-html="current_chat_info.default_msg" ></div>
      <!-- /Welcom messsage -->

    <!-- START LIST CHAT MESSAGE -->
    <div class="chat_msg_container" v-for="message in chat_messages" >
      <div
        :class="{
                    'chat_message send_message': message.self_msg === 1,
                    'chat_message received_message': message.self_msg === 0,
                    'chat_message admin_message': message.sender_type === '3',
                  }"
      >
        <div class="message_info">
          <!-- if 1 booster sended message, 2 - customer , 3 - admin -->
          <span class="user_logo">
              <img :src="
                        message.sender_type === '1' ? message.booster_info.custom_profile_image :
                        message.sender_type === '2' ? message.customer_info.custom_profile_image :
                        message.sender_type === '3' ? '<?php echo plugins_url().'/gladiator_dashboard/img/admin.png';?>' : ''
                    " width="50" >
          </span>
          <span class="user_login" >{{
          message.sender_type === '1' ? message.booster_info.first_name?message.booster_info.first_name:message.booster_info.user_login:
          message.sender_type === '2' ? message.customer_info.first_name?message.customer_info.first_name:message.customer_info.user_login :
          message.sender_type === '3' ? 'Admin' : ''
              }}
          </span>
          <span class="message_date">{{formatDate(message.item.post_date)}}</span>

            <div v-if="message.author_id==current_author_id && chat_type!=3" class="chat_items_delete" title="Delete message" v-on:click="delete_message(message.item.ID)" >
            <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24" fill="none">
              <path d="M10 12V17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M14 12V17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M4 7H20" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div v-if="message.author_id==current_author_id && chat_type!=3"  class="chat_items_edit" title="Edit message" v-on:click="edit_message($event,message.item.ID)" >
            <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24" fill="none">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M21.1213 2.70705C19.9497 1.53548 18.0503 1.53547 16.8787 2.70705L15.1989 4.38685L7.29289 12.2928C7.16473 12.421 7.07382 12.5816 7.02986 12.7574L6.02986 16.7574C5.94466 17.0982 6.04451 17.4587 6.29289 17.707C6.54127 17.9554 6.90176 18.0553 7.24254 17.9701L11.2425 16.9701C11.4184 16.9261 11.5789 16.8352 11.7071 16.707L19.5556 8.85857L21.2929 7.12126C22.4645 5.94969 22.4645 4.05019 21.2929 2.87862L21.1213 2.70705ZM18.2929 4.12126C18.6834 3.73074 19.3166 3.73074 19.7071 4.12126L19.8787 4.29283C20.2692 4.68336 20.2692 5.31653 19.8787 5.70705L18.8622 6.72357L17.3068 5.10738L18.2929 4.12126ZM15.8923 6.52185L17.4477 8.13804L10.4888 15.097L8.37437 15.6256L8.90296 13.5112L15.8923 6.52185ZM4 7.99994C4 7.44766 4.44772 6.99994 5 6.99994H10C10.5523 6.99994 11 6.55223 11 5.99994C11 5.44766 10.5523 4.99994 10 4.99994H5C3.34315 4.99994 2 6.34309 2 7.99994V18.9999C2 20.6568 3.34315 21.9999 5 21.9999H16C17.6569 21.9999 19 20.6568 19 18.9999V13.9999C19 13.4477 18.5523 12.9999 18 12.9999C17.4477 12.9999 17 13.4477 17 13.9999V18.9999C17 19.5522 16.5523 19.9999 16 19.9999H5C4.44772 19.9999 4 19.5522 4 18.9999V7.99994Z" fill="#fff"/>
            </svg>
          </div>
          <div v-if="chat_type==3" class="chat_items_delete" title="Delete message" v-on:click="delete_message(message.item.ID)" >
            <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24" fill="none">
              <path d="M10 12V17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M14 12V17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M4 7H20" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div v-if="chat_type==3"  class="chat_items_edit" title="Edit message" v-on:click="edit_message($event,message.item.ID)" >
            <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24" fill="none">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M21.1213 2.70705C19.9497 1.53548 18.0503 1.53547 16.8787 2.70705L15.1989 4.38685L7.29289 12.2928C7.16473 12.421 7.07382 12.5816 7.02986 12.7574L6.02986 16.7574C5.94466 17.0982 6.04451 17.4587 6.29289 17.707C6.54127 17.9554 6.90176 18.0553 7.24254 17.9701L11.2425 16.9701C11.4184 16.9261 11.5789 16.8352 11.7071 16.707L19.5556 8.85857L21.2929 7.12126C22.4645 5.94969 22.4645 4.05019 21.2929 2.87862L21.1213 2.70705ZM18.2929 4.12126C18.6834 3.73074 19.3166 3.73074 19.7071 4.12126L19.8787 4.29283C20.2692 4.68336 20.2692 5.31653 19.8787 5.70705L18.8622 6.72357L17.3068 5.10738L18.2929 4.12126ZM15.8923 6.52185L17.4477 8.13804L10.4888 15.097L8.37437 15.6256L8.90296 13.5112L15.8923 6.52185ZM4 7.99994C4 7.44766 4.44772 6.99994 5 6.99994H10C10.5523 6.99994 11 6.55223 11 5.99994C11 5.44766 10.5523 4.99994 10 4.99994H5C3.34315 4.99994 2 6.34309 2 7.99994V18.9999C2 20.6568 3.34315 21.9999 5 21.9999H16C17.6569 21.9999 19 20.6568 19 18.9999V13.9999C19 13.4477 18.5523 12.9999 18 12.9999C17.4477 12.9999 17 13.4477 17 13.9999V18.9999C17 19.5522 16.5523 19.9999 16 19.9999H5C4.44772 19.9999 4 19.5522 4 18.9999V7.99994Z" fill="#fff"/>
            </svg>
          </div>

        </div>
        <div class="message_content" v-html="replaceNewlinesWithBr(message.item.post_content)" >
        </div>
        <div class="message_bottom">
          <div v-if="message.status=='1'" class="message_status sent" title="Message send" :data-status="message.status"></div>
          <div v-if="message.status=='2'" class="message_status delivered" title="Message delivered" :data-status="message.status"></div>
          <div v-if="message.status=='3'" class="message_status read" title="Message has been read" :data-status="message.status" ></div>
        </div>
      </div>
    </div>
    <!-- END LIST CHAT MESSAGE -->
  </div>
  <div class="bottom" >
    <div class="fields_container">
      <div class="chat_field">
        <textarea placeholder="Your text message" v-model="current_message" @keydown="ctrl_enter" ></textarea>
        <button id="send_msg_chat" v-on:click="send_message" :disabled="!current_chat_info || Object.keys(current_chat_info).length === 0">{{btn.text}}</button>
      </div>
      <div class="chat_field">
        <p><small><?php echo __('Ctrl+Enter send message','gladiator-theme');?></small></p>

      </div>
    </div>
  </div>
</div>
<!-- /CHAT -->
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> ] -->