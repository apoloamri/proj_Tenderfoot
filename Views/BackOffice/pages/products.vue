<template>
    <div>
        <div id="productWindow" class="wrapper">
            
            <div id="productWindow-header" class="innerWindow windowWrap">
                TEST
            </div>
        </div>
    </div>
</template>

<script>
import Draggable from "../js/draggable.js";
Vue.use(Draggable, "$draggable");
module.exports = {
    data() {
        return {
            someData: "HELLO WORLD"
        }
    },
    methods: {

    },
    mounted() {
        this.$draggable.dragElement(document.getElementById("productWindow"));
    }
}
</script>

<style>
.windowWrap {
    height: 100px;
    width: 100px;
    padding: 5px !important;
}
</style>
