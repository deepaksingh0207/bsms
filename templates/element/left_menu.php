
<li class="menu-item"><a class="menu-link" href="<?= $this->Url->build('/') ?>dealer/dashboard/">
        <div>Dashboard</div>
    </a>
</li>
<li class="menu-item sub-menu">
    <a class="menu-link" href="#" style="padding-top: 39px; padding-bottom: 39px;">
        <div>Deals<i class="icon-angle-down"></i></div>
    </a>
    <ul class="sub-menu-container">
        <li class="menu-item">
            <a class="menu-link" href="<?= $this->Url->build('/') ?>dealer/addproduct/buyer">
                <div>
                    <i class="icon-wpforms"></i>
                    Want To Buy
                </div>
            </a>
        </li>
        <li class="menu-item">
            <a class="menu-link" href="<?= $this->Url->build('/') ?>dealer/addproduct/seller">
                <div>
                    <i class="icon-wpforms"></i>
                    Want To Sell
                </div>
            </a>
        </li>
    </ul>
</li>
<li class="menu-item sub-menu">
    <a class="menu-link" href="#" style="padding-top: 39px; padding-bottom: 39px;">
        <div>Product<i class="icon-angle-down"></i></div>
    </a>
    <ul class="sub-menu-container">
        <li class="menu-item">
            <a class="menu-link" href="#">
                <div>
                    <i class="icon-wpforms"></i>
                    Stock For Sell
                </div>
            </a>
        </li>
        <li class="menu-item">
            <a class="menu-link" href="#">
                <div>
                    <i class="icon-wpforms"></i>
                    Buying Requirements
                </div>
            </a>
        </li>
    </ul>
</li>
<li class="menu-item">
    <a class="menu-link" href="<?= $this->Url->build('/') ?>dealer/logout">
        <div>Logout</div>
    </a>
</li>