<div id="adminPages">
    <?php $this->Partial("admin_navigation") ?>
    <div id="adminContent">
        <div id="adminInnerContent">
            <h3><a href="/admin/products">◄ Products</a></h3>
            <h2><?php echo $this->PageTitle ?></h2>
            <div class="adminTable">
                <h3>Product</h3>
                <label>Code</label>
                <input type="text" v-model="code" placeholder="SKU10001" />
                <label class="red">{{messages["Code"]}}</label>
                <label>Brand</label>
                <input type="text" v-model="brand" placeholder="Microsoft" />
                <label class="red">{{messages["Brand"]}}</label>
                <label>Name</label>
                <input type="text" v-model="name" placeholder="Surface Pro" />
                <label class="red">{{messages["Name"]}}</label>
                <label>Description</label>
                <textarea v-model="description" placeholder="Unplug. Pack light. Get productive your way, all day, with the new Surface Pro 6 - now faster than ever with the latest 8th Gen Intel Core processor."></textarea>
                <label class="red">{{messages["Description"]}}</label>
            </div>
            <div class="spacer-h-15"></div>
            <div class="adminTable">
                <h3>Pricing</h3>
                <label>Price</label>
                <input type="number" v-model="price" placeholder="15,000.00" />
                <label class="red">{{messages["Price"]}}</label>
            </div>
            <div class="spacer-h-15"></div>
            <div class="adminTable">
                <h3>Images</h3>
                <input type="file" v-on:change="UploadImage()" id="image" />
                <button v-on:click="imagePaths = []" class="gray inline-block">Remove Images</button>
                <label class="red">{{messages["ImageFile"]}}</label>
                <div v-for="image in imagePaths" v-bind:style="{ 'background-image': 'url(' + image + ')' }" class="image size-100"></div>
            </div>
            <hr />
            <div class="float-right">
                <div v-if="id == ''" class="inline-block">
                    <button v-on:click="PostProducts()" class="inline-block">Add product</button>
                </div>
                <div v-else class="inline-block">
                    <button v-on:click="PutProducts()" class="inline-block">Update product</button>
                    <button v-on:click="DeleteProducts()" class="red inline-block">Delete product</button>
                </div>
                <button v-on:click="Clear()" class="gray inline-block">Clear</button>
            </div>
        </div>
    </div>
</div>

<style>
    .adminTable {
        padding: 20px;
        width: 60%;
        margin: 0 auto;
    }
    .adminTable input,
    .adminTable textarea {
        width: 90%;
    }
    .adminTable input[type="number"] {
        width: 30%;
    }
</style>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#adminInnerContent",
        data: {
            id: "<?php echo $this->Id; ?>",
            code: "",
            brand: "",
            name: "",
            description: "",
            price: null,
            imagePaths: [],
            messages: []
        },
        methods: {
            GetProduct: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Get("/api/products", {
                    "Id": self.id
                },
                function (success) {
                    self.code = success.Result.str_code;
                    self.brand = success.Result.str_brand;
                    self.name = success.Result.str_name;
                    self.description = success.Result.str_description;
                    self.price = success.Result.dbl_price;
                    if (success.ImagePaths != null) {
                        self.imagePaths = success.ImagePaths;
                    }
                    Lib.InitialLoading(false);
                });
            },
            PostProducts: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Post("/api/products", {
                    "Code": self.code,
                    "Brand": self.brand,
                    "Name": self.name,
                    "Description": self.description,
                    "Price": self.price,
                    "ImagePaths": self.imagePaths
                },
                function (success) { 
                    alert("Product Added");
                    window.location = "/admin/products";
                },
                function (failed) {
                    var response = failed.responseJSON;
                    self.messages = response.Messages;
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    Lib.InitialLoading(false);
                });
            },
            PutProducts: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Put("/api/products", {
                    "Id": self.id,
                    "Code": self.code,
                    "Brand": self.brand,
                    "Name": self.name,
                    "Description": self.description,
                    "Price": self.price,
                    "ImagePaths": self.imagePaths
                },
                function (success) { 
                    alert("Product Updated");
                    Lib.InitialLoading(false);
                },
                function (failed) {
                    var response = failed.responseJSON;
                    self.messages = response.Messages;
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    Lib.InitialLoading(false);
                });
            },
            DeleteProducts: function () {
                var self = this;
                Lib.InitialLoading(false);
                Lib.Delete("/api/products", {
                    "Id": self.id
                },
                function (success) { 
                    alert("Product Deleted");
                    window.location = "/admin/products";
                });
            },
            UploadImage: function () {
                var self = this;
                Lib.InitialLoading(true);
                var image = $("#image").prop("files")[0];
                Lib.Form("/api/products/image", {
                    "ImageFile": image
                },
                function (success) { 
                    self.imagePaths.push(success.ImagePath);
                    Lib.InitialLoading(false);
                },
                function (failed) {
                    var response = failed.responseJSON;
                    self.messages = response.Messages;
                    Lib.InitialLoading(false);
                });
                $("#image").val(null);
            },
            Clear: function () {
                var self = this;
                self.code = "";
                self.brand = "";
                self.name = "";
                self.description = "";
                self.price = null;
                self.imagePaths = [];
            }
        },
        created() {
            if (this.id != "") {
                this.GetProduct();
            }
        }
    });
</script>