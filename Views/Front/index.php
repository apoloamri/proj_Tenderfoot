<?php $this->Partial("menu") ?>
<?php $this->Partial("categories") ?>
<div id="mainContent">
    <h3 v-if="Search != ''">Search: '{{Search}}'</h3>
    <h3 v-else-if="SearchTag != ''">Items tagged: '{{SearchTag}}'</h3>
    <div v-else>
        <div v-if="Store.str_header != null && Store.str_header != ''" id="header">
            <img v-bind:src="Store.str_header" alt=""><hr/>
        </div>
    </div>
    <div v-if="Search == '' && SearchTag == ''">
        <h1>Top Trending</h1>
        <div class="items" v-for="item in Result">
            <center>
                <a v-bind:href="'/detail/' + item.str_code">
                    <div class="image" v-bind:style="'background-image: url(' + item.str_path + ')'"></div>
                    <div class="content">
                        <label class="font-17">{{item.str_name}}</label><br/>
                        <label class="font-15">₱{{item.dbl_price}}</label>
                    </div>
                </a>
                <button v-on:click="Menu().PostCart(item.str_code)" :disabled="item.int_amount == null || item.int_amount == 0">
                    <label v-if="item.int_amount == null || item.int_amount == 0">OUT OF STOCK</label>
                    <label v-else>ADD TO CART</label>
                </button>
            </center>
        </div>
    </div>
</div>
<script src="/Resources/js/front/index.js" async></script>
<?php $this->Partial("footer") ?>