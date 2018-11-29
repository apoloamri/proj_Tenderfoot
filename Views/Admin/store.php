<div id="adminPages">
    <?php $this->Partial("admin_navigation") ?>
    <div id="adminContent">
        <div id="adminInnerContent">
            <div id="add">
                <h2>Store Settings</h2>
                <div class="adminTable">
                    <h3>Index Page</h3>
                    <label>Announcement</label>
                    <textarea v-model="announcement" placeholder="Website announcement header..."></textarea>
                    <label class="red">{{messages["Announcement"]}}</label>
                    <label>Header</label>
                    <input type="file" v-on:change="UploadHeader()" id="header" />
                    <button v-on:click="DeleteHeader()" class="gray inline-block">Remove Images</button>
                    <label class="red">{{messages["HeaderImage"]}}</label>
                    <div 
                        v-if="store.str_header != null && store.str_header != ''" 
                        v-bind:style="{ 'background-image': 'url(' + store.str_header + ')' }"
                        id="header" class="image size-100" >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#adminInnerContent",
        data: {
            announcement: "",
            store: {},
            messages: []
        },
        methods: {
            GetStore: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Get("/api/store", null,
                function (success) { 
                    self.store = success.Store;
                    Lib.InitialLoading(false);
                });
            },
            UploadHeader: function () {
                var self = this;
                Lib.InitialLoading(true);
                var image = $("#header").prop("files")[0];
                Lib.Form("/api/store/header", {
                    "ImageFile": image
                },
                function (success) { 
                    Lib.InitialLoading(false);
                    self.GetStore();
                },
                function (failed) {
                    var response = failed.responseJSON;
                    self.messages = response.Messages;
                    Lib.InitialLoading(false);
                });
                $("#header").val(null);
            },
            DeleteHeader: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Delete("/api/store/header", null,
                function (success) { 
                    Lib.InitialLoading(false);
                    self.GetStore();
                });
            }
        },
        created() {
            this.GetStore();
        }
    });
</script>