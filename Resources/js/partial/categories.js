new Vue({
    el: "#categories",
    data: {
        Categories: [],
        Tracking: ""
    },
    methods: {
        GetTags() {
            var self = this;
            axios
            .get("/api/products/tags")
            .then(function (response) {
                self.Categories = response.data.Result;
            });
        },
        GetTracking() {
            window.location = "/tracking/" + this.Tracking;
        }
    },
    created() {
        this.GetTags();
    }
});