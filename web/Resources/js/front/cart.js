cart = new Vue({
    el: "#mainContent",
    data: { 
        Result: [],
        Count: 0,
        Total: 0,
    },
    methods: {
        PutCart(itemCode, amount) {
            axios.put("/api/cart", {
                Code: itemCode,
                Amount: amount
            });
            menu.GetCart();
        },
        DeleteCart(itemCode) {
            axios.delete("/api/cart", {
                params: { 
                    Code: itemCode
                }
            });
            menu.GetCart();
        }
    }
});