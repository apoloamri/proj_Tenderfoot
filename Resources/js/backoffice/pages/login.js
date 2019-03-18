new Vue({
    el: "#login",
    data: { 
        username: "",
        password: "",
        messages: []
    },
    methods: {
        Login() {
            var self = this;
            axios
            .post("/api/v1/login/backoffice", self.$data)
            .then(function () {
                window.location = "/backoffice";
            })
            .catch(function (error) {
                self.messages = error.response.data.messages;
                Loading(false);
            });
        }
    }
});