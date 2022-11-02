<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="row">
    <div class="col-2">

    </div>
    <div class="col-8" id="slider" style='background-image: url("<?= $this->Url->build(' /') ?>img/bg2.jpg");background-size:cover;background-repeat: no-repeat;height:30vh'>
        <div class="row mt-5 pt-5">
            <div class="col-3"></div>
            <div class="col-2">
                <select name="" id="" class="form-control" style="width: 100%;
        height: 56px;
        padding: 0px 40px 0px 15px;
        border: 1px solid #D4E7FE;
        border-radius: 5px;
        color: #002678;
        font-weight: 500;outline: none;margin: 0;
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;box-sizing: border-box;    outline-offset: 0px;     text-rendering: auto;  letter-spacing: normal;
        word-spacing: normal; text-transform: none;
        text-indent: 0px;
        text-shadow: none;
        display: inline-block;
        text-align: start;
        appearance: auto;
        -webkit-rtl-ordering: logical;
        cursor: text;
        background-color: field;">
                    <option value="">Buyer</option>
                    <option value="">Seller</option>
                </select>
            </div>
            <div class="col-3">
                <input type="text" class="global" name="common_search" id="search-blk"
                    placeholder="What are you looking for?" style="width: 100%;
        height: 56px;
        padding: 0px 40px 0px 15px;
        border: 1px solid #D4E7FE;
        border-radius: 5px;
        color: #002678;
        font-weight: 500;outline: none;margin: 0;
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;box-sizing: border-box;    outline-offset: 0px;     text-rendering: auto;  letter-spacing: normal;
        word-spacing: normal; text-transform: none;
        text-indent: 0px;
        text-shadow: none;
        display: inline-block;
        text-align: start;
        appearance: auto;
        -webkit-rtl-ordering: logical;
        cursor: text;
        background-color: field;">
            </div>
            <div class="col-3">
                <a href="#"
                    class="button button-rounded button-reveal button-large button-yellow button-light text-end">
                    <i class="icon-search"></i><span>Search</span>
                </a>
            </div>
        </div>  
    </div>
    <div class="col-2">

    </div>
</div>

<br>
<section>
    <div class="container clearfix">
        <div class="row" style="justify-content:center;">
            <div class="col-1 mx-2">
                <div class="owl-item" style="max-width:90px;">
                    <div class="portfolio-item">
                        <div class="portfolio-image">
                            <a href="#">
                                <img src="<?= $this->Url->build('/') ?>img/cat/electrical.png" alt="Open Imagination">
                            </a>
                        </div>
                        <div class="portfolio-desc">
                            <h3><a href="#">Electrical</a></h3>
                            <!-- <span><a href="#">Hard Disk</a></span> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1 mx-2">
                <div class="owl-item" style="max-width:90px;">
                    <div class="portfolio-item">
                        <div class="portfolio-image">
                            <a href="#">
                                <img src="<?= $this->Url->build('/') ?>img/cat/devices.png" alt="Open Imagination">
                            </a>
                        </div>
                        <div class="portfolio-desc">
                            <h3><a href="#">Electronics</a></h3>
                            <!-- <span><a href="#">Hard Disk</a></span> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1 mx-2">
                <div class="owl-item" style="max-width:90px;">
                    <div class="portfolio-item">
                        <div class="portfolio-image">
                            <a href="#">
                                <img src="<?= $this->Url->build('/') ?>img/cat/drug.png" alt="Open Imagination">
                            </a>
                        </div>
                        <div class="portfolio-desc">
                            <h3><a href="#">Pharmacy</a></h3>
                            <!-- <span><a href="#">Hard Disk</a></span> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1 mx-2">
                <div class="owl-item" style="max-width:90px;">
                    <div class="portfolio-item">
                        <div class="portfolio-image">
                            <a href="#">
                                <img src="<?= $this->Url->build('/') ?>img/cat/engineering.png" alt="Open Imagination">
                            </a>
                        </div>
                        <div class="portfolio-desc">
                            <h3><a href="#">Civil</a></h3>
                            <!-- <span><a href="#">Hard Disk</a></span> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1 mx-2">
                <div class="owl-item" style="max-width:90px;">
                    <div class="portfolio-item">
                        <div class="portfolio-image">
                            <a href="#">
                                <img src="<?= $this->Url->build('/') ?>img/cat/metal.png" alt="Open Imagination">
                            </a>
                        </div>
                        <div class="portfolio-desc">
                            <h3><a href="#">Metal</a></h3>
                            <!-- <span><a href="#">Hard Disk</a></span> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1 mx-2">
                <div class="owl-item" style="max-width:90px;">
                    <div class="portfolio-item">
                        <div class="portfolio-image">
                            <a href="#">
                                <img src="<?= $this->Url->build('/') ?>img/cat/nut.png" alt="Open Imagination">
                            </a>
                        </div>
                        <div class="portfolio-desc">
                            <h3><a href="#">Nut</a></h3>
                            <!-- <span><a href="#">Hard Disk</a></span> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <!-- <h1 style="margin-top:3rem;text-align-last: center;color: #0a5078;">QUICK LINKS</h1> -->
    <div class="row p-0 m-0 text-center">
        <div class="col-2"><img class="login" src="<?= $this->Url->build('/') ?>img/button/1.png" style="width: 200px;">
        </div>
        <div class="col-2"><img class="login" src="<?= $this->Url->build('/') ?>img/button/2.png" style="width: 200px;">
        </div>
        <div class="col-2"><img class="login" src="<?= $this->Url->build('/') ?>img/button/3.png" style="width: 200px;">
        </div>
        <div class="col-2"><img class="login" src="<?= $this->Url->build('/') ?>img/button/4.png" style="width: 200px;">
        </div>
        <div class="col-2"><img class="login" src="<?= $this->Url->build('/') ?>img/button/5.png" style="width: 200px;">
        </div>
        <div class="col-2"><img class="login" src="<?= $this->Url->build('/') ?>img/button/6.png" style="width: 200px;">
        </div>
    </div>
</section>