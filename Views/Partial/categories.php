<div id="categories">
    <div class="content">
        <div class="header"><img src="/Resources/images/tracking.png" class="menuImages" />Track Order</div>
        <input type="text" placeholder="Tracking number here..." v-model="Tracking" v-on:keyup.enter="GetTracking" />
    </div>
    <div class="content">
        <div class="header"><img src="/Resources/images/categories.png" class="menuImages" />Categories</div>
        <div class="links" v-for="item in Categories">
            <a v-bind:href="'/?tag=' + item.str_tag">{{item.str_tag}}</a>
        </div>
    </div>
</div>
<script src="/Resources/js/partial/categories.js" async></script>