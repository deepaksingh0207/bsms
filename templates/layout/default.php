<?php $cakeDescription = 'Supplier Management'; ?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb" />
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <link
        href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Poppins:300,400,500,600,700|PT+Serif:400,400i&display=swap"
        rel="stylesheet" type="text/css" />
    <?= $this->Html->css(['normalize.min', 'cake', 'bootstrap', 'style', 'dark', 'font-icons', 'animate', 'magnific-popup', 'custom', 'settings', 'layers', 'navigation', 'custom']) ?>
    <script>
        var baseUrl = '<?= $this->Url->build(' / ') ?>';
    </script>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body class="stretched">
    <div id="wrapper" class="clearfix">
        <!-- Top Bar		============================================= -->
        <?php if(isset($logged_in)) : ?>
        <?php else : ?>
        <div id="top-bar">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-md-auto">
                        <p class="mb-0 py-2 text-center text-md-start"><strong>Call:</strong> 9876543210 |
                            <strong>Email:</strong> support@fts-pl.com
                        </p>
                    </div>
                    <div class="col-12 col-md-auto">
                        <!-- Top Links						============================================= -->
                        <div class="top-links on-click">
                            <ul class="top-links-container">
                                <li class="top-links-item"><a href="#">Login</a>
                                    <div class="top-links-section">
                                        <?= $this->Flash->render('auth') ?>
                                        <?= $this->Form->create() ?>
                                        <div class="form-group">
                                            <?= $this->Form->control('username', [
                                                'label' => 'USERNAME',
                                                'type' => 'text',
                                                'class' => 'form-control',
                                            ]) ?>
                                        </div>
                                        <div class="form-group">
                                            <?= $this->Form->control('password', [
                                                'label' => 'PASSWORD',
                                                'type' => 'password',
                                                'class' => 'form-control',
                                            ]) ?>
                                        </div>
                                        <?= $this->Form->button(__('Login'), [
                                            'label' => 'Login',
                                            'class' => 'btn btn-danger w-100',
                                        ]); ?>
                                        <?= $this->Form->end() ?>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>

        <header id="header">
            <div id="header-wrap">
                <div class="container">
                    <div class="header-row">
                        <div id="logo">
                            <a href="/" class="standard-logo" data-dark-logo="../webroot/images/logo-dark.png">
                                <img src="<?= $this->Url->build('/') ?>webroot/img/logo.png" alt="ftspl" style="max-height:9vh;">
                            </a>
                            <a href="/" class="retina-logo" data-dark-logo="../webroot/images/logo-dark@2x.png">
                                <img src="../webroot/images/logo@2x.png" alt="ftspl Logo">
                            </a>
                        </div>
                        <div id="primary-menu-trigger">
                            <svg class="svg-trigger" viewBox="0 0 100 100">
                                <path
                                    d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20">
                                </path>
                                <path d="m 30,50 h 40"></path>
                                <path
                                    d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20">
                                </path>
                            </svg>
                        </div>
                        <nav class="primary-menu">
                            <ul class="menu-container">
                                <?php if(isset($logged_in)) : ?>
                                <?php echo $this->element('left_menu'); ?>
                                <?php else : ?>
                                <li class="menu-item current"><a class="menu-link" href="#">
                                        <div>Home</div>
                                    </a>
                                </li>
                                <li class="menu-item"><a class="menu-link" href="#">
                                        <div>Services</div>
                                    </a>
                                </li>
                                <li class="menu-item"><a class="menu-link" href="#">
                                        <div>About Us</div>
                                    </a>
                                </li>
                                <li class="menu-item"><a class="menu-link" href="#">
                                        <div>Contact</div>
                                    </a>
                                </li>
                                <?php endif ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="header-wrap-clone"></div>
        </header>

        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>

        <!-- Footer		============================================= -->
        <footer id="footer" class="dark">
            <div class="container">
                <div class="footer-widgets-wrap">
                    <div class="row col-mb-50">
                        <div class="col-lg-8">
                            <div class="row col-mb-50">
                                <div class="col-md-4" style="align-self: center;">
                                    <div class="widget clearfix">
                                        <img src="../webroot/img/w_logo.png" alt="Image" class="footer-logo">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget widget_links clearfix">
                                        <address>
                                            <strong>Address:</strong><br>
                                            401, Meet Galaxy, Trimurti Lane <br>Behind Tip Top Plaza, <br>Teen Hath Naka, Thane, <br>Maharashtra - 400604
                                        </address>
                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget clearfix">
                                        <abbr title="Phone Number"><strong>Phone:</strong></abbr> (+91) 083693 90146<br>
                                        <abbr title="Email Address"><strong>Email:</strong></abbr> support@fts-pl.com
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="row col-mb-50">
                                <div class="col-md-4 col-lg-12">
                                    <div class="widget clearfix" style="margin-bottom: -20px;">
                                        <div class="row">
                                            <div class="col-lg-6 bottommargin-sm">
                                                <div class="counter counter-small"><span data-from="50"
                                                        data-to="15065421" data-refresh-interval="80" data-speed="3000"
                                                        data-comma="true"></span></div>
                                                <h5 class="mb-0">Total Buyers</h5>
                                            </div>
                                            <div class="col-lg-6 bottommargin-sm">
                                                <div class="counter counter-small"><span data-from="100" data-to="18465"
                                                        data-refresh-interval="50" data-speed="2000"
                                                        data-comma="true"></span></div>
                                                <h5 class="mb-0">Sellers</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-5 col-lg-12">
                                    <div class="widget subscribe-widget clearfix">
                                        <h5><strong>Subscribe</strong> to Our Newsletter to get Important News, Amazing
                                            Offers &amp; Inside Scoops:</h5>
                                        <div class="widget-subscribe-form-result"></div>
                                        <form id="widget-subscribe-form" action="include/subscribe.php" method="post"
                                            class="mb-0">
                                            <div class="input-group mx-auto">
                                                <div class="input-group-text"><i class="icon-email2"></i></div>
                                                <input type="email" id="widget-subscribe-form-email"
                                                    name="widget-subscribe-form-email"
                                                    class="form-control required email" placeholder="Enter your Email">
                                                <button class="btn btn-success" type="submit">Subscribe</button>
                                            </div>
                                        </form>
                                    </div>
                                </div> -->
                                <div class="col-md-3 col-lg-12">
                                    <div class="widget clearfix" style="margin-bottom: -20px;">
                                        <div class="row">
                                            <div class="col-6 col-md-12 col-lg-6 clearfix bottommargin-sm">
                                                <a href="#" class="social-icon si-dark si-colored si-facebook mb-0"
                                                    style="margin-right: 10px;">
                                                    <i class="icon-facebook"></i>
                                                    <i class="icon-facebook"></i>
                                                </a>
                                                <a href="#"><small style="display: block; margin-top: 3px;"><strong>Like
                                                            us</strong><br>on Facebook</small></a>
                                            </div>
                                            <div class="col-6 col-md-12 col-lg-6 clearfix">
                                                <a href="#" class="social-icon si-dark si-colored si-rss mb-0"
                                                    style="margin-right: 10px;">
                                                    <i class="icon-rss"></i>
                                                    <i class="icon-rss"></i>
                                                </a>
                                                <a href="#"><small
                                                        style="display: block; margin-top: 3px;"><strong>Subscribe</strong><br>to
                                                        RSS Feeds</small></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Copyrights			============================================= -->
            <div id="copyrights">
                <div class="container">
                    <div class="row col-mb-30">
                        <div class="col-md-6 text-center text-md-start">
                            Copyrights &copy; 2020 All Rights Reserved by ftspl Inc.<br>
                            <div class="copyright-links"><a href="https://www.fts-pl.com/privacy-policy/">Terms of Use</a> / <a href="https://www.fts-pl.com/privacy-policy/">Privacy Policy</a>
                            </div>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="d-flex justify-content-center justify-content-md-end">
                                <a href="#" class="social-icon si-small si-borderless si-facebook">
                                    <i class="icon-facebook"></i>
                                    <i class="icon-facebook"></i>
                                </a>
                                <a href="#" class="social-icon si-small si-borderless si-twitter">
                                    <i class="icon-twitter"></i>
                                    <i class="icon-twitter"></i>
                                </a>
                                <a href="#" class="social-icon si-small si-borderless si-gplus">
                                    <i class="icon-gplus"></i>
                                    <i class="icon-gplus"></i>
                                </a>
                                <a href="#" class="social-icon si-small si-borderless si-pinterest">
                                    <i class="icon-pinterest"></i>
                                    <i class="icon-pinterest"></i>
                                </a>
                                <a href="#" class="social-icon si-small si-borderless si-vimeo">
                                    <i class="icon-vimeo"></i>
                                    <i class="icon-vimeo"></i>
                                </a>
                                <a href="#" class="social-icon si-small si-borderless si-github">
                                    <i class="icon-github"></i>
                                    <i class="icon-github"></i>
                                </a>
                                <a href="#" class="social-icon si-small si-borderless si-yahoo">
                                    <i class="icon-yahoo"></i>
                                    <i class="icon-yahoo"></i>
                                </a>
                                <a href="#" class="social-icon si-small si-borderless si-linkedin">
                                    <i class="icon-linkedin"></i>
                                    <i class="icon-linkedin"></i>
                                </a>
                            </div>
                            <div class="clear"></div>
                            <i class="icon-envelope2"></i> support@fts-pl.com <span class="middot">&middot;</span> <i
                                class="icon-headphones"></i> +91 9876543210 <span class="middot">&middot;</span> <i
                                class="icon-skype2"></i> ftsplOnSkype
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <div id="gotoTop" class="icon-angle-up"></div>
    <?= $this->Html->script(['jquery', 'plugins.min', 'functions', 'jquery.themepunch.tools.min', 'jquery.themepunch.revolution.min', 'extensions/revolution.extension.video.min', 'extensions/revolution.extension.slideanims.min', 'extensions/revolution.extension.actions.min', 'extensions/revolution.extension.layeranimation.min', 'extensions/revolution.extension.kenburn.min', 'extensions/revolution.extension.navigation.min', 'extensions/revolution.extension.migration.min', 'extensions/revolution.extension.parallax.min', 'common', 'custom']) ?>
</body>

</html>