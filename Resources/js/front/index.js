var index = new Vue({
    el: "#mainContent",
    data: { 
        Search: Param("search"),
        SearchTag: Param("tag"),
        Count: 10,
        Store: {},
        Page: 1,
        NewProducts: [],
        Trending: []
    },
    methods: {
        Menu() { return menu; },
        GetStore() {
            var self = this;
            axios
            .get("/api/store")
            .then(function (response) {
                self.Store = response.data.Store;
                Loading(false);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        GetProducts() {
            var self = this;
            axios
            .get("/api/products", { params: self.$data })
            .then(function (response) {
                self.NewProducts = response.data.Result;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        GetTrending() {
            var self = this;
            axios
            .get("/api/store/trending", { params: { Page: 1, Count: 10 } })
            .then(function (response) {
                self.Trending = response.data.Result;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        AddCount() {
            this.Count = this.Count + 10;
            this.GetProducts();
        }
    },
    created() {
        this.GetStore();
        this.GetProducts();
        this.GetTrending();
    }
});