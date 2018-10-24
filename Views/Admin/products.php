<div id="adminProducts" class="adminPages">
    <?php $this->Partial("admin_navigation") ?>
    <div id="adminContent">
        <div id="adminInnerContent">
            <h2>Products</h2>
            <button onclick="window.location='/admin/products/add';">Add product</button>
            <div class="adminTable">
                <input type="text" v-model="search" v-on:keyup="GetProductsDelay()" placeholder="Search products" />
                <table>
                    <tr>
                        <th width="10%"><input type="checkbox" /></th>
                        <th width="40%">Product</th>
                        <th width="17%">Inventory</th>
                        <th width="17%">Category</th>
                        <th width="17%">Price</th>
                    </tr>
                    <tr v-for="item in result">
                        <td><input type="checkbox" /></th>
                        <td>({{item.str_code}}) {{item.str_name}}</th>
                        <td>0</th>
                        <td>Test</th>
                        <td>{{item.dbl_price}}</th>
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
                Lib.Get("/api/products", {
                    "Search": self.search,
                    "Page": self.page,
                    "Count": 10
                },
                function (success) {
                    self.result = success.Result;
                    self.pageCount = success.PageCount;
                });
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
            }
        },
        created () {
            this.GetProducts();
        }
    });
</script>