var productAdd = new Vue({
    el: "#adminInnerContent",
    data: {
        Id: pageId,
        Amount: 0,
        Code: "",
        Brand: "",
        Name: "",
        Description: "",
        Tags: "",
        Price: 0,
        SalePrice: 0,
        ImagePaths: [],
        Messages: []
    },
    methods: {
        GetProduct() {
            var self = this;
            Loading(true);
            axios
            .get("/api/products", { params: self.$data })
            .then(function (response) {
                self.Amount = response.data.Result.int_amount;
                self.Code = response.data.Result.str_code;
                self.Brand = response.data.Result.str_brand;
                self.Name = response.data.Result.str_name;
                self.Description = response.data.Result.txt_description;
                self.Tags = response.data.Result.Tags;
                self.Price = response.data.Result.dbl_price;
                self.SalePrice = response.data.Result.dbl_sale_price;
                if (response.data.Result.ImagePaths != null) {
                    self.ImagePaths = response.data.Result.ImagePaths;
                }
                Loading(false);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        PostProducts() {
            var self = this;
            Loading(true);
            axios
            .post("/api/products", self.$data)
            .then(function (response) { 
                alert("Product Added");
                window.location = "/admin/products/edit/" + response.data.Id;
            })
            .catch(function (error) {
                self.Messages = error.response.data.Messages;
                $("html, body").animate({ scrollTop: 0 }, "slow");
                Loading(false);
            });
        },
        PutProducts() {
            var self = this;
            Loading(true);
            axios
            .put("/api/products", self.$data)
            .then(function () { 
                alert("Product Updated");
                Loading(false);
            })
            .catch(function (error) {
                self.Messages = error.response.data.Messages;
                $("html, body").animate({ scrollTop: 0 }, "slow");
                Loading(false);
            });
        },
        DeleteProducts() {
            var self = this;
            Loading(false);
            axios
            .delete("/api/products", { params: self.$data })
            .then(function (response) { 
                alert("Product Deleted");
                window.location = "/admin/products";
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        PutInventory() {
            var self = this;
            Loading(true);
            axios
            .put("/api/products/inventory", self.$data)
            .then(function () {
                alert("Inventory Updated");
                Loading(false);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        UploadImage() {
            var self = this;
            Loading(true);
            var formData = new FormData();
            formData.append("ImageFile", $("#image").prop("files")[0]);
            axios
            .post("/api/products/image", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function (response) { 
                self.ImagePaths.push(response.data.ImagePath);
                Loading(false);
            })
            .catch(function (error) {
                self.Messages = error.response.data.Messages;
                Loading(false);
            });
            $("#image").val(null);
        },
        Clear() {
            this.Code = "";
            this.Brand = "";
            this.Name = "";
            this.Description = "";
            this.Tags = "";
            this.Price = null;
            this.ImagePaths = [];
        }
    },
    mounted() {
        window.addEventListener("load", () => {
            if (this.Id != "") {
                this.GetProduct();
            }
        });
    }
});