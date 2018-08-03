<div id="create">
    <input type="text" v-model="AssetTag">
    <input type="text" v-model="Model">
    <input type="text" v-model="Status">
    <input type="text" v-model="AssetName">
    <button v-on:click="Submit();">Submit</button>
</div>

<script type="module">
    import Common from "/Resources/js/script.js";
    new Vue({
        el: "#create",
        data: {
            AssetTag: "",
            Model: "",
            Status: "",
            AssetName: ""
        },
        methods: {
            Submit: function () {
                var self = this;
                Common.Post("/assets",
                {
                    "AssetTag": self.AssetTag,
                    "Model": self.Model,
                    "Status": self.Status,
                    "AssetName": self.AssetName,
                },
                function (success) {
                    alert(success);
                },
                function (error) {
                    alert(error);
                });
            }
        }
    });
</script>