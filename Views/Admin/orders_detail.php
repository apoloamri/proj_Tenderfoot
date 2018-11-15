<div id="adminPages">
    <?php $this->Partial("admin_navigation") ?>
    <div id="adminContent">
        <div id="adminInnerContent">
            <h3><a href="/admin/orders">◄ Orders</a></h3>
            <div style="overflow:auto;">
                <h2>Order Details</h2>
                <div class="adminTable float-left" style="width:50%;">
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
                <div id="statusChange" class="float-left">
                    <center>
                        <label v-bind:class="{ 'green': details.str_order_status == 'Fulfilled' }">Current status: <b>{{details.str_order_status}}</b></label><br/>
                        <button id="button1" v-on:click="PutOrder()" class="statusChange">Processed</button><label class="arrow">▾<br/>▾<br/></label>
                        <button id="button2" v-on:click="PutOrder()" class="statusChange">On Delivery</button><label class="arrow">▾<br/>▾<br/></label>
                        <button id="button3" v-on:click="PutOrder()" class="statusChange">Delivered</button><label class="arrow">▾<br/>▾<br/></label>
                        <button id="button4" v-on:click="PutOrder()" class="statusChange">Fulfilled</button>
                    </center>
                </div>
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

<style>
    #statusChange {
        width: 45%;
    }
    #statusChange button {
        width: 70%;
    }
    .arrow {
        color: lightgray;
    }
</style>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#adminInnerContent",
        data: {
            id: "<?php echo $this->Id; ?>",
            details: { type: Object, default: () => ({}) },
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
                    self.Disable();
                });
            },
            PutOrder: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Put("/api/orders", {
                    "Id": self.id
                },
                function (success) {
                    Lib.InitialLoading(false);
                    self.GetOrder();
                });
            },
            Disable: function () {
                var self = this;
                $(".statusChange").prop("disabled", true);
                switch (self.details.str_order_status)
                {
                    case "New Order":
                    $("#button1").removeAttr("disabled");
                    break;
                    case "Processed":
                    $("#button2").removeAttr("disabled");
                    break;
                    case "On Delivery":
                    $("#button3").removeAttr("disabled");
                    break;
                    case "Delivered":
                    $("#button4").removeAttr("disabled");
                    break;
                }
            }
        },
        created() {
            this.GetOrder();
        }
    });
</script>