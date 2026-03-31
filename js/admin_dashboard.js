if (typeof Vue === 'undefined') {
    console.error('Vue.js is not loaded. Aborting script.');
} else {
    var gladiator_dashboard_admin_dash = document.getElementById("gladiator_dashboard_admin_dash");
    var gladiator_dashboard_admin_choose_app = document.getElementById("gladiator_dashboard_admin_choose_app");
    var gladiator_dashboard_admin_order_completion_app = document.getElementById("gladiator_dashboard_admin_order_completion_app");
    var gladiator_dashboard_admin_withdrawal_requests_app = document.getElementById("gladiator_dashboard_admin_withdrawal_requests_app");
    var app_admin_chat = document.getElementById("app_admin_chat");

    if ( gladiator_dashboard_admin_dash ) {
        var app = new Vue({
            el: '#gladiator_dashboard_admin_dash',
            data: {
                order_paged: 1,
                order_paged_total: 1,
                order_list: [],

                find_chat_order_id:'',
                orderId:0,
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE mounted success');
                this.get_list();
                jQuery('#gladiator_dashboard_admin_dash').show();
            },
            methods:
                {
                    get_list() {
                        let _this = this;
                        let data =
                            {
                                action: 'gladiator_dashboard_get_order_list',
                                paged: this.order_paged,
                                orderId: this.orderId,
                            };

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                let list_orders = obj.gets_orders.list_orders;

                                if ( !_this.order_list.length ) {
                                    _this.order_list = list_orders;
                                }
                                else
                                {
                                    _this.order_list = _this.order_list.concat(list_orders);
                                }
                                _this.order_paged_total = parseInt(obj.gets_orders.total_pages);

                                _this.sortedItems();
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
                    set_order_price(event,index=0)
                    {
                        let orderId = event.target.getAttribute('data-order_id');
                        let product_id = event.target.getAttribute('data-product_id');
                        let _price = jQuery('input[data-order_id="'+orderId+'"][data-product_id="'+product_id+'"]').val().replace(',','.');
                        let price = parseFloat(_price);

                        let _this = this;

                        let data =
                        {
                            action: 'gladiator_dashboard_set_order_price',
                            orderId: orderId,
                            product_id: product_id,
                            price: price,
                        };

                        if ( price<=0 )
                        {
                            jQuery('input[data-order_id="'+orderId+'"]').val(0);
                            return;
                        }

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                _this.order_list[index].boosters_products_prices = price;
                                _this.sortedItems();
                            }
                        });

                        console.log('orderId',orderId);
                        console.log('product_id',product_id);
                        console.log('price',price);
                    },
                    clear_order_price(event,index=0)
                    {
                        let orderId = event.target.getAttribute('data-order_id');
                        let product_id = event.target.getAttribute('data-product_id');

                        let _this = this;
                        let data =
                        {
                            action: 'gladiator_dashboard_clear_order_price',
                            orderId: orderId,
                            product_id: product_id,
                        };

                        let result = confirm("Cancel price and remove from boosterss panel?");
                        if (!result) {
                            return false;
                        }

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                _this.order_list[index].boosters_products_prices = 0;
                                _this.sortedItems();
                            }
                        });
                    },

                    find_chat()
                    {
                        this.order_list=[];
                        this.orderId = parseInt(this.find_chat_order_id.trim());
                        this.get_list();
                    },

                    reset_find_chat()
                    {
                        this.order_list=[];
                        this.find_chat_order_id='';
                        this.orderId=0;
                        this.get_list();
                    },

                    sortedItems() {
                        this.order_list.sort((a, b) => {
                            if (a.order_id === b.order_id) {
                                // product_name
                                return a.product_name.localeCompare(b.product_name);
                            }
                            // order_id DESC
                            return b.order_id - a.order_id;
                        });

                        // boosters_products_prices > 0 down
                        this.order_list = [
                            ...this.order_list.filter(item => item.boosters_products_prices <= 0),
                            ...this.order_list.filter(item => item.boosters_products_prices > 0)
                        ];
                    }
                }
        });
    }

    if ( gladiator_dashboard_admin_choose_app )
    {
        var app = new Vue({
            el: '#gladiator_dashboard_admin_choose_app',
            data: {
                order_paged: 1,
                order_paged_total: 1,
                order_list: [],
                boosters_list:[],
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE mounted success');
                this.get_list();
                jQuery('#gladiator_dashboard_admin_choose_app').show();
            },
            methods:
            {
                get_list() {
                    let _this = this;
                    let data =
                        {
                            action: 'gladiator_dashboard_get_order_list_choice_app',
                            paged: this.order_paged,
                        };

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            let list_orders = obj.gets_orders.list_orders;

                            if ( !_this.order_list.length ) {
                                _this.order_list = list_orders;
                            }
                            else
                            {
                                _this.order_list = _this.order_list.concat(list_orders);
                            }
                            _this.order_paged_total = parseInt(obj.gets_orders.total_pages);
                            _this.order_paged_total = parseInt(obj.gets_orders.total_pages);

                           // _this.order_list.sort(_this.customSort);
                        }
                    });
                },
                load_more_order() {
                    if (this.order_paged < this.order_paged_total) {
                        this.order_paged++;
                        this.get_order_list();
                    }
                },
                view_applicants(order_id=0,product_id=0)
                {
                    this.get_boosters_list(order_id,product_id);
                },
                chooise_applicants(boosters_id=0,booster_applicants_id=0,order_id=0,product_id=0)
                {
                    let _this = this;
                    let data =
                        {
                            action: 'gladiator_dashboard_set_order_to_booster',
                            boosters_id: boosters_id,
                            booster_applicants_id: booster_applicants_id,
                            order_id: order_id,
                            product_id: product_id,
                        };

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        async: false,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);

                            if (obj.status===true)
                            {
                                for( let i=0; i<_this.order_list.length;i++)
                                {
                                    let ord = _this.order_list[i];
                                    if ( ord.order_id==order_id && ord.id==product_id )
                                    {
                                        _this.order_list[i].applicants_info.booster_status=2;
                                        _this.close_view_applicants();
                                    }
                                }
                            }
                        }
                    });
                },
                close_view_applicants()
                {
                    jQuery('#booster_popap').hide();
                },
                get_boosters_list(order_id=0,product_id=0)
                {
                    let _this = this;
                    let data =
                    {
                        action: 'gladiator_dashboard_boosters_list',
                        order_id: order_id,
                        product_id: product_id,
                    };

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            _this.boosters_list = obj.booster_list;
                            jQuery('#booster_popap').show();
                        }
                    });
                },
                shouldAddClass(index) {
                    return (index + 1) % 10 === 0;
                },
            }
        });
    }

    if (gladiator_dashboard_admin_order_completion_app)
    {
        var app = new Vue({
            el: '#gladiator_dashboard_admin_order_completion_app',
            data: {
                lists: [],
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE mounted success');
                this.get_list();
                jQuery('#gladiator_dashboard_admin_choose_app').show();
            },
            methods:
            {
                get_list() {
                    let _this = this;
                    let data =
                        {
                            action: 'gladiator_dashboard_admin_order_completion_app',
                        };

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            _this.lists = obj.lists;
                        }
                    });
                },
                confirm_completion(completion_id=0,order_id=0, product_id=0)
                {
                    if (!confirm('Confirm Completion?'))
                        return false;

                    let _this = this;
                    let data =
                        {
                            action: 'gladiator_dashboard_admin_confirm_order_completed',
                            completion_id:completion_id,
                            order_id:order_id,
                            product_id:product_id,
                        };

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            if (obj.set_order_completed===true)
                            {
                               //alert(obj.set_order_completed);
                               //alert('Done order completed'+' ( Operation info : Booster ID: '+obj.boosters_id+'; add_to_balance:'+obj.add_to_balance+';   )');
                               alert(`
                                   Done order completed.  
                                   Operation info : 
                                   Booster ID:${obj.boosters_id}; 
                                   current_balance:${obj.current_balance}; 
                                   sum_to_boosters:${obj.sum_to_boosters}; 
                                   update_balance:${obj.update_balance}; 
                               `);
                            }
                        }
                    });
                },
                shouldAddClass(index) {
                    return (index + 1) % 10 === 0;
                },
            }
        });
    }

    if ( gladiator_dashboard_admin_withdrawal_requests_app )
    {
        var app = new Vue({
            el: '#gladiator_dashboard_admin_withdrawal_requests_app',
            data: {
                paged: 1,
                paged_total: 1,
                lists: [],
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE mounted success');
                this.get_list();
                jQuery('#gladiator_dashboard_admin_choose_app').show();
            },
            methods:
                {
                    get_list() {
                        let _this = this;
                        let data =
                            {
                                action: 'gladiator_dashboard_get_withdrawal_app',
                                paged: this.paged,
                            };

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                _this.lists = obj.lists;
                                _this.paged_total = parseInt(obj.total_pages);
                            }
                        });
                    },
                    confirm_paid(id=0)
                    {
                        let _this = this;
                        let data =
                            {
                                action: 'gladiator_dashboard_admin_confirm_paid',
                                withdrawal_id: id,
                            };

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);

                                for(let i=0;i<_this.lists.length;i++)
                                {
                                    let l = _this.lists[i];
                                    if (l.ID==id)
                                    {
                                        _this.lists[i].status=2;
                                        break;
                                    }
                                }
                            }
                        });
                    },
                    delete_paid(id)
                    {
                        if (!confirm('Delete withdrawal requests?'))
                            return false;

                        let _this = this;
                        let data =
                            {
                                action: 'gladiator_dashboard_admin_delete_withdrawal',
                                withdrawal_id:id,
                            };

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                _this.get_list();
                            }
                        });
                    },
                    load_more_withdrawal() {
                        if (this.paged < this.paged_total) {
                            this.paged++;
                            this.get_list();
                        }
                    },
                    shouldAddClass(index) {
                        return (index + 1) % 10 === 0;
                    },
                }
        });
    }

    if (app_admin_chat)
    {
        var app = new Vue({
            el: '#app_admin_chat',
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
                chat_type:3, // 1 - booster, 2-customer , 3 - admin
                users_status:[],
                find_chat_order_id:'',
                user_add:0,
                user_add_list:[],
                last_count:-1,
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE app_admin_chat mounted success');
                this.current_author_id = current_author_id;
                jQuery.DashBoardAction.add_vue(this);
                this.get_users_list();
                this.get_chat_list();
            },
            watch:{
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
                    // console.log('old',old);
                    // console.log('upd',upd);

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
                },
            },
            methods:
            {
                get_users_list()
                {
                    let _this = this;
                    let data =
                    {
                        action: 'gladiator_dashboard_admin_get_users_list',
                    };
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            _this.user_add_list=obj.user_add_list;
                        }
                    });
                },

                get_chat_list()
                {
                    let _this = this;
                    _this.chat_messages = [];
                    _this.current_chat_info = {};
                    let data =
                        {
                            action: 'gladiator_dashboard_admin_chat_list',
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
                        action: 'gladiator_dashboard_admin_chat_load_message',
                        chat_id:chat_id,
                    };
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);

                            if (
                                obj.chat_messages.length>0  &&
                                _this.last_count!=obj.chat_messages.length &&
                                obj.chat_messages[obj.chat_messages.length-1].sender_type!='3'
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
                    let customer_id = _this.current_chat_info.customer_id;
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
                                action: 'gladiator_dashboard_admin_chat_send_message',
                                message: _this.current_message,
                                chat_id: chat_id,
                                customer_id: customer_id,
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

                                _this.load_message_to_chat(chat_id);
                            }
                        });
                    }
                    else
                    {
                        let data =
                            {
                                action: 'gladiator_dashboard_admin_chat_edit_message',
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
                                _this.load_message_to_chat(chat_id);
                                _this.msg_edit = false;
                                _this.edited_message_id=0;
                                _this.btn.text = 'SEND';
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
                                action: 'gladiator_dashboard_admin_chat_delete_message',
                                message_id: message_id,
                            };
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                _this.current_chat_info.count_message--;
                                _this.load_message_to_chat(_this.current_chat_info.chat_id);
                            }
                        });
                    }
                },

                admin_delete_chat(chat_id=0)
                {
                    if (confirm('Delete this chat?'))
                    {
                        let _this = this;
                        let data =
                        {
                            action: 'gladiator_dashboard_admin_delete_chat',
                            chat_id: chat_id,
                        };
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                _this.get_chat_list();
                            }
                        });
                    }
                },

                edit_message(event,message_id=0)
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
                    // console.log(clientHeight);
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

                set_status_user(timestamp={})
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

                find_chat()
                {
                    let orderId = parseInt(this.find_chat_order_id);
                    if (!orderId)
                    {
                        jQuery('li[data-order_id]').show();
                    }
                    let foundItem = this.chat_list.find(item => parseInt(item.order_id) === orderId);
                    if (foundItem) {
                        jQuery('li[data-order_id]').hide();
                        jQuery(`li[data-order_id="${orderId}"]`).show();
                    }
                },

                find_handleKeyUp()
                {
                  //  this.find_chat();
                },

                reset_find_chat()
                {
                    this.find_chat_order_id='';
                    jQuery('li[data-order_id]').show();
                },

                add_user_chat()
                {
                    let _this = this;
                    let data =
                    {
                        action: 'gladiator_dashboard_admin_add_user_to_chat',
                        user_add: _this.user_add,
                        chat_id: _this.current_chat_info.chat_id,
                    };
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            _this.get_chat_list();
                        }
                    });
                },

                admin_delete_user_chat(chat_id=0,user_id=0)
                {
                    if (confirm('Delete user from chat?'))
                    {
                        let _this = this;
                        let data =
                        {
                            action: 'gladiator_dashboard_admin_remove_user_from_chat',
                            user_id: user_id,
                            chat_id: chat_id,
                        };
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                if ( obj.result===false)
                                {
                                    alert('The user is the main participant in the chat. The user cannot be removed from the chat.');
                                }
                                else {
                                    _this.get_chat_list();
                                }
                            }
                        });
                    }
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


jQuery(document).ready(function() {
    jQuery('#user_add').niceSelect('destroy');
});