<!-- [ <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?> -->
<!-- NOTIFICATION -->

<div id="block_notifications" >
    <div id="notifications_container" >
        <div id="close_notifications" v-on:click="close_notify" >Close</div>
        <div id="notifications_control" >
            <button class="button" v-on:click="read_notify_all " >Read all</button>
            <button class="button alt" v-on:click="delete_notify_all " >Delete all</button>
        </div>
        <div id="list_notifications" >
            <!-- item notification -->
            <div v-for="notify_item in notify_list"
                 :class="{
                          'item_notification new': notify_item.info.status === '1',
                          'item_notification readed': notify_item.info.status === '2',
                        }"
            >
                <div class="notify_controll">
                    <div class="notify_delete" v-on:click="delete_notify(notify_item.notify_id)" >&#x2715;</div>
                </div>
                <div class="notify_date">{{notify_item.item.post_date}}</div>
                <div class="notify_message">{{notify_item.item.post_content}}</div>
            </div>
            <!-- /item notification -->
        </div>
    </div>
</div>

<!-- /NOTIFICATION -->
<!--  <?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__); ?>] -->