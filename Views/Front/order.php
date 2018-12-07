<?php $this->Partial("menu") ?>
<?php $this->Partial("categories") ?>
<div id="mainContent">
    <div id="breadCrumbs">
        <a href="/">Home</a> ›
        <a href="/cart">Cart</a> › Order
    </div>
    <hr/>
    <div id="cartInformation">
        <center>
            <table class="cartTable">
                <tr>
                    <th width="10%"></th>
                    <th width="60%">Item brand / name</th>
                    <th width="30%">Price</th>
                </tr>
                <tr v-for="item in result">
                    <td><div class="cartImage" v-bind:style="'background-image: url(' + item.str_path + ')'"></div></td>
                    <td><a v-bind:href="'/detail/' + item.str_code">{{item.str_brand}} - {{item.str_name}}</a></td>
                    <td>₱{{item.dbl_price}} x {{item.int_amount}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Total price:</td>
                    <td>₱{{total}}</td>
                </tr>
            </table>
        </center>
    </div>
    <div id="contactInformation"> 
        <p>Text boxes with asterisks are required inputs. Please dictate each accurately and exact as possible.</p>
        <h3>Contact Information</h3>
        <label class="red message">{{messages["PhoneNumber"]}}</label>
        <input type="text" v-model="phoneNumber" placeholder="Phone number" /><label class="red">*</label><br/>
        <label class="red message">{{messages["Email"]}}</label>
        <input type="text" v-model="email" placeholder="Email Address" /><label class="red">*</label><br/>
        <h3>Shipping Address</h3>
        <label class="red message">{{messages["LastName"]}}</label>
        <input type="text" v-model="lastName" placeholder="Last name" class="short" /><label class="red">*</label><br/>
        <label class="red message">{{messages["FirstName"]}}</label>
        <input type="text" v-model="firstName" placeholder="First name" class="short" /><label class="red">*</label><br/>
        <label class="red message">{{messages["Address"]}}</label>
        <input type="text" v-model="address" placeholder="Complete address (Blk, Lot, Street, Subd, Bldg)" /><label class="red">*</label><br/>
        <label class="red message">{{messages["Barangay"]}}</label>
        <input type="text" v-model="barangay" placeholder="Barangay" />
        <label class="red message">{{messages["City"]}}</label>
        <input type="text" v-model="city" placeholder="City" class="short"  /><label class="red">*</label><br/>
        <label class="red message">{{messages["PostalCode"]}}</label>
        <input type="text" v-model="postalCode" placeholder="Postal code" class="short"  />
        <h3>Payment Method</h3>
        <p>
            Payment method will be 'Cash on Delivery' only. Please pay for the amount dictated from the cart summary at the right side when the item is delivered to the address indicated.<br/><br/>
            <input type="checkbox" id="agree" onchange="document.getElementById('continue').disabled = !this.checked;" /><label for="agree">I've read the statement above and understood. I also deem to have read the terms of use and various policies and agreed to the content.</label><br/>
        </p>
        <center><button v-on:click="PostOrder();" id="continue" disabled>Continue</button></center>
    </div>
    <div id="shadowModal" class="modal"></div>
    <div id="completeModal" class="modal">
        <center><h2>Order Completed!</h2></center>
        <p>Congratulations {{lastName}}, {{firstName}}!</p>
        <p>Your order is now being processed. Please wait for a text message for your order fulfillment and shipping status.</p>
        <p>Your order number: <b>{{orderNumber}}</b>. When there are problems, please use this order number when you contact us.</p>
        <center><button onclick="window.location='/';">Return to homepage.</button></center>
    </div>
</div>
<?php $this->Partial("footer") ?>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#mainContent",
        data: { 
            result: [],
            count: 0,
            total: 0,
            orderNumber: "",
            phoneNumber: "",
            email: "",
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
                Lib.InitialLoading(true);
                Lib.Post("/api/orders", {
                    PhoneNumber: self.phoneNumber,
                    Email: self.email,
                    LastName: self.lastName,
                    FirstName: self.firstName,
                    Address: self.address,
                    Barangay: self.barangay,
                    City: self.city,
                    PostalCode: self.postalCode
                },
                function (success) {
                    self.orderNumber = success.OrderNumber;
                    Lib.InitialLoading(false);
                    $(".modal").show();
                },
                function (failed) {
                    var response = failed.responseJSON;
                    self.messages = response.Messages;
                    Lib.InitialLoading(false);
                });
            }
        },
        created() {
            this.GetCart();
        }
    });
</script>