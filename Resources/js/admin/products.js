new Vue({
    el: "#adminInnerContent",
    data: {
        Search: "",
        Page: 1,
        Count: 10,
        PageCount: 0,
        Result: []
    },
    methods: {
        GetProductsDelay: _.debounce(function () {
            this.GetProducts();
        }, 500),
        GetProducts() {
            var self = this;
            Loading(true);
            axios
            .get("/api/products", { params: self.$data })
            .then(function (response) {
                self.Result = response.data.Result;
                self.PageCount = response.data.PageCount;
                Loading(false);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        NextPage() {
            if (this.Page < this.PageCount) {
                this.Page = this.Page + 1;
                this.GetProducts();
            }
        },
        PrevPage() {
            if (this.Page != 1) {
                this.Page = this.Page - 1;
                this.GetProducts();
            }
        },
        Redirect(id) {
            window.location = "/admin/products/edit/" + id;
        }
    },
    mounted() {
        window.addEventListener("load", () => {
            this.GetProducts();
        });
    }
});