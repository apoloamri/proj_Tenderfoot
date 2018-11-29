<div id="menu">
    <div id="menuUpper">
        <div onclick="window.location='/';" id="menuLogo"></div>
        <div id="menuCartDiv">
            <a href="/"><img src="/Resources/images/home.png" class="menuImages" /></a>
            <a href="/contact"><img src="/Resources/images/contact.png" class="menuImages" /></a>
            <a href="/faq"><img src="/Resources/images/faq.png" class="menuImages" /></a>
            <a href="/cart">
                <img src="/Resources/images/cart.png" class="menuImages" /> 
                {{count}} item/s (₱{{total}})
            </a>
        </div>
    </div>
    <div id="menuLower">
        <div id="menuSearchDiv">
        <img src="/Resources/images/search.png" class="menuImages hideOnMobile" v-on:click="Search()" /> 
            <input type="text" placeholder="Search products in ShopPin..." id="menuSearch" v-model="search" v-on:keyup="Search()" />
            <label>
                Top searches: 
                <a href="/?search=lenovo">Lenovo</a>
                <a href="/?search=lenovo">Lenovo</a>
                <a href="/?search=lenovo">Lenovo</a>
                <a href="/?search=lenovo">Lenovo</a>
                <a href="/?search=lenovo">Lenovo</a>
            </label>
        </div>
    </div>
</div>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#menu",
        data: {
            search: Lib.UrlParameter("search"), 
            menu: [],
            total: 0,
            count: 0
        },
        methods: {
            GetCart: function () {
                var self = this;
                Lib.Get("/api/cart", null,
                function (success) {
                    self.total = success.Total;
                    self.count = success.Count;
                });
            },
            Search: function () {
                var self = this;
                if (event.key === "Enter") {
                    window.location = "/?search=" + self.search;
                }
            }
        },
        created() {
            this.GetCart();
        }
    });
</script>