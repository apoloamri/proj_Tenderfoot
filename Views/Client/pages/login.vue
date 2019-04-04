<template>
    <div id="login">
        {{messages["username"]}}
        <input type="text" v-model="username" placeholder="Username"><br>
        {{messages["password"]}}
        <input type="password" v-model="password" placeholder="Password"><br>
        <button v-on:click="login">Login</button>
    </div>
</template>

<script>
module.exports = {
    name: "login",
    data() {
        return {
            username: "",
            password: "",
            messages: []
        }
    },
    methods: {
        login() {
            var self = this;
            axios
            .post("/api/v1/member/login", self.$data)
            .then(function (response) {
                Cookies.set("auth", response.data.token);
                router.push("/");
            })
            .catch(function (error) {
                self.messages = error.response.data.messages;
            });
        }
    }
}
</script>