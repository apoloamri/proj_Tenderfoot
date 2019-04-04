<template>
    <div id="register">
        {{messages["username"]}}
        <input type="text" v-model="username" placeholder="Username"><br>
        {{messages["password"]}}
        <input type="password" v-model="password" placeholder="Password"><br>
        {{messages["confirmPassword"]}}
        <input type="password" v-model="confirmPassword" placeholder="Repeat Password"><br>
        {{messages["emailAddress"]}}
        <input type="text" v-model="emailAddress" placeholder="Email Address"><br>
        {{messages["storeName"]}}
        <input type="text" v-model="storeName" placeholder="Store Name"><br>
        <button v-on:click="register">Register</button>
    </div>
</template>

<script>
module.exports = {
    name: "register",
    data() {
        return {
            username: "",
            password: "",
            confirmPassword: "",
            emailAddress: "",
            storeName: "",
            messages: []
        }
    },
    methods: {
        register() {
            var self = this;
            axios
            .post("/api/v1/member/register", self.$data)
            .then(function () {
                router.push("login");
            })
            .catch(function (error) {
                self.messages = error.response.data.messages;
            });
        }
    }
}
</script>