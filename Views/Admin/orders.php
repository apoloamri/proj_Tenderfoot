<div id="adminPages">
    <?php $this->Partial("admin_navigation") ?>
    <div id="adminContent">
        <div id="adminInnerContent">
            <h2>Orders</h2>
            <div class="adminTable">
                <input type="text" v-model="search" v-on:keyup="GetOrdersDelay()" placeholder="Search orders" /><br/>
                <button class="status" v-on:click="GetList('New Order')">New</button>
                <button class="status" v-on:click="GetList('Processed')">Processed</button>
                <button class="status" v-on:click="GetList('On Delivery')">On Delivery</button>
                <button class="status" v-on:click="GetList('Delivered')">Delivered</button>
                <button class="status" v-on:click="GetList('Fulfilled')">Fulfilled</button>
                <button class="status red" v-on:click="GetList('Cancelled')">Cancelled</button>
                <table>
                    <tr>
                        <th width="25%">Order Number</th>
                        <th width="25%">Customer Name</th>
                        <th width="15%">Total Purchase</th>
                        <th width="15%">Status</th>
                        <th width="20%">Date</th>
                    </tr>
                    <tr v-for="order in result" v-bind:class="{ 'green' : order.str_order_status == 'New Order' }">
                        <td v-on:click="Redirect(order.id)">{{order.str_order_number}}</td>
                        <td v-on:click="Redirect(order.id)">{{order.str_last_name}}, {{order.str_first_name}}</td>
                        <td v-on:click="Redirect(order.id)">{{order.dbl_total}}</td>
                        <td v-on:click="Redirect(order.id)">{{order.str_order_status}}</td>
                        <td v-on:click="Redirect(order.id)">{{order.dat_insert_time}}</td>
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
    .status {
        width: 110px;
        display: inline-block;
    }
</style>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#adminInnerContent",
        data: {
            search: "",
            orderStatus: "",
            page: 1,
            pageCount: 0,
            result: []
        },
        methods: {
            GetOrdersDelay: function () {
                var self = this;
                Lib.Delay(function () {
                    self.GetOrders();
                }, 500);
            },
            GetOrders: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Get("/api/orders", {
                    "Search": self.search,
                    "OrderStatus": self.orderStatus,
                    "Page": self.page,
                    "Count": 10
                },
                function (success) {
                    self.result = success.Result;
                    self.pageCount = success.PageCount;
                    Lib.InitialLoading(false);
                });
            },
            GetList: function (orderStatus) {
                var self = this;
                self.orderStatus = orderStatus;
                self.GetOrders();
            },
            NextPage: function () {
                if (this.page < this.pageCount) {
                    this.page = this.page + 1;
                    this.GetOrders();
                }
            },
            PrevPage: function () {
                if (this.page != 1) {
                    this.page = this.page - 1;
                    this.GetOrders();
                }
            },
            Redirect: function (id) {
                window.location = "/admin/orders/detail/" + id;
            }
        },
        created () {
            this.GetOrders();
        }
    });
</script>