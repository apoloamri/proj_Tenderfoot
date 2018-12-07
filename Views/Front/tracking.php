<?php $this->Partial("menu") ?>
<?php $this->Partial("categories") ?>
<div id="mainContent">
    <div id="breadCrumbs">
        <a href="/">Home</a> › 
        Tacking: <?php echo $this->URI[2]; ?>
    </div>
    <hr/>
    <div id="tracking">
        <div>
            <center>
                <h1><?php echo $this->Result->str_order_status ?></h1>
                <br/>
                <img src="/Resources/images/tracking-1<?php if ($this->Result->str_order_status == OrderStatus::NewOrder) echo "-active" ?>.png" />
                <div class="progress">🡆 🡆</div>
                <img src="/Resources/images/tracking-2<?php if ($this->Result->str_order_status == OrderStatus::Processed) echo "-active" ?>.png" />
                <div class="progress">🡆 🡆</div>
                <img src="/Resources/images/tracking-3<?php if ($this->Result->str_order_status == OrderStatus::OnDelivery) echo "-active" ?>.png" />
                <div class="progress">🡆 🡆</div>
                <img src="/Resources/images/tracking-4<?php if ($this->Result->str_order_status == OrderStatus::Delivered) echo "-active" ?>.png" />
            </center>
        </div>
    </div>
    <div id="cartInformation">
        <center>
            <table class="cartTable">
                <tr>
                    <th width="10%"></th>
                    <th width="60%">Item brand / name</th>
                    <th width="30%">Price</th>
                </tr>
                <?php foreach ($this->CartItems as $item) { ?>
                <tr>
                    <td><div class="cartImage" style="background-image:url('<?php echo $item->str_path ?>')"></div></td>
                    <td><a href="/detail/<?php echo $item->str_code ?>"><?php echo $item->str_brand ?> - <?php echo $item->str_name ?></a></td>
                    <td>₱<?php echo $item->dbl_price ?> x <?php echo $item->int_amount ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td></td>
                    <td>Total price:</td>
                    <td>₱<?php echo $this->Result->dbl_total ?></td>
                </tr>
            </table>
        </center>
    </div>
    <div id="contactInformation"> 
        <h3>Delivery Address</h3>
        <p>
            <?php echo $this->Result->str_address ?>, 
            <?php echo $this->Result->str_barangay ?>, 
            <?php echo $this->Result->str_city ?>, 
            <?php echo $this->Result->str_postal ?>
        </p>
    </div>
</div>
<?php $this->Partial("footer") ?>