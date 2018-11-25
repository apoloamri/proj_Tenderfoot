<div id="adminPages">
    <?php $this->Partial("admin_navigation") ?>
    <div id="adminContent">
        <div id="adminInnerContent">
            <h3><a href="/admin/products">◄ Products</a></h3>
            <h2>Tags</h2>
            <div class="adminTable">
                <table>
                    <tr>
                        <th colspan="2" width="40%">Tag Name</th>
                        <th width="30%">Set Image</th>
                        <th width="10%">Remove Image</th>
                        <th width="10%">Item Count</th>
                        <th width="10%"></th>
                    </tr>
                    <tr v-for="item in result">
                        <td width="10%"></td>
                        <td width="30%">{{item.str_tag}}</td>
                        <td><input type="file" v-on:change="UploadImage(item.str_tag)" id="image" /></td>
                        <td><button>Remove</button></td>
                        <td></td>
                        <td><button v-on:click="DeleteTags(item.str_tag)">Delete</button></td>
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
            search: "",
            result: []
        },
        methods: {
            GetTagsDelay: function () {
                var self = this;
                Lib.Delay(function () {
                    self.GetTags();
                }, 500);
            },
            GetTags: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Get("/api/products/tags", null,
                function (success) {
                    self.result = success.Result;
                    Lib.InitialLoading(false);
                });
            },
            DeleteTags: function (tagName) {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Delete("/api/products/tags", {
                    "TagName": tagName
                },
                function (success) {
                    alert("Tag successfully removed.");
                    Lib.InitialLoading(false);
                });
            }
        },
        created () {
            this.GetTags();
        }
    });
</script>