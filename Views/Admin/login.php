<div id="adminLogin">
    <center>    
        <div class="levitateDiv">
            <div id="shopPinLogo"></div>
            <input type="text" v-model="username" placeholder="Username" />
            <label class="red">{{messages["Username"]}}</label>
            <input type="password" v-model="password" placeholder="Password" />
            <label class="red">{{messages["Password"]}}</label>
            <button v-on:click="Login">Log in</button>
            <input type="checkbox" value="Test" />Remember me?
        </div>
    </center>
</div>

<style>
    #shopPinLogo {
        background: url("/Resources/images/shoppin-logo.png");
        background-size: contain;
        background-repeat: no-repeat;
        height: 100px;
        width: 250px;
        margin: 15px;
    }
</style>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    new Vue({
        el: "#adminLogin",
        data: { 
            username: "",
            password: "",
            messages: []
        },
        methods: {
            Login: function () {
                var self = this;
                Lib.InitialLoading(true);
                Lib.Post("/admin/api/login", {
                    "Username": self.username,
                    "Password": self.password
                },
                function (success) {
                    window.location = "/admin";
                },
                function (failed) {
                    var response = failed.responseJSON;
                    self.messages = response.Messages;
                    Lib.InitialLoading(false);
                });
            }
        }
    });
</script>