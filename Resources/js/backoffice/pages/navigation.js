new Vue({
    el: "#navigation",
    methods: {
        Logout() {
            axios
            .delete("/api/v1/logout/backoffice")
            .then(function () {
                location.reload();
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    }
});