<?php $this->Partial("menu") ?>
<?php $this->Partial("categories") ?>
<div id="mainContent">
    <div id="details">
        <a href="/">Home</a> ›
        <?php echo $this->Result->str_name; ?>
        <hr/>
        <div id="detailsImage" style="background-image: url('<?php echo $this->Result->ImagePaths[0]; ?>')"></div>
        <div id="detailsContent">
            <h2><?php echo $this->Result->str_name; ?></h2>
            <h3><?php echo $this->Result->str_brand; ?></h3>
            <h1>₱<?php echo $this->Result->dbl_price; ?></h1>
            <p><?php echo $this->Result->txt_description; ?></p>
            <?php if ($this->Result->int_amount != null && $this->Result->int_amount != 0) { ?>
                <button v-on:click="AddCart();">Add to cart</button>
            <?php } else { ?>
                <button disabled>Out of stock</button>
            <?php } ?>
        </div>
    </div>
</div>
<?php $this->Partial("footer") ?>

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