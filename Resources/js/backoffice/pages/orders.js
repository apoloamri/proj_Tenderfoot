new Vue({
    el: "#adminInnerContent",
    data: {
        Search: "",
        OrderStatus: "",
        Page: 1,
        Count: 10,
        PageCount: 0,
        Result: []
    },
    methods: {
        GetOrdersDelay: _.debounce(function () {
            this.GetOrders();
        }, 500),
        GetOrders: function () {
            var self = this;
            Loading(true);
            axios
            .get("/api/orders", { params: self.$data })
            .then(function (response) {
                self.Result = response.data.Result;
                self.PageCount = response.data.PageCount;
                Loading(false);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        GetList: function (OrderStatus) {
            this.Page = 1;
            this.OrderStatus = OrderStatus;
            this.GetOrders();
        },
        NextPage: function () {
            if (this.Page < this.PageCount) {
                this.Page = this.Page + 1;
                this.GetOrders();
            }
        },
        PrevPage: function () {
            if (this.Page != 1) {
                this.Page = this.Page - 1;
                this.GetOrders();
            }
        },
        Redirect: function (id) {
            window.location = "/admin/orders/detail/" + id;
        }
    },
    mounted() {
        window.addEventListener("load", () => {
            this.GetOrders();
        });
    }
});