<div id="item">
    <?php $this->Partial("navigation") ?>
    <div id="mainContent">
        <a href="/">Home</a> ›
        <?php echo $this->URI[2]; ?>
        <hr>
        <div id="itemImageDetail" class="float-left" style="background-image: url('<?php echo $this->Result->ImagePaths[0]; ?>')"></div>
        <div id="itemDetail" class="float-left">
            <h2>
                <?php echo $this->Result->str_brand; ?> -
                <?php echo $this->Result->str_name; ?>
            </h2>
            <h3>₱<?php echo $this->Result->dbl_price; ?></h3>
            <p style="width:440px;"><?php echo $this->Result->txt_description; ?></p>
            <?php if ($this->Result->int_amount != null && $this->Result->int_amount != 0) { ?>
                <button v-on:click="AddCart();">Add to cart</button>
            <?php } else { ?>
                <button disabled>Out of stock</button>
            <?php } ?>
        </div>
    </div>
</div>

<style>
    #itemDetail {
        padding: 25px;
    }

    #itemImageDetail {
        width: 400px;
        height: 400px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        display: inline-block;
    }
</style>

<script type="module">
    import Lib from "/Resources/js/lib.js";
    import Common from "/Resources/js/common.js";
    new Vue({
        el: "#mainContent",
        methods: {
            AddCart: function () {
                Common.AddCart("<?php echo $this->Result->str_code; ?>");
            }
        }
    });
</script>