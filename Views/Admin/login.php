<div id="adminLogin">
    <center>    
        <div class="levitateDiv">
            <div id="shopPinLogo"></div>
            <input type="text" v-model="Username" placeholder="Username" />
            <label class="red">{{Messages["Username"]}}</label>
            <input type="password" v-model="Password" placeholder="Password" />
            <label class="red">{{Messages["Password"]}}</label>
            <button v-on:click="Login">Log in</button>
            <input type="checkbox" value="Test" />Remember me?
        </div>
    </center>
</div>

<style>
    #shopPinLogo {
        background: url("/Resources/images/shoppin-logo.png");
        background-size: contain;
        background-repeat: no-repeat;
        height: 100px;
        width: 250px;
        margin: 15px;
    }
</style>
<script src="/Resources/js/admin/login.js" async></script>