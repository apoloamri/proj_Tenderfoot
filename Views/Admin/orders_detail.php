<div id="adminPages">
    <?php $this->Partial("admin_navigation") ?>
    <div id="adminContent">
        <div id="adminInnerContent">
            <h3><a href="/admin/orders">◄ Orders</a></h3>
            <h2>Order Details</h2>
            <div class="adminTable" style="width:50%;">
                <label><b>Order Number</b></label>
                <label>{{details.str_order_number}}</label><br/>
                <label><b>Phone Number</b></label>
                <label>{{details.str_phonenumber}}</label><br/>
                <label><b>Customer Name</b></label>
                <label>{{details.str_last_name}}, {{details.str_first_name}}</label><br/>
                <label><b>Street Address</b></label>
                <label>{{details.str_address}}</label><br/>
                <label><b>Barangay</b></label>
                <label>{{details.str_barangay}}</label><br/>
                <label><b>City</b></label>
                <label>{{details.str_city}}</label><br/>
                <label><b>Postal Code</b></label>
                <label>{{details.str_postal}}</label>
            </div>
            <h3>Items</h3>
            <div class="adminTable">
                <table>
                    <tr>
                        <th width="60%">Product Code</th>
                        <th width="15%">Price</th>
                        <th width="10%">Amount</th>
                        <th width="15%">Total</th>
                    </tr>
                    <tr v-for="item in cartItems">
                        <td>{{item.str_code}}</td>
                        <td>{{item.dbl_price}}</td>
                        <td>{{item.num_amount}}</td>
                        <td>{{item.dbl_total_price}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>Total</b></td>
                        <td>{{details.dbl_total}}</td>
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
            id: "<?php echo $this->Id; ?>",
            details: null,
            cartItems: []
        },
        methods: {
            GetOrder: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Get("/api/orders", {
                    "Id": self.id
                },
                function (success) {
                    self.details = success.Result;
                    self.cartItems = success.CartItems;
                    Lib.InitialLoading(false);
                });
            }
        },
        created() {
            this.GetOrder();
        }
    });
</script>