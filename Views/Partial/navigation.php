<img src="/Resources/images/magnifying-glass.png" style="height:20px;margin:-3px 3px;"> 
<input type="text" placeholder="Search" id="globalSearch">
<div id="cartHeader" class="float-right">
    <a href="/cart" class="margin-15">
        <img src="/Resources/images/shopping-cart.png" style="height:20px;margin:-3px 3px;"> 
        Cart ({{count}})
    </a>
</div><hr>
<div id="navigation" class="float-left">
    <label id="headingText">Shopping.</label><hr>
    <a href="/" class="margin-15">Home</a><hr>
    <a href="/" class="margin-15">Accessories</a><hr>
    <a href="/" class="margin-15">Denim</a><hr>
    <a href="/" class="margin-15">Footwear</a><hr>
    <a href="/" class="margin-15">Jeans</a><hr>
</div>

<script type="module">
import Lib from "/Resources/js/lib.js";
new Vue({
    el: "#cartHeader",
    data: { 
        count: 0
    },
    methods: {
        GetCart: function () {
            var self = this;
            Lib.Get("/api/shop/cart", {
                "sessionId": Lib.GetCookie("session_id")
            },
            function (success) {
                self.count = success.count;
            });
        }
    },
    created() {
        this.GetCart();
    }
});
</script>