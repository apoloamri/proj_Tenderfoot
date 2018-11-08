<div id="header">
    <img src="/Resources/images/magnifying-glass.png" style="height:20px;margin:-3px 3px;"> 
    <input type="text" placeholder="Search" id="globalSearch" v-model="search" v-on:keyup="Search()" />
    <div id="cartHeader" class="float-right">
        <a href="/cart" class="margin-15">
            <img src="/Resources/images/shopping-cart.png" style="height:20px;margin:-3px 3px;"> 
            Cart ({{count}})
        </a>
    </div><hr>
    <div id="navigation" class="float-left">
        <div onclick="window.location='/';" id="shopPinLogo"></div><hr>
        <a href="/" class="margin-15">Home</a><hr/>
        <a href="/" class="margin-15">Contact Us</a><hr/>
        <a href="/" class="margin-15">FAQ</a><hr/>
        <h4>Categories</h4>
        <div v-for="item in menu">
            <a v-bind:href="'/?tag=' + item.str_tag" class="margin-15">{{item.str_tag}}</a><hr/>
        </div>
    </div>
</div>

<style>
    #shopPinLogo {
        background: url("/Resources/images/shoppin-logo.png");
        background-size: contain;
        background-repeat: no-repeat;
        height: 70px;
        width: 150px;
        margin: 15px;
        margin: auto;
        cursor: pointer;
    }
</style>

<script type="module">
import Lib from "/Resources/js/lib.js";
new Vue({
    el: "#header",
    data: {
        search: Lib.UrlParameter("search"), 
        menu: [],
        count: 0
    },
    methods: {
        GetCart: function () {
            var self = this;
            Lib.Get("/api/cart", null,
            function (success) {
                self.count = success.Count;
            });
        },
        GetTags: function () {
            var self = this;
            Lib.Get("/api/products/tags", null,
            function (success) {
                self.menu = success.Result;
            });
        },
        Search: function (element) {
            var self = this;
            if (event.key === "Enter") {
                window.location = "/?search=" + self.search;
            }
        }
    },
    created() {
        this.GetCart();
        this.GetTags();
    }
});
</script>