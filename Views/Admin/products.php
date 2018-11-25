<div id="adminPages">
    <?php $this->Partial("admin_navigation") ?>
    <div id="adminContent">
        <div id="adminInnerContent">
            <h2>Products</h2>
            <button onclick="window.location='/admin/products/add';">Add product</button>
            <button onclick="window.location='/admin/products/tags';">Edit Tags</button>
            <div class="adminTable">
                <input type="text" v-model="search" v-on:keyup="GetProductsDelay()" placeholder="Search products" />
                <table>
                    <tr>
                        <th width="50%" colspan="2">Product</th>
                        <th width="17%">Inventory</th>
                        <th width="17%">Brand</th>
                        <th width="17%">Price</th>
                    </tr>
                    <tr v-for="item in result">
                        <td width="5%"><div v-bind:style="{ 'background-image': 'url(' + item.str_path + ')' }" class="image size-50"></div></td>
                        <td v-on:click="Redirect(item.id)">({{item.str_code}}) {{item.str_name}}</td>
                        <td>
                            <input type="number" v-on:keyup="PutInventory($event.target.value, item.id)" v-bind:value="item.int_amount" />
                            <label v-bind:id="'check_' + item.id" class="inline-block"></label>
                        </td>
                        <td v-on:click="Redirect(item.id)">{{item.str_brand}}</td>
                        <td v-on:click="Redirect(item.id)">{{item.dbl_price}}</td>
                    </tr>
                </table>
                <div class="spacer-h-15"></div>
                <center>
                    <a href="#" v-on:click="PrevPage()">🡄</a>
                    {{page}} / {{pageCount}}
                    <a href="#" v-on:click="NextPage()">🡆</a>
                </center>
            </div>
        </div>
    </div>
</div>

<style>
    input[type="number"] {
        width: 30px;
    }
    button {
        display: inline-block;
    }
</style>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#adminInnerContent",
        data: {
            search: "",
            page: 1,
            pageCount: 0,
            result: []
        },
        methods: {
            GetProductsDelay: function () {
                var self = this;
                Lib.Delay(function () {
                    self.GetProducts();
                }, 500);
            },
            GetProducts: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Get("/api/products", {
                    "Search": self.search,
                    "Page": self.page,
                    "Count": 10
                },
                function (success) {
                    self.result = success.Result;
                    self.pageCount = success.PageCount;
                    Lib.InitialLoading(false);
                });
            },
            PutInventory: function (value, id) {
                $("#check_" + id)
                    .text("✖")
                    .addClass("red")
                    .removeClass("green");
                if (value != "") {
                    Lib.Delay(function () {
                        Lib.Put("/api/products/inventory", {
                            "Id": id,
                            "Amount": value
                        },
                        function (success) {
                            $("#check_" + id)
                                .text("✔")
                                .addClass("green")
                                .removeClass("red");
                        });
                    }, 500);
                }
            },
            NextPage: function () {
                if (this.page < this.pageCount) {
                    this.page = this.page + 1;
                    this.GetProducts();
                }
            },
            PrevPage: function () {
                if (this.page != 1) {
                    this.page = this.page - 1;
                    this.GetProducts();
                }
            },
            Redirect: function (id) {
                window.location = "/admin/products/edit/" + id;
            }
        },
        created () {
            this.GetProducts();
        }
    });
</script>