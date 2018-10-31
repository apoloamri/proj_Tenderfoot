<div id="item">
    <?php $this->Partial("navigation") ?>
    <div id="mainContent">
        <a href="/">Home</a> ›
        <a href="/"><?php echo $this->URI[2]; ?></a>
        <hr>
        <div id="itemImageDetail" class="float-left" style="background-image: url('<?php echo $this->Result->ImagePaths[0]; ?>')"></div>
        <div id="itemDetail" class="float-left">
            <h2>
                <?php echo $this->Result->str_brand; ?> -
                <?php echo $this->Result->str_name; ?>
            </h2>
            <h3>₱<?php echo $this->Result->dbl_price; ?></h3>
            <p style="width:440px;"><?php echo $this->Result->txt_description; ?></p>
            <button>Add to cart</button>
        </div>
    </div>
</div>