import Lib from "/Resources/js/lib.js";
export default {
    AddCart: function (itemCode) {
        Lib.Post("/api/shop/cart", {
            "itemCode": itemCode,
            "sessionId": Lib.GetCookie("session_id")
        });
    }
}