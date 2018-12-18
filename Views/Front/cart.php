<?php $this->Partial("menu") ?>
<?php $this->Partial("categories") ?>
<div id="mainContent">
    <div id="breadCrumbs"><a href="/">Home</a> › Cart</div>
    <hr/>
    <center>
        <table class="cartTable">
            <tr>
                <th width="10%"></th>
                <th width="50%">Item brand / name</th>
                <th width="15%">Price</th>
                <th width="15%">Amount</th>
                <th width="10%"></th>
            </tr>
            <tr v-for="item in Result">
                <td><div class="cartImage" v-bind:style="'background-image: url(' + item.str_path + ')'"></div></td>
                <td><a v-bind:href="'/detail/' + item.str_code">{{item.str_brand}} - {{item.str_name}}</a></td>
                <td>₱{{item.dbl_price}}</td>
                <td><input type="number" v-on:change="PutCart(item.str_code, $event.target.value)" v-bind:value="item.int_amount" /></td>
                <td><button v-on:click="DeleteCart(item.str_code);">Delete</button></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="padding:20px;">Total price:</td>
                <td>₱{{Total}}</td>
            </tr>
        </table>
        <button style="width:40%;" onclick="window.location='/order'" :disabled="Count == 0"><h3>Continue Order</h3></button>
    </center>
</div>
<script src="/Resources/js/front/cart.js" async></script>
<?php $this->Partial("footer") ?>