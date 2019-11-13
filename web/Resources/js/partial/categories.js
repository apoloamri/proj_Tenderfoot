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
        GetSearchTag(searchTag) {
            if (typeof index != "undefined") {
                index.Search = "";
                index.SearchTag = searchTag;
                index.Count = 10;
                index.GetProducts();
            }
            else {
                window.location = "/?tag=" + searchTag;    
            }
        },
        GetTracking() {
            window.location = "/tracking/" + this.Tracking;
        }
    },
    created() {
        this.GetTags();
    }
});