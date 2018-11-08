import Lib from "/Resources/js/lib.js";
export default {
    AddCart: function (itemCode) {
        Lib.Post("/api/cart", {
            "Code": itemCode
        },
        function (success) {
            window.location = "/cart";
        });
    }
}