new Vue({
    el: "#adminInnerContent",
    data: {
        announcement: "",
        store: {},
        messages: []
    },
    methods: {
        GetStore: function () {
            var self = this;
            Loading(true);
            axios
            .get("/api/store")
            .then(function (response) { 
                self.store = response.data.Store;
                Loading(false);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        UploadHeader: function () {
            var self = this;
            Loading(true);
            var formData = new FormData();
            formData.append("ImageFile", $("#header").prop("files")[0]);
            axios
            .post("/api/store/header", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function () { 
                Loading(false);
                self.GetStore();
            })
            .catch(function (error) {
                self.messages = error.response.data.Messages;
                Loading(false);
            });
            $("#header").val(null);
        },
        DeleteHeader: function () {
            var self = this;
            Loading(true);
            axios
            .delete("/api/store/header")
            .then(function () { 
                Loading(false);
                self.GetStore();
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    },
    mounted() {
        window.addEventListener("load", () => {
            this.GetStore();
        });
    }
});