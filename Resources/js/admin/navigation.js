new Vue({
    el: "#adminNavigation",
    methods: {
        Logout() {
            var self = this;
            axios
            .delete("/admin/api/logout")
            .then(function () {
                location.reload(); 
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    }
});