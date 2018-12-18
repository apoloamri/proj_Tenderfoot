<?php $this->Partial("menu") ?>
<?php $this->Partial("categories") ?>
<div id="mainContent">
    <div id="breadCrumbs">
        <a href="/">Home</a> ›
        <?php echo $this->Result->str_name; ?>
    </div>
    <div id="details">
        <hr/>
        <div id="detailsImagesDiv">
            <img id="mainImage" class="image" src="<?php echo $this->Result->ImagePaths[0]; ?>" /><br/>
            <?php foreach ($this->Result->ImagePaths as $paths) { ?>
                <img class="childImages" src="<?php echo $paths ?>" />
            <?php } ?>
        </div>
        <div id="detailsContent">
            <h2><?php echo $this->Result->str_name; ?></h2>
            <h3><?php echo $this->Result->str_brand; ?></h3>
            <h1>₱<?php echo $this->Result->dbl_price; ?></h1>
            <p><?php echo $this->Result->txt_description; ?></p>
            <?php if ($this->Result->int_amount != null && $this->Result->int_amount != 0) { ?>
                <button v-on:click="PostCart('<?php echo $this->Result->str_code; ?>')">Add to cart</button>
            <?php } else { ?>
                <button disabled>Out of stock</button>
            <?php } ?>
        </div>
    </div>
</div>
<script src="/Resources/js/front/detail.js" async></script>
<?php $this->Partial("footer") ?>