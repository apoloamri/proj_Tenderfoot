<div id="order">
    <?php $this->Partial("navigation") ?>
    <div id="mainContent">
        <div id="contactInformation" class="float-left">
            <a href="/">Home</a> ›
            <a href="/cart">Cart</a> › Order
            <h3>Contact Information</h3>
            <label class="red display-block">{{messages["PhoneNumber"]}}</label>
            <input type="text" v-model="phoneNumber" placeholder="Phone number" />
            <h3>Shipping Address</h3>
            <label class="red display-block">{{messages["LastName"]}}</label>
            <label class="red display-block">{{messages["FirstName"]}}</label>
            <input type="text" v-model="lastName" placeholder="Last name" class="short" />
            <input type="text" v-model="firstName" placeholder="First name" class="short float-right" />
            <label class="red display-block">{{messages["Address"]}}</label>
            <input type="text" v-model="address" placeholder="Complete address (Blk, Lot, Street, Subd, Bldg)" />
            <label class="red display-block">{{messages["Barangay"]}}</label>
            <input type="text" v-model="barangay" placeholder="Barangay" />
            <label class="red display-block">{{messages["City"]}}</label>
            <label class="red display-block">{{messages["PostalCode"]}}</label>
            <input type="text" v-model="city" placeholder="City" class="short"  />
            <input type="text" v-model="postalCode" placeholder="Postal code" class="short float-right"  />
            <h3>Payment Method</h3>
            <p>
                Payment method will be 'Cash on Delivery' only. Please pay for the amount dictated from the cart summary at the right side when the item is delivered to the address indicated.<br/><br/>
                <input type="checkbox" id="agree" onchange="document.getElementById('continue').disabled = !this.checked;" /><label for="agree">I've read the statement above and understood. I also deem to have read the terms of use and various policies and agreed to the content.</label><br/>
            </p>
            <center><button v-on:click="PostOrder();" id="continue" disabled>Continue</button></center>
        </div>
        <div id="cartInformation" class="float-right">
            <center>
                <table id="cartTable">
                    <tr>
                        <th width="10%"></th>
                        <th width="60%">Item brand / name</th>
                        <th width="30%">Price</th>
                    </tr>
                    <tr v-for="item in result">
                        <td><div class="itemImage-50" v-bind:style="'background-image: url(' + item.str_path + ')'"></div></td>
                        <td><a v-bind:href="'/detail/' + item.str_code">{{item.str_brand}} - {{item.str_name}}</a></td>
                        <td>₱{{item.dbl_price}} x {{item.num_amount}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="padding:20px;">Total price:</td>
                        <td>₱{{total}}</td>
                    </tr>
                </table>
            </center>
        </div>
    </div>
</div>

<style>
    p {
        text-align: justify;
    }

    .short {
        width: 218px !important;
    }

    #contactInformation,
    #cartInformation {
        width: 50% !important;
    }
    #contactInformation input {
        margin: 5px;
        width: 469px;
        display: inline-block !important;
    }
    #contactInformation input[type="checkbox"] {
        width: 15px;
    }
</style>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#mainContent",
        data: { 
            result: [],
            count: 0,
            total: 0,
            phoneNumber: "",
            lastName: "",
            firstName: "",
            address: "",
            barangay: "",
            city: "",
            postalCode: "",
            messages: []
        },
        methods: {
            GetCart: function () {
                var self = this;
                Lib.Get("/api/cart", null,
                function (success) {
                    self.result = success.Result;
                    self.count = success.Count;
                    self.total = success.Total;
                });
            },
            PostOrder: function () {
                var self = this;
                Lib.Post("/api/order", {
                    PhoneNumber: self.PhoneNumber,
                    lastName: self.LastName,
                    firstName: self.FirstName,
                    address: self.Address,
                    barangay: self.Barangay,
                    city: self.City,
                    postalCode: self.PostalCode
                },
                function (success) {
                    alert("Order complete.")
                },
                function (failed) {
                    var response = failed.responseJSON;
                    self.messages = response.Messages;
                });
            }
        },
        created() {
            this.GetCart();
        }
    });
</script>