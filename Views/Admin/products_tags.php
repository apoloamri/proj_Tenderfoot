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
                        <th width="10%"></th>
                    </tr>
                    <tbody v-for="item in result">
                        <tr>
                            <td width="10%" v-on:click="Show('sub_' + item.id)"><div v-bind:style="{ 'background-image': 'url(' + item.str_image_path + ')' }" class="image size-50"></div></td>
                            <td width="30%" v-on:click="Show('sub_' + item.id)">{{item.str_tag}}</td>
                            <td><input type="file" v-on:change="UploadImage(item.str_tag, item.id)" v-bind:id="'image_' + item.id" /></td>
                            <td><button v-on:click="DeleteImage(item.str_tag)">Remove</button></td>
                            <td><button v-on:click="DeleteTags(item.str_tag)">Delete</button></td>
                        </tr>
                        <tr v-for="subItem in item.products" 
                            v-bind:class="'gray sub_' + item.id" 
                            v-on:click="Redirect(subItem.id)" 
                            style="display:none">
                            <td></td>
                            <td colspan="4">({{subItem.str_code}}) {{subItem.str_brand}} - {{subItem.str_name}}</td>
                        </tr>
                    </tbody>
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
            UploadImage: function (tagName, id) {
                var self = this;
                Lib.InitialLoading(true);
                var image = $("#image_" + id).prop("files")[0];
                Lib.Form("/api/products/tags/image", {
                    "TagName": tagName,
                    "ImageFile": image
                },
                function (success) {
                    Lib.InitialLoading(false);
                    self.GetTags();
                },
                function (failed) {
                    Lib.InitialLoading(false);
                    var response = failed.responseJSON;
                    alert(response.Messages.ImageFile);
                });
                $("#image_" + id).val(null);
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
                    self.GetTags();
                });
            },
            DeleteImage: function (tagName) {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Put("/api/products/tags/image", {
                    "TagName": tagName
                },
                function (success) {
                    Lib.InitialLoading(false);
                    self.GetTags();
                });
            },
            Show: function (subName) {
                $("." + subName).toggle();
            },
            Redirect: function (id) {
                window.location = '/admin/products/edit/' + id;
            }
        },
        created () {
            this.GetTags();
            $("button").prop("disabled", false);
        }
    });
</script>