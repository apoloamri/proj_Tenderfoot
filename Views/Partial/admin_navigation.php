<div id="adminNavigation">
    <center><div id="shopPinLogo"></div></center>
    <label class="margin-15"><a href="/admin">Home</a></label>
    <label class="margin-15"><a href="/admin/orders">Orders</a></label>
    <label class="margin-15"><a href="/admin/products">Products</a></label>
    <label class="margin-15"><a href="/admin/customers">Customers</a></label>
    <label class="margin-15"><a href="/admin/analytics">Analytics</a></label>
    <label class="margin-15"><a href="/admin/store">Store</a></label>
    <label class="margin-15"><a v-on:click="Logout()" href="#">Logout</a></label>
</div>

<style>
    #shopPinLogo {
        background: url("/Resources/images/shoppin-logo.png");
        background-size: contain;
        background-repeat: no-repeat;
        height: 70px;
        width: 150px;
        margin-top: 25px;
        margin-bottom: -5px;
    }
</style>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#adminNavigation",
        methods: {
            Logout: function () {
                var self = this;
                Lib.Delete("/admin/api/logout", null,
                function (success) { });
                location.reload();
            }
        }
    });
</script>