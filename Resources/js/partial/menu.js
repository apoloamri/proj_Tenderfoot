var menu = new Vue({
    el: "#menu",
    data: {
        Search: Param("search"),
        Total: 0,
        Count: 0
    },
    methods: {
        PostCart(itemCode) {
            var self = this;
            axios
            .post("/api/cart", { "Code": itemCode })
            .then(function () {
                self.GetCart();
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        GetCart() {
            var self = this;
            axios.get("/api/cart")
            .then(function (response) {
                if (typeof cart != "undefined") {
                    cart.Result = response.data.Result;
                    cart.Total = response.data.Total;
                    cart.Count = response.data.Count;
                }
                if (typeof order != "undefined") {
                    order.Result = response.data.Result;
                    order.Total = response.data.Total;
                    order.Count = response.data.Count;
                }
                self.Total = response.data.Total;
                self.Count = response.data.Count;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        GetSearch() {
            index.Search = this.Search;
            index.SearchTag = "";
            index.Count = 10;
            index.GetProducts();
        }
    },
    created() {
        this.GetCart();
    }
});