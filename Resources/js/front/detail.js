var order = new Vue({
    el: "#mainContent",
    methods: {
        Menu() { return menu; },
        PostCart(itemCode) {
            this.Menu().PostCart(itemCode);
            window.location = "/cart";
        }
    }
});