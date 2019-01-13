var order = new Vue({
    el: "#mainContent",
    data: { 
        Result: [],
        Count: 0,
        Total: 0,
        OrderNumber: "",
        PhoneNumber: "",
        Email: "",
        LastName: "",
        FirstName: "",
        Address: "",
        Barangay: "",
        City: "",
        PostalCode: "",
        Messages: []
    },
    methods: {
        PostOrder() {
            var self = this;
            Loading(true);
            axios
            .post("/api/orders", self.$data)
            .then(function (response) {
                self.OrderNumber = response.data.OrderNumber;
                Loading(false);
                $(".modal").show();
            })
            .catch(function (error) {
                self.Messages = error.response.data.Messages;
                $("html, body").animate({ scrollTop: 0 }, "slow");
                Loading(false);
            });
        }
    }
});