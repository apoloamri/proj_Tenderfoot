<template>
    <div>
        {{someData}}
    </div>
</template>

<script>
module.exports = {
    name: "index",
    data() {
        return {
            someData: "HOME"
        }
    },
    methods: {
        member() {
            var self = this;
            axios
            .get("/api/v1/member", { params: self.$data, headers: { "Authorization": "Bearer " + Cookies.get("auth") } })
            .then(function (response) {
            });
        }
    },
    created() {
        this.member();
    }
}
</script>