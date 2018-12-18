new Vue({
    el: "#adminInnerContent",
    data: {
        Search: "",
        Result: []
    },
    methods: {
        GetTagsDelay() {
            var self = this;
            Lib.Delay(function () {
                self.GetTags();
            }, 500);
        },
        GetTags() {
            var self = this;
            Loading(true);
            axios
            .get("/api/products/tags")
            .then(function (response) {
                self.Result = response.data.Result;
                Loading(false);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        UploadImage: function (tagName, id) {
            var self = this;
            Loading(true);
            var formData = new FormData();
            formData.append("ImageFile", $("#image_" + id).prop("files")[0]);
            formData.append("TagName", tagName);
            axios
            .post("/api/products/tags/image", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function () {
                Loading(false);
                self.GetTags();
            })
            .catch(function (error) {
                Loading(false);
                alert(error.response.data.Messages.ImageFile);
            });
            $("#image_" + id).val(null);
        },
        DeleteTags: function (tagName) {
            var self = this;
            Loading(true);
            axios
            .delete("/api/products/tags", {
                params: { 
                    "TagName": tagName 
                }
            })
            .then(function () {
                alert("Tag successfully removed.");
                Loading(false);
                self.GetTags();
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        DeleteImage: function (tagName) {
            var self = this;
            Loading(true);
            axios
            .put("/api/products/tags/image", {
                "TagName": tagName
            })
            .then(function () {
                Loading(false);
                self.GetTags();
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        Show: function (subName) {
            $("." + subName).toggle();
        },
        Redirect: function (id) {
            window.location = '/admin/products/edit/' + id;
        }
    },
    mounted() {
        window.addEventListener("load", () => {
            this.GetTags();
            $("button").prop("disabled", false);
        });
    }
});