<div id="menu">
    <div id="menuUpper">
        <div onclick="window.location='/';" id="menuLogo"></div>
        <div id="menuCartDiv">
            <a href="/cart" class="float-right">
                <img src="/Resources/images/shopping-cart.png" class="menuImages" /> 
                {{count}} item/s (₱{{total}})
            </a>
        </div>
    </div>
    <div id="menuLower">
        <div id="menuNaviDiv">
            <a href="/">Home</a>
            <a href="/">Contact Us</a>
            <a href="/">FAQ</a>
        </div>
        <div id="menuSearchDiv">
            <input type="text" placeholder="Search" id="menuSearch" v-model="search" v-on:keyup="Search()" />
            <img src="/Resources/images/magnifying-glass.png" class="menuImages" v-on:click="Search()" /> 
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