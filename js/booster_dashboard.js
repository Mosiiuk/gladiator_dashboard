if (typeof Vue === 'undefined') {
    console.error('Vue.js is not loaded. Aborting script.');
} else {
    var app_dashboard_booster_find_orders = document.getElementById("app_dashboard_booster_find_orders");
    var app_booster_dashboard = document.getElementById("app_booster_dashboard");
    var app_dashboard_booster_my_orders = document.getElementById("app_dashboard_booster_my_orders");
    var app_dashboard_booster_withdrawal = document.getElementById("app_dashboard_booster_withdrawal");
    var app_dashboard_booster_payment_methods = document.getElementById("app_dashboard_booster_payment_methods");
    var app_dashboard_booster_subscribe_order = document.getElementById("app_dashboard_booster_subscribe_order");
    var app_booster_chat = document.getElementById("app_booster_chat");


    function show_bootom_message(msg='')
    {
        jQuery('.booster_bottom_message').html(msg);
        jQuery('.booster_bottom_message').removeClass('bhide').addClass('bshow');
    }

    function hide_bootom_message()
    {
        jQuery('.booster_bottom_message').html('');
        jQuery('.booster_bottom_message').removeClass('bshow').addClass('bhide');
    }

    if ( app_dashboard_booster_find_orders ) {

        jQuery(document).ready(function($) {
            $('#start_time').datetimepicker({
                format: 'Y-m-d H:i',
                minDate: new Date(),
                onChangeDateTime: function(dp, $input) {
                    app.want_order.start_time = $input.val();
                }
            });

            $('#eta').datetimepicker({
                format: 'Y-m-d H:i',
                minDate: new Date(),
                onChangeDateTime: function(dp, $input) {
                    app.want_order.eta = $input.val();
                }
            });
        });

        var app = new Vue({
            el: '#app_dashboard_booster_find_orders',
            data: {
                order_paged: 1,
                order_paged_total: 1,
                order_list: [],
                filter:{
                    order_id:'',
                    region:'',
                    character_name_server:'',
                    available_hours:'',
                },
                filter_request:{
                    order_id:'',
                    region:'',
                    character_name_server:'',
                    available_hours:'',
                },
                want_order:{
                    product_id:'',
                    order_id:'',
                    start_time:'',
                    eta:'',
                },
                order_info:{
                    product_name:'',
                    order_id:'',
                    meta:[],
                },
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE app_dashboard_booster_find_orders mounted success');
                this.get_list();
            },
            watch:{
               /* 'filter.order_id': function(newVal, oldVal) {
                    this.filter_request.order_id = oldVal;
                },
                'filter.region': function(newVal, oldVal) {
                    this.filter_request.region = oldVal;
                },
                'filter.character_name_server': function(newVal, oldVal) {
                    this.filter_request.character_name_server = oldVal;
                },
                'filter.available_hours': function(newVal, oldVal) {
                    this.filter_request.available_hours = oldVal;
                },*/
            },
            methods:
                {
                    exfilter() {
                        let _this = this;
                        _this.order_paged = 1;
                        _this.filter_request = _this.filter;
                        let data = {
                            action: 'gladiator_dashboard_booster_order_filter',
                            paged: _this.order_paged,
                            filter_request: _this.filter_request,
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

                                _this.sortedItems();
                            }
                        });
                    },
                    get_list() {
                        let _this = this;
                        let data =
                            {
                                action: 'gladiator_dashboard_booster_order_list',
                                paged: this.order_paged,
                            };

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
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

                                _this.sortedItems();
                            }
                        });
                    },
                    show_popap_want_order(event,order_id,product_id)
                    {
                        this.want_order.product_id=product_id;
                        this.want_order.order_id=order_id;

                        for(let i=0;this.order_list.length;i++)
                        {
                            let order = this.order_list[i];
                            if ( order.id==product_id && order.order_id==order_id )
                            {
                                this.order_info.meta = order.meta;
                                this.order_info.product_name = order.product_name;
                                this.order_info.order_id = order.order_id;
                                break;
                            }
                        }

                        jQuery('#booster_want_order_popap').show();
                    },
                    close_want_order()
                    {
                        this.want_order.product_id='';
                        this.want_order.order_id='';
                        jQuery('#booster_want_order_popap').hide();
                    },
                    ex_want_order()
                    {
                        if ( this.want_order.start_time!="" && this.want_order.eta!="" )
                        {
                            if ( !this.isValidDate(this.want_order.start_time) || !this.isValidDate(this.want_order.eta) )
                            {
                                alert('Invalid date format.');
                                return false;
                            }

                            let date1 = new Date(this.want_order.start_time);
                            let date2 = new Date(this.want_order.eta);

                            if (date2 < date1) {
                                let tmp = this.want_order.eta;
                                this.want_order.eta = this.want_order.start_time;
                                this.want_order.start_time = tmp;
                            }

                            let _this = this;
                            let data =
                                {
                                    action: 'gladiator_dashboard_booster_want_order',
                                    product_id: _this.want_order.product_id,
                                    order_id: _this.want_order.order_id,
                                    start_time: _this.want_order.start_time,
                                    eta: _this.want_order.eta,
                                };

                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl.url,
                                data: data,
                                beforeSend: function() {
                                    jQuery('.ex_want_order').attr('disabled',true);
                                },
                                success: function (data) {
                                    let obj = jQuery.parseJSON(data);

                                    jQuery('.ex_want_order').attr('disabled',false);
                                    _this.visual_action(_this.want_order.order_id,_this.want_order.product_id, obj.booster_applicants.ID, true);
                                    _this.close_want_order();
                                }
                            });
                        }
                       // console.log(JSON.stringify(this.want_order));
                    },
                    cancel_want_order(event,order_id,product_id,booster_applicants_id){

                        if(!confirm('Cancel application?'))
                            return false;

                        let _this = this;
                        let data =
                            {
                                action: 'gladiator_dashboard_booster_cancel_want_order',
                                booster_applicants_id: booster_applicants_id,
                            };

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                _this.visual_action(order_id,product_id,booster_applicants_id,false);
                            }
                        });
                    },
                    visual_action(order_id=0,product_id=0,booster_applicants_id=0,action=true)
                    {
                        for(let i=0;this.order_list.length;i++)
                        {
                            let order = this.order_list[i];
                            if ( order.id==product_id && order.order_id==order_id )
                            {
                                this.order_list[i].is_applicants=action;
                                this.order_list[i].booster_applicants_id=booster_applicants_id;
                                break;
                            }
                        }
                    },
                    isValidDate(dateTimeString) {
                        var dateTimeRegex = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/;
                        if (!dateTimeRegex.test(dateTimeString)) {
                            return false;
                        }

                        var parts = dateTimeString.split(/[\s-:]/);
                        var year = parseInt(parts[0], 10);
                        var month = parseInt(parts[1], 10);
                        var day = parseInt(parts[2], 10);
                        var hour = parseInt(parts[3], 10);
                        var minute = parseInt(parts[4], 10);

                        if (
                            isNaN(year) ||
                            isNaN(month) ||
                            isNaN(day) ||
                            isNaN(hour) ||
                            isNaN(minute) ||
                            month < 1 ||
                            month > 12 ||
                            day < 1 ||
                            day > 31 ||
                            hour < 0 ||
                            hour > 23 ||
                            minute < 0 ||
                            minute > 59
                        ) {
                            return false;
                        }
                        return true;
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

    if (app_booster_dashboard)
    {
        var app = new Vue({
            el: '#app_booster_dashboard',
            data: {
                booster:{
                    avatar:'',
                },
                allowedExtensions : ['jpg', 'jpeg', 'gif', 'png'],
                maxSize:10 * 1024 * 1024, // 10MB,
                booster_balance:0,
                withdrawal:{
                    amount:0,
                    payment_method:'',
                    payment_method_list:[],
                }
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE app_booster_dashboard mounted success');
                this.booster.avatar = user_booster.avatar;

                if ( typeof booster_payment_methods !== "undefined" && Object.keys(booster_payment_methods).length>0 )
                {
                    this.withdrawal.payment_method_list=booster_payment_methods;
                    console.log(this.withdrawal.payment_method_list)
                }
                if ( typeof booster_balance !== "undefined" && booster_balance>0 )
                {
                    this.booster_balance=booster_balance;
                    this.withdrawal.amount = this.booster_balance;
                }
            },
            watch:{

            },
            methods:
                {
                    change_avatar()
                    {
                        jQuery('#uploadAvatar').click();
                    },
                    uploadAvatar(event)
                    {
                        const file = event.target.files[0];
                        let fileExtension = file.name.split('.').pop().toLowerCase();
                        let _this = this;

                        if (this.allowedExtensions.includes(fileExtension) && file.size <= this.maxSize) {
                            let reader = new FileReader();

                            let rs='';
                            reader.onload = function (e) {
                                rs = e.target.result;
                            }

                            reader.readAsDataURL(file);

                            let formData = new FormData();

                            formData.append('file', file);
                            formData.append('action', 'gladiator_dashboard_booster_change_avatar');

                            jQuery.ajax({
                                url: ajaxurl.url,
                                type: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    let obj = jQuery.parseJSON(response);
                                    _this.booster.avatar = rs;
                                },
                                error: function(xhr, status, error) {
                                    console.log(xhr.responseText);
                                }
                            });

                        }
                        console.log(file);
                    },
                    show_withdraw()
                    {
                        jQuery('#booster_popap_withdraw').show();
                    },
                    close_view_withdraw()
                    {
                        jQuery('#booster_popap_withdraw').hide();
                    },
                    ex_withdraw(){
                        let _this = this;

                        if ( _this.withdrawal.amount<=0 )
                        {
                            alert('The sum must be greater than zero');
                            return;
                        }

                        if ( _this.withdrawal.amount>_this.booster_balance )
                        {
                            alert('The amount is more than your balance');
                            _this.withdrawal.amount = _this.booster_balance;
                            return;
                        }

                        if ( _this.withdrawal.payment_method=="" )
                        {
                            alert('Choose a payment method');
                            return;
                        }

                        let data =
                            {
                                action: 'gladiator_dashboard_booster_create_withdraw',
                                withdrawal: _this.withdrawal,
                            };
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            async: false,  // Adding synchrony
                            beforeSend: function() {
                                alert('Your request has been sent. Please wait for completion...');
                            },
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                if ( obj.create_withdrawal===false )
                                {
                                    alert('Creation error, please contact your administrator');
                                }
                                else
                                {
                                    alert('Withdrawal request received, please allow up to 72 Hours');
                                    _this.booster_balance = obj.create_withdrawal.new_balance;
                                    jQuery('[data-booster_balance="1"]').html(_this.booster_balance);
                                    _this.close_view_withdraw();
                                }
                            }
                        });
                    },

                }
        });
    }

    if (app_dashboard_booster_my_orders)
    {
        var app = new Vue({
            el: '#app_dashboard_booster_my_orders',
            data: {
                order_paged: 1,
                order_paged_total: 1,
                order_list: [],
                allowedExtensions : ['jpg', 'jpeg', 'gif', 'png'],
                maxSize:10 * 1024 * 1024, // 10MB,
                order_completed:{
                    booster_applicants_id:0,
                }
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE app_dashboard_booster_my_orders mounted success');
                this.get_list();
            },
            watch:{

            },
            methods:
            {
                get_list() {
                    let _this = this;
                    let data =
                        {
                            action: 'gladiator_dashboard_booster_my_order_list',
                            paged: this.order_paged,
                        };

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
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

                booster_order_completed(index,booster_applicants_id)
                {
                    if (!confirm('Change the status of the order to completed? You will definitely need to add a screenshot! '))
                        return false;

                    this.order_completed.booster_applicants_id=booster_applicants_id;
                    this.order_screen_shot();
                },

                exec_booster_order_completed()
                {
                    let _this = this;
                    let data =
                        {
                            action: 'gladiator_dashboard_booster_set_order_completed',
                            booster_applicants_id: _this.order_completed.booster_applicants_id,
                        };

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        beforeSend: function() {
                            jQuery('button.chat_btn_confirm_order').attr('disabled',true);
                            jQuery('button.chat_btn_confirm_order').text('Executed...');
                        },
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            if (obj.set_order_completed===true)
                            {
                                jQuery('button.chat_btn_confirm_order').attr('disabled',false);
                                jQuery('button.chat_btn_confirm_order').text('Confirm Order Completion');

                                _this.after_set_status(_this.order_completed.booster_applicants_id);
                            }
                        }
                    });
                },

                choice_order_screen_shot(event)
                {
                    const file = event.target.files[0];
                    let fileExtension = file.name.split('.').pop().toLowerCase();
                    let _this = this;

                    if (this.allowedExtensions.includes(fileExtension) && file.size <= this.maxSize) {
                        let reader = new FileReader();

                        let rs='';
                        reader.onload = function (e) {
                            rs = e.target.result;
                        }

                        reader.readAsDataURL(file);

                        let formData = new FormData();

                        formData.append('file', file);
                        formData.append('booster_applicants_id', _this.order_completed.booster_applicants_id);
                        formData.append('action', 'gladiator_dashboard_booster_order_screen_shot');

                        jQuery.ajax({
                            url: ajaxurl.url,
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                show_bootom_message('Please wait, the request is in progress');
                            },
                            success: function(response) {
                                let obj = jQuery.parseJSON(response);
                                hide_bootom_message();
                                _this.exec_booster_order_completed();
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.responseText);
                            }
                        });

                    }
                },

                order_screen_shot()
                {
                    jQuery('#order_screen_shot').click();
                },

                after_set_status(booster_applicants_id=0)
                {
                    for(let i=0;this.order_list.length;i++)
                    {
                        let order = this.order_list[i];
                        if ( order.applicants_info.booster_applicants_id==booster_applicants_id )
                        {
                            this.order_list[i].applicants_info.booster_status=3;
                            break;
                        }
                    }
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

    if (app_dashboard_booster_withdrawal)
    {
        var app = new Vue({
            el: '#app_dashboard_booster_withdrawal',
            data: {
                list:[],
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE app_dashboard_booster_withdrawal mounted success');
                this.get_list();
            },
            watch:{

            },
            methods:
            {
                get_list() {
                    let _this = this;
                    let data =
                    {
                        action: 'gladiator_dashboard_booster_withdrawal_list',
                    };

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            if ( !_this.list.length ) {
                                _this.list = obj.lists;
                            }
                            else
                            {
                                _this.list = _this.order_list.concat(obj.lists);
                            }
                           // _this.order_paged_total = parseInt(obj.total_pages);
                        }
                    });
                },

                booster_withdrawal_delete(id)
                {
                    let _this = this;
                    let data =
                        {
                            action: 'gladiator_dashboard_booster_withdrawal_delete',
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
                }
            }
        });
    }

    if (app_dashboard_booster_payment_methods)
    {
        var app = new Vue({
            el: '#app_dashboard_booster_payment_methods',
            data: {
                payment_fields:{
                    booster_paypal:'',
                    booster_wiseemail:'',
                    booster_usdt_trc20:'',
                }
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE app_dashboard_booster_payment_methods mounted success');
                if ( typeof booster_payment_methods !== "undefined" && Object.keys(booster_payment_methods).length>0 )
                {
                    this.payment_fields=booster_payment_methods;
                }
            },
            watch:{

            },
            methods:
            {
                save_payment()
                {
                    let _this = this;
                    let data =
                    {
                        action: 'gladiator_dashboard_booster_save_payment_method',
                        payment_fields: _this.payment_fields,
                    };
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                        }
                    });
                }
            }
        });
    }

    if (app_dashboard_booster_subscribe_order)
    {
        var app = new Vue({
            el: '#app_dashboard_booster_subscribe_order',
            data: {
                subscribe_category:[],
                booster_subscribe_category:[],
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE app_dashboard_booster_subscribe_order mounted success');
                this.booster_subscribe_category = booster_subscribe_category;
                this.subscribe_category = booster_subscribe_category;
            },
            watch:{

            },
            methods:
                {
                    save_subscribe_order()
                    {
                        let _this = this;
                        let data =
                            {
                                action: 'gladiator_dashboard_booster_save_subscribe_order',
                                subscribe_category: _this.subscribe_category,
                            };
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                            }
                        });
                    },
                }
        });
    }

    if (app_booster_chat)
    {
        var app = new Vue({
            el: '#app_booster_chat',
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
                chat_type:1, // 1 - booster, 2-customer , 3 - admin
                users_status:[],
                allowedExtensions : ['jpg', 'jpeg', 'gif', 'png'],
                maxSize:10 * 1024 * 1024, // 10MB,
                order_completed:{
                    booster_applicants_id:0,
                },

                ajax_actions:{
                  load_run:false,
                  load_message_run:false,
                },
                last_count:-1,
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE app_booster_chat mounted success');
                jQuery.DashBoardAction.add_vue(this);
                this.current_author_id = current_author_id;
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

                }
            },
            methods:
            {
                get_chat_list()
                {
                    let _this = this;
                    _this.chat_messages = [];
                    _this.current_chat_info = {};
                    let data =
                    {
                        action: 'gladiator_dashboard_booster_chat_list',
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
                        action: 'gladiator_dashboard_booster_chat_load_message',
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
                            _this.ajax_actions.load_message_run=true;

                            if (
                                obj.chat_messages.length>0  &&
                                _this.last_count!=obj.chat_messages.length &&
                                obj.chat_messages[obj.chat_messages.length-1].sender_type!='1'
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

                booster_order_completed(booster_applicants_id)
                {
                    if (!confirm('Change the status of the order to completed? You will definitely need to add a screenshot! '))
                        return false;

                    this.order_completed.booster_applicants_id=booster_applicants_id;
                    this.order_screen_shot();
                },

                exec_booster_order_completed()
                {
                    let _this = this;
                    let data =
                        {
                            action: 'gladiator_dashboard_booster_set_order_completed',
                            booster_applicants_id: _this.order_completed.booster_applicants_id,
                        };

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        beforeSend: function() {
                            jQuery('button.chat_btn_confirm_order').attr('disabled',true);
                            jQuery('button.chat_btn_confirm_order').text('Executed...');
                        },
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            jQuery('button.chat_btn_confirm_order').attr('disabled',false);
                            jQuery('button.chat_btn_confirm_order').text('Confirm Order Completion');

                            _this.get_chat_list();
                        }
                    });
                },

                choice_order_screen_shot(event)
                {
                    const file = event.target.files[0];
                    let fileExtension = file.name.split('.').pop().toLowerCase();
                    let _this = this;

                    if (this.allowedExtensions.includes(fileExtension) && file.size <= this.maxSize) {
                        let reader = new FileReader();

                        let rs='';
                        reader.onload = function (e) {
                            rs = e.target.result;
                        }

                        reader.readAsDataURL(file);

                        let formData = new FormData();

                        formData.append('file', file);
                        formData.append('booster_applicants_id', _this.order_completed.booster_applicants_id);
                        formData.append('action', 'gladiator_dashboard_booster_order_screen_shot');

                        jQuery.ajax({
                            url: ajaxurl.url,
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                               alert('Your request has been sent.');
                            },
                            success: function(response) {
                                let obj = jQuery.parseJSON(response);
                                _this.exec_booster_order_completed();
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.responseText);
                            }
                        });

                    }
                },

                order_screen_shot()
                {
                    jQuery('#order_screen_shot').click();
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
                                action: 'gladiator_dashboard_booster_chat_send_message',
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
                                _this.ajax_actions.load_message_run=false;
                                _this.load_message_to_chat(chat_id);
                            }
                        });
                    }
                    else
                    {
                        let data =
                        {
                            action: 'gladiator_dashboard_booster_chat_edit_message',
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
                                _this.ajax_actions.load_message_run=false;
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
                            action: 'gladiator_dashboard_booster_chat_delete_message',
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
