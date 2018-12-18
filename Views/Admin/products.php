<?php $this->Partial("navigation") ?>
<div id="adminPages">
    <div id="adminContent">
        <div id="adminInnerContent">
            <h2>Products</h2>
            <button onclick="window.location='/admin/products/add';">Add product</button>
            <button onclick="window.location='/admin/products/tags';">Edit Tags</button>
            <div class="adminTable">
                <input type="text" v-model="Search" v-on:input="GetProductsDelay()" placeholder="Search products" />
                <table>
                    <tr>
                        <th width="50%" colspan="2">Product</th>
                        <th width="17%">Inventory</th>
                        <th width="17%">Brand</th>
                        <th width="17%">Price</th>
                    </tr>
                    <tr v-for="item in Result">
                        <td width="5%"><div v-bind:style="{ 'background-image': 'url(' + item.str_path + ')' }" class="image size-50"></div></td>
                        <td v-on:click="Redirect(item.id)">({{item.str_code}}) {{item.str_name}}</td>
                        <td>{{item.int_amount}}</td>
                        <td v-on:click="Redirect(item.id)">{{item.str_brand}}</td>
                        <td v-on:click="Redirect(item.id)">{{item.dbl_price}}</td>
                    </tr>
                </table>
                <div class="spacer-h-15"></div>
                <center>
                    <a href="#" v-on:click="PrevPage">🡄</a>
                    {{Page}} / {{PageCount}}
                    <a href="#" v-on:click="NextPage">🡆</a>
                </center>
            </div>
        </div>
    </div>
</div>
<script src="/Resources/js/admin/products.js" async></script>

<style>
    input[type="number"] {
        width: 30px;
    }
    button {
        display: inline-block;
    }
</style>