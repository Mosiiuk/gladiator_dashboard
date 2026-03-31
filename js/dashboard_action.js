(function ($) {

    $('.js-burger').on('click', ()=>{
        $('#booster_left_menu').toggleClass('active');
    });


    var DashBoardAction = {
        version: '1.0',
        author: 'WEBCAPITAN',
        list_vue_objects:[],
        intervalId:null,
        is_window_active:true,
        users_status:[],
        run_user_actv:false,

        getVersion: function () {
            return this.version;
        },
        getAuthor: function () {
            return this.author;
        },

        run:function(){
            if (this.intervalId === null) {
                this.intervalId = setInterval(this.watcher.bind(this), 3000);
            }
        },

        stop: function () {
            if (this.intervalId !== null) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        },

        add_vue: function(obj)
        {
            if (this.list_vue_objects.indexOf(obj) === -1)
            {
                this.list_vue_objects.push(obj);
            }
            this.run();
        },

        watcher: function()
        {
            if ( this.list_vue_objects.length )
            {
                for(let i=0;i<this.list_vue_objects.length;i++)
                {
                    let vue_obj = this.list_vue_objects[i];
                    if ( Object.keys(vue_obj.current_chat_info).length )
                    {
                        let current_chat_info = vue_obj.current_chat_info;
                        if ( 'chat_id' in  current_chat_info )
                        {
                            vue_obj.load_message_to_chat(current_chat_info.chat_id);
                        }
                    }

                    if ( Object.keys(vue_obj.chat_list).length )
                    {
                        let list_chats = vue_obj.chat_list;
                        let list_chat_users=[];
                        let set_list_chat_users = new Set(list_chat_users);

                        for(let i=0; i<list_chats.length;i++)
                        {
                           if ( list_chats[i].allowed_users.length )
                            {
                                let allowed_users = list_chats[i].allowed_users;
                                for(let ic=0; ic<allowed_users.length;ic++)
                                {
                                    set_list_chat_users.add(allowed_users[ic]);
                                }
                            }

                        }

                        list_chat_users = Array.from(set_list_chat_users);
                        this.get_user_activity(list_chat_users);
                    }

                    vue_obj.users_status = this.users_status;

                }
            }

            this.user_activity();
        },

        user_activity:function(){
           let data =
           {
               action: 'gladiator_dashboard_chat_user_activity',
               is_window_active:this.is_window_active,
           };
           if (!this.is_window_active)
           {
               this.stop();
           }
           jQuery.ajax({
               type: 'POST',
               url: ajaxurl.url,
               data: data,
               success: function (data) {
                   let obj = jQuery.parseJSON(data);
               }
           });
        },

        get_user_activity:function(users_ids=[])
        {
            let _this = this;
            let data =
            {
               action: 'gladiator_dashboard_get_chat_user_activity',
               users_ids:users_ids,
            };

            if (_this.run_user_actv==true)
            {
                _this.run_user_actv=false;
                return;
            }

            jQuery.ajax({
               type: 'POST',
               url: ajaxurl.url,
               data: data,
              // async:false,
               success: function (data) {
                   let obj = jQuery.parseJSON(data);
                   _this.run_user_actv=true;
                   _this.users_status = obj.chat_time_activity;
               }
            });
        },

        window_active:function(){
            if (document.hidden || document.visibilityState !== "visible" || document.hasFocus()) {
                this.is_window_active = false;
                console.log('STOP');
            } else {
                this.is_window_active = true;
                this.run();
                console.log('RUN');
            }
        }
    };
    document.addEventListener("visibilitychange", function () {
        DashBoardAction.window_active();
    });
    window.addEventListener("blur", function () {
        DashBoardAction.window_active();
    });
    $.DashBoardAction = DashBoardAction;
})(jQuery);

(function ($) {
    var DashBoardNotification = {
        version: '1.0',
        author: 'WEBCAPITAN',
        intervalId:null,
        vue_obj:null,
        getVersion: function () {
            return this.version;
        },
        getAuthor: function () {
            return this.author;
        },

        set_vue_obj:function(obj)
        {
            this.vue_obj = obj;
        },

        run:function(){
            if (this.intervalId === null) {
                this.intervalId = setInterval(this.watcher.bind(this), 2500);
            }
        },
        stop: function () {
            if (this.intervalId !== null) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        },

        watcher: function()
        {
            this.vue_obj.get_notification();
        },
    };
    $.DashBoardNotification = DashBoardNotification;
    $(document).on('click', '.booster_dashboard_notifications,.show_notifications', function (e) {
        e.preventDefault();
        $('#block_notifications').show();
    });
})(jQuery);

