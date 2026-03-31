if (typeof Vue === 'undefined') {
    console.error('Vue.js is not loaded. Aborting script.');
} else {
    var app_dashboard_customer_my_orders = document.getElementById("app_dashboard_customer_my_orders");
    var app_dashboard_customer_profile_setting = document.getElementById("app_dashboard_customer_profile_setting");
    var app_customer_chat = document.getElementById("app_customer_chat");

    if ( app_dashboard_customer_my_orders ) {
        var app = new Vue({
            el: '#app_dashboard_customer_my_orders',
            data: {
                order_paged: 1,
                order_paged_total: 1,
                order_list: [],
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE app_dashboard_customer_my_orders mounted success');
                this.get_list();
            },
            watch:{

            },
            methods:
                {
                    get_list() {
                        let _this = this;
                        _this.order_paged = 1;
                        let data = {
                            action: 'gladiator_dashboard_customer_order_list',
                            paged: _this.order_paged,
                        };

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            beforeSend: function() {
                                _this.order_list=[];
                            },
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);

                                if ( !_this.order_list.length ) {
                                    _this.order_list = obj.list_orders;
                                }
                                else
                                {
                                    _this.order_list = _this.order_list.concat(obj.list_orders);
                                }
                                _this.order_paged_total = parseInt(obj.total_pages);
                            }
                        });
                    },
                    load_more_order() {
                        if (this.order_paged < this.order_paged_total) {
                            this.order_paged++;
                            this.get_list();
                        }
                    },
                    shouldAddClass(index) {
                        return (index + 1) % 10 === 0;
                    },
                }
        });
    }

    if (app_dashboard_customer_profile_setting)
    {
        var app = new Vue({
            el: '#app_dashboard_customer_profile_setting',
            data: {
                customer_profile:{
                    chats_user_name:'',
                    avatar:'',
                    first_name:'',
                    last_name:'',
                    email:'',
                    account_discort_tag:'',
                    account_tel:'',
                },
                allowedExtensions : ['jpg', 'jpeg', 'gif', 'png'],
                maxSize:10 * 1024 * 1024, // 10MB,
                formData : new FormData(),
                avatar_res:'',
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE app_dashboard_customer_profile_setting mounted success');
                this.customer_profile = usermeta;
            },
            watch:{

            },
            methods:
            {
                save()
                {
                    let _this = this;
                    _this.formData.append('action', 'gladiator_dashboard_customer_save_profile');

                    for (const key in _this.customer_profile ) {
                        let value = _this.customer_profile[key];
                        _this.formData.append(key,value);
                    }

                    jQuery.ajax({
                        url: ajaxurl.url,
                        type: 'POST',
                        data: _this.formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            let obj = jQuery.parseJSON(response);
                            _this.customer_profile.avatar = _this.avatar_res;
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });

                },
                uploadAvatar(event)
                {
                    const file = event.target.files[0];
                    let fileExtension = file.name.split('.').pop().toLowerCase();
                    let _this = this;

                    if (this.allowedExtensions.includes(fileExtension) && file.size <= this.maxSize) {
                        let reader = new FileReader();

                        _this.avatar_res='';
                        reader.onload = function (e) {
                            _this.avatar_res = e.target.result;
                            _this.customer_profile.avatar = _this.avatar_res;
                        }

                        reader.readAsDataURL(file);
                        _this.formData.append('file', file);

                     }
                    else {
                        alert('Image incorrect type or size 10MB');
                    }
                },
            }
        });
    }

    if (app_customer_chat)
    {
        var app = new Vue({
            el: '#app_customer_chat',
            data: {
                chat_list:[],
                chat_messages:[],
                current_chat_info:{},
                current_message:'',
                msg_edit:false,
                edited_message_id:0,
                current_author_id:0,
                btn:{
                    text:'SEND',
                },
                chat_type:2, // 1 - booster, 2-customer , 3 - admin
                users_status:[],

                ajax_actions:{
                    load_run:false,
                    load_message_run:false,
                },
                last_count:-1,
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE app_customer_chat mounted success');
                this.current_author_id = current_author_id;
                this.get_chat_list();

                jQuery.DashBoardAction.add_vue(this);
            },
            watch:{
                chat_list(old,upd)
                {
                    const urlParams = new URLSearchParams(window.location.search);
                    const chatIdParam = urlParams.get('chat_id');
                    if (chatIdParam)
                    {
                        if (this.chat_list.length)
                        {
                            for(let i=0; i<this.chat_list.length; i++)
                            {
                                let chat = this.chat_list[i];

                                if ( parseInt(chat.chat_id)==parseInt(chatIdParam) )
                                {
                                    this.select_chat(chat.chat_id);
                                }
                            }
                        }
                    }
                    console.log('chat_list',old);
                },
                chat_messages(old,upd)
                {
                    let _this = this;
                    this.$nextTick(() => {
                        if ( old.length != upd.length ) {
                            _this.scroll_to_last_msg();
                        }
                    });
                },
                users_status(old,upd)
                {
                    if (upd.length)
                    {
                        for(let i=0;i<upd.length;i++)
                        {
                            let user_id = upd[i].user_id;
                            let second = upd[i].second;
                            let minutes = upd[i].minutes;
                            let is_window_active = (upd[i].is_window_active=== "false")?false:true;
                            let on_status =1;

                            if (minutes>30) // afk_line
                            {
                                on_status =2;
                            }

                            if (minutes>120) //off_line
                            {
                                on_status =3;
                            }

                            if (!is_window_active) //off_line
                            {
                                on_status =3;
                            }

                            //----------------------------------------------

                            for(let c=0;c<this.chat_list.length;c++)
                            {
                                let chat = this.chat_list[c];
                                let uobj = chat.chat_users_info.find(item => item.user_id === parseInt(user_id));
                                if (typeof uobj !== "undefined" )
                                uobj.on_status=on_status;
                            }
                        }
                    }
                }
            },
            methods:
            {
                get_chat_list()
                {
                    let _this = this;
                    let data =
                        {
                            action: 'gladiator_dashboard_customer_chat_list',
                        };
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            _this.chat_list=obj.chat_list;
                        }
                    });
                },

                select_chat(chat_id=0)
                {
                    this.ajax_actions.load_run=false;

                    this.chat_list.forEach(chat_data => {
                        chat_data.selected = false;
                    });

                    this.chat_list.forEach(chat_data => {
                        if ( chat_data.chat_id==chat_id )
                        {
                            chat_data.selected = true;
                            return;
                        }
                    });

                    this.get_chat_info(chat_id);
                    this.load_message_to_chat(chat_id);
                },

                load_message_to_chat(chat_id=0)
                {
                    let _this = this;
                    let data =
                        {
                            action: 'gladiator_dashboard_customer_chat_load_message',
                            chat_id:chat_id,
                        };

                    /*if (_this.ajax_actions.load_message_run==true)
                    {
                        _this.ajax_actions.load_message_run==false;
                        return;
                    }*/

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                           /* if (!(JSON.stringify(_this.chat_messages) === JSON.stringify(obj.chat_messages)) )
                            {
                                console.log('playSound load msg');
                                playChatSound();
                            }*/
                            _this.ajax_actions.load_run=true;

                            if (
                                obj.chat_messages.length>0  &&
                                _this.last_count!=obj.chat_messages.length &&
                                obj.chat_messages[obj.chat_messages.length-1].sender_type!='2'
                            )
                            {
                                console.log('playSound load msg');
                                playChatSound();
                            }

                            _this.chat_messages = obj.chat_messages;
                            _this.last_count = obj.chat_messages.length;
                        }
                    });
                },

                get_chat_info(chat_id=0)
                {
                    if ( this.chat_list.length)
                    {
                        for(let i=0; i<this.chat_list.length; i++)
                        {
                            let chat = this.chat_list[i];
                            if (chat.chat_id==chat_id)
                            {
                                this.current_chat_info = chat;
                                break;
                            }
                        }
                    }
                },

                send_message()
                {
                    let _this = this;
                    let chat_id = _this.current_chat_info.chat_id;
                    let booster_id = _this.current_chat_info.boosters_id;
                    if (_this.current_message=="")
                    {
                        return;
                    }

                    if (jQuery('#send_msg_chat').prop('disabled')) {
                        return;
                    }

                    if ( _this.msg_edit === false )
                    {
                        let data =
                            {
                                action: 'gladiator_dashboard_customer_chat_send_message',
                                message: _this.current_message,
                                chat_id: chat_id,
                                booster_id: booster_id,
                            };

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            beforeSend: function() {
                                _this.btn.text = 'SEND...';
                                _this.current_message = '';
                                jQuery('#send_msg_chat').attr('disabled',true);
                            },
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                jQuery('#send_msg_chat').attr('disabled',false);
                                _this.btn.text = 'SEND';
                                _this.current_message = '';
                                _this.ajax_actions.load_message_run==false;
                                _this.load_message_to_chat(chat_id);
                            }
                        });
                    }
                    else
                    {
                        let data =
                        {
                            action: 'gladiator_dashboard_customer_chat_edit_message',
                            message: _this.current_message,
                            message_id: _this.edited_message_id,
                        };

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            beforeSend: function() {
                                _this.btn.text = 'SEND...';
                                _this.current_message = '';
                                jQuery('#send_msg_chat').attr('disabled',true);
                            },
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                _this.current_message = '';
                                jQuery('#send_msg_chat').attr('disabled',false);
                                _this.btn.text = 'SEND';


                                _this.msg_edit = false;
                                _this.edited_message_id=0;

                                _this.ajax_actions.load_message_run==false;
                                _this.load_message_to_chat(chat_id);
                            }
                        });
                    }

                    _this.current_chat_info.count_message++;
                },

                set_status_message(message_id=0,status=1)
                {
                    let _this = this;
                    let data =
                    {
                        action: 'gladiator_dashboard_chat_set_message_status',
                        message_id: message_id,
                        status: status,
                    };

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);

                            if ( obj.chat_messages.change === true)
                            {
                                if ( _this.chat_messages.length ) {
                                    for (let i = 0; i < _this.chat_messages.length; i++) {
                                        let msg_item = _this.chat_messages[i].item;
                                        if (msg_item.ID==message_id) {
                                            _this.chat_messages[i].status=''+status;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },

                scroll_to_last_msg()
                {
                    let div = jQuery("#chat_items_container");
                    div.scrollTop(div.prop('scrollHeight'));
                },

                delete_message(message_id=0)
                {
                    if (confirm('Delete this message?'))
                    {
                        let _this = this;
                        let data =
                        {
                            action: 'gladiator_dashboard_customer_chat_delete_message',
                            message_id: message_id,
                        };
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                _this.load_message_to_chat(_this.current_chat_info.chat_id);
                                _this.current_chat_info.count_message--;
                            }
                        });
                    }
                },

                edit_message(event,message_id=0,booster_id=0)
                {
                    let clickedElement = event.target;
                    let $clickedElement = jQuery(clickedElement);

                    this.edited_message_id = message_id;
                    this.current_message = $clickedElement.closest('.chat_msg_container').find('.message_content').text();
                    this.msg_edit = true;
                    this.btn.text = 'SAVE';
                },

                handleScroll(event)
                {
                    let clientHeight = event.target.clientHeight;
                    console.log(clientHeight);
                },

                ctrl_enter(event)
                {
                    if (event.key === "Enter" && event.ctrlKey) {
                      this.send_message();
                    }
                },

                replaceNewlinesWithBr(text) {
                    return text.replace(/\n/g, '<br>');
                },

                set_status_user(timestamp=0)
                {

                    if ( Object.keys(timestamp).length === 0)
                    {
                        return 'off_line';
                    }

                    if (timestamp.is_window_active === "false" )
                    {
                        return 'off_line';
                    }

                    if (timestamp.minutes>30)
                    {
                        return 'afk_line';
                    }

                    if (timestamp.minutes>120)
                    {
                        return 'off_line';
                    }

                    return 'on_line';
                },

                formatDate(dateString) {
                    const options = {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                    };
                    const formattedDate = new Date(dateString).toLocaleDateString('en-GB', options);
                    return formattedDate.replace(/,/g, '').replace(/\//g, '-');
                },
            }
        });
    }
}
