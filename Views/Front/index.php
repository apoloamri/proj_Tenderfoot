<?php $this->Partial("menu") ?>
<?php $this->Partial("categories") ?>
<div id="mainContent">
    <h3 v-if="search != ''">Search: '{{search}}'</h3>
    <h3 v-else-if="searchTag != ''">Items tagged: '{{searchTag}}'</h3>
    <div v-else>
        <div v-if="store.str_header != null && store.str_header != ''" id="header">
            <img v-bind:src="store.str_header" alt=""><hr/>
        </div>
        <h1>Trending Items</h1>
    </div>
    <div class="items" v-for="item in result">
        <center>
            <a v-bind:href="'/detail/' + item.str_code">
                <div class="image" v-bind:style="'background-image: url(' + item.str_path + ')'"></div>
                <div class="content">
                    <label class="font-17">{{item.str_name}}</label><br/>
                    <label class="font-15">₱{{item.dbl_price}}</label>
                </div>
            </a>
            <button v-on:click="AddCart(item.str_code);" :disabled="item.int_amount == null || item.int_amount == 0">
                <label v-if="item.int_amount == null || item.int_amount == 0">OUT OF STOCK</label>
                <label v-else>ADD TO CART</label>
            </button>
        </center>
    </div>
    <div v-if="search == '' && searchTag == ''">
        <h3>New Arrivals</h3>
        <div class="items" v-for="item in result">
            <center>
                <a v-bind:href="'/detail/' + item.str_code">
                    <div class="image" v-bind:style="'background-image: url(' + item.str_path + ')'"></div>
                    <div class="content">
                        <label class="font-17">{{item.str_name}}</label><br/>
                        <label class="font-15">₱{{item.dbl_price}}</label>
                    </div>
                </a>
                <button v-on:click="AddCart(item.str_code);" :disabled="item.int_amount == null || item.int_amount == 0">
                    <label v-if="item.int_amount == null || item.int_amount == 0">OUT OF STOCK</label>
                    <label v-else>ADD TO CART</label>
                </button>
            </center>
        </div>
    </div>
</div>
<?php $this->Partial("footer") ?>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    import Common from "/Resources/js/common.js";
    new Vue({
        el: "#mainContent",
        data: { 
            search: Lib.UrlParameter("search"),
            searchTag: Lib.UrlParameter("tag"),
            store: {},
            page: 1,
            result: [],
            pageCount: 0
        },
        methods: {
            GetStore: function () {
                var self = this;
                Lib.Get("/api/store", null,
                function (success) { 
                    self.store = success.Store;
                    Lib.InitialLoading(false);
                });
            },
            GetProducts: function () {
                var self = this;
                Lib.Get("/api/products", {
                    "Search": self.search,
                    "SearchTag": self.searchTag,
                    "Page": self.page,
                    "Count": 10
                },
                function (success) {
                    self.result = success.Result;
                    self.pageCount = success.PageCount;
                });
            },
            AddCart: function (code) {
                Common.AddCart(code);
            }
        },
        created() {
            this.GetStore();
            this.GetProducts();
        }
    });
</script>