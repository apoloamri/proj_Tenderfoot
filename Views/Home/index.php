<div id="home">
    <?php $this->Partial("navigation") ?>
    <div id="mainContent">
        <h3>Latest Items</h3>
        <div class="items" v-for="item in result">
            <center>
                <a v-bind:href="'/item/' + item.str_code">
                    <div class="itemImage" v-bind:style="'background-image: url(' + item.str_image_url + ')'"></div>
                    <label class="font-17">{{item.str_brand}} - {{item.str_name}}</label><br>
                    <label class="font-13">₱{{item.dbl_price}}</label><br>
                </a>
                <button>Add to cart</button>
            </center>
        </div>
    </div>
</div>

<script type="module">
    import Common from "/Resources/js/script.js";
    new Vue({
        el: "#mainContent",
        data: { 
            result: []
        },
        methods: {
            GetItems: function () {
                var self = this;
                Common.Get("/api/shop/items", {
                    "count": 6
                },
                function (success) {
                    self.result = success.result;
                });
            }
        },
        created() {
            this.GetItems();
        }
    });
</script>