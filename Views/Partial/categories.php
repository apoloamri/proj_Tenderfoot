<div id="categories">
    <div id="categories">
        <div class="content">
            <div class="header">Track Order</div>
            <input type="text" placeholder="Tracking number here..." v-model="tracking" v-on:keyup="Tracking()" />
        </div>
        <div class="content">
            <div class="header">Categories</div>
            <div class="links" v-for="item in menu">
                <a v-bind:href="'/?tag=' + item.str_tag">{{item.str_tag}}</a>
            </div>
        </div>
    </div>
</div>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#categories",
        data: {
            menu: [],
            tracking: ""
        },
        methods: {
            GetTags: function () {
                var self = this;
                Lib.Get("/api/products/tags", null,
                function (success) {
                    self.menu = success.Result;
                });
            },
            Tracking: function () {
                var self = this;
                if (event.key === "Enter") {
                    window.location = "/tracking/" + self.tracking;
                }
            }
        },
        created() {
            this.GetTags();
        }
    });
</script>