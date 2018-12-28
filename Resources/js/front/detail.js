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
$(document).ready(function(){
    $('.slickImages').slick({
        dots: true,
        infinite: true,
        speed: 300,
        cssEase: 'linear'
    });
});