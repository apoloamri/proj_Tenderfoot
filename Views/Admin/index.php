<div id="adminPages">
    <?php $this->Partial("admin_navigation") ?>
    <div id="adminContent">
        <div id="adminInnerContent">
            <h2>Recent Activities</h2>
            <div class="adminTable">
                <table>
                    <tr>
                        <th width="75%">Action</th>
                        <th width="25%">Time</th>
                    </tr>
                    <tr v-for="item in logs">
                        <td>{{item.str_admin_user}} {{item.str_action}} <b>{{item.str_code}}</b></td>
                        <td>{{item.dat_insert_time}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#adminInnerContent",
        data: {
            logs: []
        },
        methods: {
            GetLogs: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Get("/api/logs", null,
                function (success) {
                    self.logs = success.Result;
                    Lib.InitialLoading(false);
                });
            }
        },
        created() {
            this.GetLogs();
        }
    });
</script>