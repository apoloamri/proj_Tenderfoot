new Vue({
    el: "#adminNavigation",
    methods: {
        Logout() {
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