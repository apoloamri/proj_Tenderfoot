<?php $this->Partial("navigation") ?>
<div id="adminPages">
    <div id="adminContent">
        <div id="adminInnerContent">
            <div id="add">
                <h3><a href="/admin/products">◄ Products</a></h3>
                <h2><?php echo $this->PageTitle ?></h2>
                <div class="adminTable" v-if="Id != ''">
                    <h3>Inventory</h3>
                    <label>Amount</label>
                    <input type="number" v-model="Amount" placeholder="50" />
                    <button v-on:click="PutInventory" class="inline-block">Update</button>
                    <label class="red">{{Messages["Amount"]}}</label>
                </div>
                <div class="spacer-h-15"></div>
                <div class="adminTable">
                    <h3>Product</h3>
                    <label>Code</label>
                    <input type="text" v-model="Code" placeholder="SKU10001" />
                    <label class="red">{{Messages["Code"]}}</label>
                    <label>Brand</label>
                    <input type="text" v-model="Brand" placeholder="Microsoft" />
                    <label class="red">{{Messages["Brand"]}}</label>
                    <label>Name</label>
                    <input type="text" v-model="Name" placeholder="Surface Pro" />
                    <label class="red">{{Messages["Name"]}}</label>
                    <label>Description</label>
                    <textarea v-model="Description" placeholder="Unplug. Pack light. Get productive your way, all day, with the new Surface Pro 6 - now faster than ever with the latest 8th Gen Intel Core processor."></textarea>
                    <label class="red">{{Messages["Description"]}}</label>
                </div>
                <div class="spacer-h-15"></div>
                <div class="adminTable">
                    <h3>Pricing</h3>
                    <label>Price</label>
                    <input type="number" v-model="Price" placeholder="15,000.00" />
                    <label class="red">{{Messages["Price"]}}</label>
                </div>
                <div class="spacer-h-15"></div>
                <div class="adminTable">
                    <h3>Tags</h3>
                    <input type="text" v-model="Tags" placeholder="Separate Tags by comma." />
                    <label class="red">{{Messages["Tags"]}}</label>
                </div>
                <div class="spacer-h-15"></div>
                <div class="adminTable">
                    <h3>Images</h3>
                    <input type="file" v-on:change="UploadImage" id="image" />
                    <button v-on:click="ImagePaths = []" class="gray inline-block">Remove Images</button>
                    <label class="red">{{Messages["ImageFile"]}}</label>
                    <div v-for="image in ImagePaths" v-bind:style="{ 'background-image': 'url(' + image + ')' }" class="image size-100"></div>
                </div>
                <hr />
                <div class="float-right">
                    <div v-if="Id == ''" class="inline-block">
                        <button v-on:click="PostProducts" class="inline-block">Add product</button>
                    </div>
                    <div v-else class="inline-block">
                        <button v-on:click="PutProducts" class="inline-block">Update product</button>
                        <button v-on:click="DeleteProducts" class="red inline-block">Delete product</button>
                    </div>
                    <button v-on:click="Clear" class="gray inline-block">Clear</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/Resources/js/admin/products_add.js" async></script>