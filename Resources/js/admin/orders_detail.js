new Vue({
    el: "#adminInnerContent",
    data: {
        Id: window.location.pathname.split("/").pop(),
        Details: {},
        CartItems: []
    },
    methods: {
        GetOrder() {
            var self = this;
            Loading(true);
            axios
            .get("/api/orders", { params: self.$data })
            .then(function (response) {
                self.Details = response.data.Result;
                self.CartItems = response.data.CartItems;
                Loading(false);
                self.Disable();
            });
        },
        PutOrder() {
            var self = this;
            Loading(true);
            axios
            .put("/api/orders", self.$data)
            .then(function () {
                Loading(false);
                self.GetOrder();
            })
            .catch(function (error) {
                alert(error.response.data.Messages.Id);
                self.GetOrder();
            });
        },
        CancelOrder() {
            var self = this;
            Loading(true);
            axios
            .delete("/api/orders", { params: { Id: self.Id } })
            .then(function () {
                Loading(false);
                self.GetOrder();
            })
            .catch(function (response) {
                alert(response.data.Messages.Id);
                self.GetOrder();
            });
        },
        Disable() {
            var self = this;
            $(".statusChange").prop("disabled", true);
            $("#cancel").removeAttr("disabled");
            switch (self.Details.str_order_status)
            {
                case "New Order":
                $("#button1").removeAttr("disabled");
                break;
                case "Processed":
                $("#button2").removeAttr("disabled");
                break;
                case "On Delivery":
                $("#button3").removeAttr("disabled");
                break;
                case "Delivered":
                $("#button4").removeAttr("disabled");
                break;
            }
        }
    },
    mounted() {
        window.addEventListener("load", () => {
            this.GetOrder();
        });
    }
});