if (typeof Vue === 'undefined') {
    console.error('Vue.js is not loaded. Aborting script.');
} else {
    var app_notifications_container = document.getElementById("notifications_container");

    if (app_notifications_container) {
        var app_notify = new Vue({
            el: '#notifications_container',
            data: {
                notify_list: [],
                status_count:{
                    new:0,
                    readed:0,
                },
                ajax_actions:{
                    load_run:false,
                },
            },
            beforeCreate() {

            },
            mounted() {
                console.log('VUE notifications_container mounted success');
                localStorage.setItem('is_vis_notify', 'false');
                jQuery.DashBoardNotification.set_vue_obj(this);
                jQuery.DashBoardNotification.run();
            },
            watch: {
            },
            methods:
            {
                get_notification()
                {
                    let _this = this;
                    let data =
                    {
                        action: 'gladiator_dashboard_get_notification',
                    };

                    if ( typeof is_gladiator_dashboard_admin !== "undefined" )
                    {
                        data.action='gladiator_dashboard_admin_get_notification';
                    }

                    if (_this.ajax_actions.load_run==true)
                    {
                        _this.ajax_actions.load_run==false;
                        return;
                    }

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            _this.ajax_actions.load_run=true;

                            if (!(JSON.stringify(_this.notify_list) === JSON.stringify(obj.notification)) )
                            {
                                console.log('playSound');
                                playChatSound();
                            }

                            _this.notify_list = obj.notification;
                            _this.notify_work();
                            jQuery('#count_notifications').html(_this.status_count.new);
                        }
                    });
                },

                delete_notify(notify_id=0)
                {
                  if (confirm('Delete notify?'))
                  {
                      let _this = this;
                      let data =
                      {
                          action: 'gladiator_dashboard_delete_notify',
                          notify_id: notify_id,
                      };

                      jQuery.ajax({
                          type: 'POST',
                          url: ajaxurl.url,
                          data: data,
                          success: function (data) {
                              let obj = jQuery.parseJSON(data);
                              _this.get_notification();
                          }
                      });
                  }
                },

                notify_work()
                {
                    this.status_count.new = this.notify_list.filter(item => item.info.status === "1").length;
                    this.status_count.readed = this.notify_list.filter(item => item.info.status === "2").length;

                    this.visual_notify();
                },

                delete_notify_all()
                {
                    if (confirm('Delete all notify?'))
                    {
                        let _this = this;
                        let data =
                            {
                                action: 'gladiator_dashboard_delete_notify_all',
                            };

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl.url,
                            data: data,
                            success: function (data) {
                                let obj = jQuery.parseJSON(data);
                                _this.get_notification();
                            }
                        });
                    }
                },

                read_notify_all()
                {
                    let _this = this;
                    let data =
                    {
                        action: 'gladiator_dashboard_read_notify_all',
                    };

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl.url,
                        data: data,
                        success: function (data) {
                            let obj = jQuery.parseJSON(data);
                            _this.get_notification();
                        }
                    });
                },

                close_notify()
                {
                    jQuery('#block_notifications').hide();
                },

                visual_notify()
                {
                  let  is_vis_notify = localStorage.getItem('is_vis_notify');

                    if (!is_vis_notify || is_vis_notify === 'false')
                    {
                       // const audio = new Audio('sound.mp3');
                       // audio.play();

                        if ( this.status_count.new>0 )
                        {
                            let isTabActive = true;
                            let old_title =  document.title;
                            let blinkInterval = setInterval(function () {
                                if (isTabActive) {
                                    document.title = 'New message';
                                } else {
                                    document.title = old_title;
                                }
                                isTabActive = !isTabActive;
                            }, 1000);

                            localStorage.setItem('is_vis_notify', 'true');

                            setTimeout(function () {
                                clearInterval(blinkInterval);
                                document.title = old_title;
                            }, 5000);
                        }
                    }
                },

            }
        });
    }

}