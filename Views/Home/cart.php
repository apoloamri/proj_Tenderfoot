<div id="cart">
    <?php $this->Partial("navigation") ?>
    <div id="mainContent">
        <center>
            <table id="cartTable">
                <tr>
                    <th width="10%"></th>
                    <th width="50%">Item brand / name</th>
                    <th width="15%">Price</th>
                    <th width="15%">Amount</th>
                    <th width="10%"></th>
                </tr>
                <tr v-for="item in result">
                    <td><div class="itemImage-50" v-bind:style="'background-image: url(' + item.str_image_url + ')'"></div></td>
                    <td>{{item.str_brand}} - {{item.str_name}}</td>
                    <td>₱{{item.dbl_price}}</td>
                    <td>{{item.num_amount}}</td>
                    <td><button v-on:click="DeleteCart(item.str_code);">Delete</button></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="padding:20px;">Total price:</td>
                    <td>₱{{total}}</td>
                </tr>
            </table>
        </center>
        <?php $this->Partial("login_form") ?>
    </div>
</div>

<script type="module">
import Lib from "/Resources/js/lib.js";
new Vue({
    el: "#cartTable",
    data: { 
        result: [],
        count: 0,
        total: 0,
    },
    methods: {
        GetCart: function () {
            var self = this;
            Lib.Get("/api/shop/cart", {
                "sessionId": Lib.GetCookie("session_id")
            }, function (success) {
                self.result = success.result;
                self.count = success.count;
                self.total = success.total;
            });
        },
        DeleteCart: function (itemCode) {
            var self = this;
            Lib.Delete("/api/shop/cart", {
                "itemCode": itemCode,
                "sessionId": Lib.GetCookie("session_id") 
            });
            self.GetCart();
            setTimeout(function () {
                cartHeader.__vue__.count = self.count
            }, 1000);
        }
    },
    created() {
        this.GetCart();
    }
});
</script>