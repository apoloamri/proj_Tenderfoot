var index = new Vue({
    el: "#mainContent",
    data: { 
        Search: "",
        SearchTag: "",
        Store: {},
        Page: 1,
        Result: [],
        PageCount: 0,
        Count: 5,
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
                self.Result = response.data.Result;
                self.PageCount = response.data.PageCount;
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    },
    created() {
        this.GetStore();
        this.GetProducts();
    }
});