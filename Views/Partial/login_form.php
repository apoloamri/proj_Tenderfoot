<div id="userLogin">
    <center>
        <h3>User login</h3>
        <input type="text" v-model="username" placeholder="Username / Email">
        <input type="password" v-model="password" placeholder="Password">
        <button v-on:click="Login();">Login</button>
    </center>
</div>

<script type="module">
import Lib from "/Resources/js/lib.js";
new Vue({
    el: "#userLogin",
    data: { 
        username: "",
        passowrd: ""
    },
    methods: {
        Login: function () {
            var self = this;
            Lib.Post("/api/session", {
                "username": self.username,
                "password": self.password,
                "session": Lib.GetCookie("session_id")
            }, function (success) {

            }, function (failed) {
                alert(failed);
            });
        }
    }
});
</script>