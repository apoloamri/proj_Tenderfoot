<?php $this->Partial("navigation") ?>
<div id="adminPages">
    <div id="adminContent">
        <div id="adminInnerContent">
            <h3><a href="/admin/orders">◄ Orders</a></h3>
            <div style="overflow:auto;">
                <h2>Order Details</h2>
                <div class="adminTable float-left" style="width:50%;">
                    <label><b>Order Number</b></label>
                    <label>{{Details.str_order_number}}</label><br/>
                    <label><b>Phone Number</b></label>
                    <label>{{Details.str_phonenumber}}</label><br/>
                    <label><b>Customer Name</b></label>
                    <label>{{Details.str_last_name}}, {{Details.str_first_name}}</label><br/>
                    <label><b>Street Address</b></label>
                    <label>{{Details.str_address}}</label><br/>
                    <label><b>Barangay</b></label>
                    <label>{{Details.str_barangay}}</label><br/>
                    <label><b>City</b></label>
                    <label>{{Details.str_city}}</label><br/>
                    <label><b>Postal Code</b></label>
                    <label>{{Details.str_postal}}</label>
                </div>
                <div id="statusChange" class="float-left">
                    <center>
                        <label v-bind:class="{ 'green': Details.str_order_status == 'Fulfilled', 'red': Details.str_order_status == 'Cancelled' }">Current status: <b>{{Details.str_order_status}}</b></label><br/>
                        <button id="cancel" v-on:click="CancelOrder" class="statusChange red">Cancel</button>
                        <button id="button1" v-on:click="PutOrder" class="statusChange">Processed</button><label class="arrow">▾<br/></label>
                        <button id="button2" v-on:click="PutOrder" class="statusChange">On Delivery</button><label class="arrow">▾<br/></label>
                        <button id="button3" v-on:click="PutOrder" class="statusChange">Delivered</button><label class="arrow">▾<br/></label>
                        <button id="button4" v-on:click="PutOrder" class="statusChange">Fulfilled</button>
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
                    <tr v-for="item in CartItems">
                        <td>{{item.str_code}}</td>
                        <td>{{item.dbl_price}}</td>
                        <td>{{item.int_amount}}</td>
                        <td>{{item.dbl_total_price}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>Total</b></td>
                        <td>{{Details.dbl_total}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="/Resources/js/admin/orders_detail.js" async></script>

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