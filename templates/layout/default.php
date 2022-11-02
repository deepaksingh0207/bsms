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
	<?= $this->Html->css(['cake', 'bootstrap', 'style', 'dark', 'font-icons', 'animate', 'magnific-popup', 'custom', 'settings', 'layers', 'navigation', 'custom']) ?>
	<script>
		var baseUrl = '<?= $this->Url->build(' / ') ?>';
	</script>
	<?= $this->fetch('meta') ?>
	<?= $this->fetch('css') ?>
	<?= $this->fetch('script') ?>
	<?= $this->Html->script(['jquery']) ?>
</head>

<body class="stretched">
	<div id="wrapper" class="clearfix">

		<header id="header" class="full-header dark">
			<div id="header-wrap">
				<div class="container">
					<div class="header-row">

						<div id="logo">
							<a href="<?= $this->Url->build('/') ?>" class="standard-logo"
								data-dark-logo="<?= $this->Url->build('/') ?>/img/logo_white.png"><img
									src="<?= $this->Url->build('/') ?>img/logo.png" alt="ftspl"
									style="max-height:9vh;"></a>
							<a href="index.html" class="retina-logo" data-dark-logo="img/logo_white.png"><img
									src="<?= $this->Url->build('/') ?>img/logo.png" alt="ftspl"
									style="max-height:9vh;"></a>
						</div>


						<nav class="primary-menu">
							<ul class="menu-container">

								<?php if(isset($logged_in)) : ?>
								<?php echo $this->element('left_menu'); ?>
								<?php else : ?>
								<li class="menu-item"><a class="menu-link" href="<?= $this->Url->build('/') ?>">
										<div>Home</div>
									</a>
								</li>
								<li class="menu-item"><a class="menu-link" id="id_login" href="#myModal1"
										data-target="#myModal1" data-lightbox="inline">
										<div>Login</div>
									</a>
								</li>
								<li class="menu-item"><a class="menu-link" id="id_signup" href="#myModal2"
										data-target="#myModal2" data-lightbox="inline">
										<div>Signup</div>
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

		<!-- <div class="modal-on-load" data-target="#myModal1"></div> -->

		<!-- Login -->
		<div class="modal1 mfp-hide" id="myModal1">
			<div class="block mx-auto" style="background-color: #FFF; max-width: 500px; padding: 25px;">
				<?= $this->Flash->render('auth') ?>
				<?= $this->Form->create() ?>
				<h2 class="m-0 p-0">Login</h2>
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-12">
							</div>
							<div class="col-6">
								<?= $this->Form->control('username', [
														'label' => 'USERNAME',
														'type' => 'text',
														'class' => 'form-control',
													]) ?>
							</div>
							<div class="col-6">
								<?= $this->Form->control('password', [
														'label' => 'PASSWORD',
														'type' => 'password',
														'class' => 'form-control',
													]) ?>
							</div>
							<div class="col-6 mt-3">
								<a href="#"
									class="button button-rounded button-reveal button-large button-teal text-start w-100 mfp-close"
									style="position: inherit;"><i class="icon-angle-left"></i><span>Back</span></a>
							</div>
							<div class="col-6 mt-3">
								<button label="Login"
									class="button button-rounded button-reveal button-large button-yellow button-light text-end w-100"
									type="submit"><i class="icon-line-arrow-right"></i><span>Login</span></button>
							</div>
						</div>
					</div>
				</div>
				<?= $this->Form->end() ?>
			</div>
		</div>

		<!-- Signup -->
		<div class="modal2 mfp-hide" id="myModal2">
			<div class="block mx-auto" style="background-color: #FFF; max-width: 75vw; padding: 25px;">
				<?= $this->Flash->render('auth') ?>
				<form method="post" accept-charset="utf-8" action="/bsms/dealer/registration">
					<h2 class="m-0 p-0">Signup</h2>
					<div class="card">
						<div class="card-body">
							<div class="row">
								<?php $option = array('' => 'Type', 'buyer' => 'Buyer', 'seller' => 'Seller'); ?>
								<div class="col-3 mt-3">
									<?= $this->Form->control('user_type', [
                                                'type' => 'select',
                                                'options' => $option,
                                                'empty' => 'Select',
                                                'id' => 'product',
                                                'label' => 'User Type',
                                                'class' => 'form-control',
                                            ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('username', [
                                                'label' => 'Username',
                                                'type' => 'text',
                                                'class' => 'form-control',
                                            ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('password', [
                                                'label' => 'New Password',
                                                'type' => 'password',
                                                'class' => 'form-control',
                                            ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('re_password', [
                                                'label' => 'Confirm Password',
                                                'type' => 'password',
                                                'class' => 'form-control',
                                            ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('company_name', [
                                                'label' => 'Company',
                                                'type' => 'text',
                                                'class' => 'form-control',
                                            ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('address', [
                                                    'label' => 'text',
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('cities', [
                                                    'label' => 'Cities',
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('email', [
                                                    'label' => 'Email',
                                                    'type' => 'email',
                                                    'class' => 'form-control',
                                                ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('contact', [
                                                    'label' => 'Contact',
                                                    'type' => 'tel',
                                                    'class' => 'form-control',
                                                ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('alt_contact', [
                                                    'label' => 'Alt. Contact',
                                                    'type' => 'tel',
                                                    'class' => 'form-control',
                                                ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('business_type', [
                                                    'label' => 'Business',
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('product_deals', [
                                                    'type' => 'select',
                                                    'options' => $products,
                                                    'empty' => 'Select',
                                                    'id' => 'product',
                                                    'label' => 'Product Deals',
                                                    'class' => 'form-control',
                                                ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('TIN', [
                                                    'label' => 'TIN',
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                ]) ?>
								</div>
								<div class="col-3 mt-3">
									<?= $this->Form->control('GST', [
                                                    'label' => 'GST',
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                ]) ?>
								</div>
								<div class="col-3 mt-3 pt-4">
									<a href="#"
										class="button button-rounded button-reveal button-large button-teal text-start w-100 mfp-close"
										style="position: inherit;"><i class="icon-angle-left"></i><span>Back</span></a>
								</div>
								<div class="col-3 mt-3 pt-4">
									<button label="Signup"
										class="button button-rounded button-reveal button-large button-yellow button-light text-end w-100"
										type="submit"><i class="icon-line-arrow-right"></i><span>Signup</span></button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>

		<?= $this->fetch('content') ?>

		<!-- Footer		============================================= -->
		<footer id="footer" class="dark">
			<div class="container">
				<div class="footer-widgets-wrap p-3">
					<div class="row col-mb-50">
						<div class="col-lg-8">
							<div class="row col-mb-50">
								<div class="col-md-4" style="align-self: center;">
									<div class="widget clearfix">
										<img src="<?= $this->Url->build('/') ?>img/logo_white.png" alt="Image"
											class="footer-logo">
										<abbr title="Phone Number"><strong>Phone:</strong></abbr> (+91) 083693
										90146<br>
										<abbr title="Email Address"><strong>Email:</strong></abbr>
										support@fts-pl.com
									</div>
								</div>
								<div class="col-md-4">
									<h3 class="mb-1">KNOWLEDGE BASE</h3>
									<div class="widget widget_links clearfix mt-2">
										<ul>
											<li>&nbsp;How Test Quality </li>
											<li>&nbsp;How To Produce Quality </li>
											<li>&nbsp;Today's Rate </li>
											<li>&nbsp;Month Wise Rates </li>
										</ul>
									</div>
								</div>
								<div class="col-md-4">
									<h3 class="mb-1">KNOWLEDGE BASE</h3>
									<div class="widget widget_links clearfix mt-2">
										<ul>
											<li>&nbsp;News Event </li>
											<li>&nbsp;Knowledge Base </li>
											<li>&nbsp;Articles On Latest Trend </li>
											<li>&nbsp;How It Works </li>
										</ul>
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
												<div class="counter counter-small"><span data-from="50" data-to="1506"
														data-refresh-interval="80" data-speed="3000"
														data-comma="true"></span></div>
												<h5 class="mb-0">Total Buyers</h5>
											</div>
											<div class="col-lg-6 bottommargin-sm">
												<div class="counter counter-small"><span data-from="100" data-to="184"
														data-refresh-interval="50" data-speed="2000"
														data-comma="true"></span></div>
												<h5 class="mb-0">Sellers</h5>
											</div>
											<div class="col-lg-12 bottommargin-sm">
												<address>
													<strong>Address:</strong><br>
													401, Meet Galaxy,Trimurti Lane
													<br>Behind Tip Top Plaza,
													<br>Teen Hath Naka, Thane,
													<br>Maharashtra - 400604
												</address>
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
								<!-- <div class="col-md-3 col-lg-12">
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
								</div> -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Copyrights			============================================= -->
			<div id="copyrights p-3">
				<div class="container">
					<div class="row col-mb-30">
						<div class="col-md-6 text-center text-md-start">
							Copyrights &copy; 2020 All Rights Reserved by ftspl Inc.<br>
							<div class="copyright-links"><a href="https://www.fts-pl.com/privacy-policy/">Terms of
									Use</a> / <a href="https://www.fts-pl.com/privacy-policy/">Privacy Policy</a>
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
							<i class="icon-envelope2"></i> support@fts-pl.com <span class="middot">&middot;</span>
							<i class="icon-headphones"></i> +91 9876543210 <span class="middot">&middot;</span> <i
								class="icon-skype2"></i> ftsplOnSkype
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
	<div id="gotoTop" class="icon-angle-up"></div>
	<?= $this->Html->script(['plugins.min', 'functions', 'jquery.themepunch.tools.min', 'jquery.themepunch.revolution.min', 'extensions/revolution.extension.video.min', 'extensions/revolution.extension.slideanims.min', 'extensions/revolution.extension.actions.min', 'extensions/revolution.extension.layeranimation.min', 'extensions/revolution.extension.kenburn.min', 'extensions/revolution.extension.navigation.min', 'extensions/revolution.extension.migration.min', 'extensions/revolution.extension.parallax.min', 'common', 'custom']) ?>
	<script>
		window.onload = function login() {
			$("#id_login").addClass('modal-on-load');
		}
		$(document).on("click", ".login", function () {
			$("#id_login").trigger("click");
		});
	</script>
</body>

</html>