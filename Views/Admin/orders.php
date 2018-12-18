<?php $this->Partial("navigation") ?>
<div id="adminPages">
    <div id="adminContent">
        <div id="adminInnerContent">
            <h2>Orders</h2>
            <div class="adminTable">
                <input type="text" v-model="Search" v-on:input="GetOrdersDelay()" placeholder="Search orders" /><br/>
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
                    <tr v-for="order in Result" v-bind:class="{ 'green' : order.str_order_status == 'New Order' }">
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
                    {{Page}} / {{PageCount}}
                    <a href="#" v-on:click="NextPage()">🡆</a>
                </center>
            </div>
        </div>
    </div>
</div>
<script src="/Resources/js/admin/orders.js" async></script>

<style>
    input[type="number"] {
        width: 30px;
    }
    .status {
        width: 110px;
        display: inline-block;
    }
</style>