new Vue({
    el: "#adminLogin",
    data: { 
        Username: "",
        Password: "",
        Messages: []
    },
    methods: {
        Login() {
            var self = this;
            Loading(true);
            axios
            .post("/admin/api/login", self.$data)
            .then(function () {
                window.location = "/admin";
            })
            .catch(function (error) {
                self.Messages = error.response.data.Messages;
                Loading(false);
            });
        }
    }
});