<div id="item">
    <?php $this->Partial("navigation") ?>
    <div id="mainContent">
        <a href="/">Home</a> ›
        <a href="/"><?php echo $this->URI[2]; ?></a>
        <hr>
        <div id="itemImageDetail" class="float-left" style="background-image: url('<?php echo $this->result["str_image_url"]; ?>')"></div>
        <div id="itemDetail" class="float-left">
            <h2>
                <?php echo $this->result["str_brand"]; ?> -
                <?php echo $this->result["str_name"]; ?>
            </h2>
            <h3>₱<?php echo $this->result["dbl_price"]; ?></h3>
            <p><?php echo $this->result["str_description"]; ?></p>
            <button>Add to cart</button>
        </div>
    </div>
</div>