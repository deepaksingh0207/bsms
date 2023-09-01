<!DOCTYPE html>
<html lang="en">

<head>

  <?= $this->Html->script('CakeLte./AdminLTE/plugins/jquery/jquery.min.js') ?>
  <?= $this->Html->script("CakeLte./AdminLTE/plugins/jquery-validation/jquery.validate.min.js") ?>

  <style type="text/css">
    input[type='radio'] {
      accent-color: #F7941D !important;
    border: 0px;
    width: 1.3em;
    height: 1.3em;
}

    span#otp_error {
      display: inline-block;
      padding-bottom: 10px;
    }

    button#otploginclick {
      margin-bottom: 30px !important;
      margin-top: 0px !important;
    }

    div#email_login {
      margin-top: 20px;
      width: 95%;
    }

    .error {
      color: #FF0000;
      text-align: left;
      display: block;
    }

    input::placeholder {
      font-size: 14px;
    }

    .signupcard .signupform__signin--signinText {
      margin-bottom: 5% !important;
    }

    .mb-0 {
      margin-bottom: 0px;
    }

    p.error-msg {
      color: #e31720;
      text-align: left;
      margin-top: 5px;
      font-size: 12px;
      font-style: italic;
    }

    .custom-radio label {
      font-size: 1rem;

    }

    .signupcard .signupform__signin {
      justify-content: center;
      flex-direction: column;
      align-items: center;
      padding: 8% 15% 5% !important
        /* height: 600px; */
    }

    #mobile_login_otp {
      padding: 0% 15% 5% !important;
      margin-top: -30px;
    }

    .ant-card-body {
      padding: 0px !important;
    }

    /*.ant-card.signupcard.ant-card-bordered {
    height: 100%;
} */

    .v2container {
      color: white;
      background-color: #b61924 !important;
      background: #2980b9 !important;
      background: -webkit-linear-gradient(to right, #2c3e50, #2980b9) !important;
      background: linear-gradient(to right, #2c3e50, #2980b9) !important;
      height: auto !important;
      min-height: calc(100vh - 0px) !important;
    }

    div#mobile_login {
      width: 100%;

    }

    canvas.particles-js-canvas-el {

      position: absolute;
      width: 100%;
      height: 100%;
      z-index: 99;
      left: 0;
      top: 0;
      bottom: 0;
      right: 0;
    }

    .left-content h1 {
      color: #fff;
      font-size: 2.8vw;
      line-height: 3.5rem;
    }

    .left-content {
      padding-left: 65px;
    }

    .left-content p {
      font-size: 20px;
    }

    img.flow-img {
      width: 55%;
    }

    .ant-row {
      position: absolute;
      top: 50%;
      left: 50%;
      align-items: center;
      transform: translate(-50%, -50%);
      text-align: center;
      width: 100%;
      height: 100%;
      z-index: 999;
    }

    .s-logo {
      width: 50%;
    }

    .sub-btn {
      background-image: linear-gradient(to right, #FF4E50 0%, #F9D423 51%, #FF4E50 100%) !important;
      transition: 0.5s;
      background-size: 200% auto !important;
      color: white;
      box-shadow: 0 0 20px #eee !important;
      border-radius: 30px !important;
      display: block;
      font-size: 15px !important;
      line-height: 1.1em !important;
      margin: 0px !important;
      height: 40px !important;
      border: none !important;
      margin-top: 7px !important;
    }

    .sub-btn:hover {
      background-position: right center !important;
      color: #fff;
      text-decoration: none;
    }

    .anticon {
      display: inline-block;
      color: inherit;
      font-style: normal;
      line-height: 0;
      text-align: center;
      text-transform: none;
      vertical-align: -0.125em;
      text-rendering: optimizeLegibility;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    .anticon>* {
      line-height: 1;
    }

    .anticon svg {
      display: inline-block;
    }

    .anticon::before {
      display: none;
    }

    .anticon .anticon-icon {
      display: block;
    }

    .anticon[tabindex] {
      cursor: pointer;
    }

    .anticon-spin::before,
    .anticon-spin {
      display: inline-block;
      -webkit-animation: loadingCircle 1s infinite linear;
      animation: loadingCircle 1s infinite linear;
    }

    .forget-pwd {
      font-size: 14px;
      margin-bottom: 10px !important;
    }
    .material-textfield .material-label,.material-textfield .material-input{
      font-size: 1.2rem !important;
    }

    @-webkit-keyframes loadingCircle {
      100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }

    @keyframes loadingCircle {
      100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }

    body {
      font-family: 'Heebo', sans-serif;
    }
  </style>
  <meta charset="utf-8">
  <link rel="icon" href="./favicon.ico">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#000000">
  <meta name="description"
    content="Sign up now! Unlock the best platform to discover freight rates, execute your shipments &amp; track containers">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= $this->Url->build('/') ?>css/all.min.css">
  <link href="<?= $this->Url->build('/') ?>css/5.7cec8de0.chunk.css" rel="stylesheet">
  <link href="<?= $this->Url->build('/') ?>css/main.26d266c0.chunk.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script charset="utf-8" src="<?= $this->Url->build('/') ?>js/0.5bbd83f8.chunk.js"></script>
  <script charset="utf-8" src="<?= $this->Url->build('/') ?>js/1.a9e5058d.chunk.js"></script>
  <script charset="utf-8" src="<?= $this->Url->build('/') ?>js/6.3128c4ca.chunk.js"></script>
  <link rel="stylesheet" type="text/css" href="<?= $this->Url->build('/') ?>css/13.656858bb.chunk.css">
  <script charset="utf-8" src="<?= $this->Url->build('/') ?>js/13.b8dbb772.chunk.js"></script>
  <?= $this->Html->script('CakeLte./AdminLTE/plugins/jquery/jquery.min.js') ?>
</head>

<body>
  <div id="root">
    <div class="App">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

      <div class="v2container" id="particles-js">
        <div class="ant-row">
          <div
            class="ant-col otherdetails-container ant-col-xs-24 ant-col-sm-24 ant-col-md-24 ant-col-lg-12 ant-col-xl-12">
            <div class="left-content animate__animated animate__backInLeft">
              <h1>Welcome to APAR Vendor Portal</h1>
              <p>A single window digital interface and communication platform for supplier assessment and onboarding-
                Towards Co-creating Governance.</p>
              <img class="flow-img" src="<?= $this->Url->build('/') ?>img/login-img.png">
            </div>
          </div>
          <div class="ant-col content-container ant-col-xs-24 ant-col-sm-24 ant-col-md-24 ant-col-lg-12 ant-col-xl-12">
            <div class="ant-card signupcard ant-card-bordered">
              <div class="ant-card-body">

                <div class="signupform signupform__signin">
                  <p class="signupform__signin--signinText">
                    <img src="<?= $this->Url->build('/') ?>img/apar_logo.png" class="s-logo">
                  </p>

                  <div class="select-button login-page .form">
                    <div class="buyer-btn custom-control custom-radio" style="
                      margin-right: 31px">
                      <input type="radio" id="mobile_btn" class="custom-control-input" name="a" value="">
                      <label class="custom-control-label" for="buyer"><span>Login with mobile</span></label>
                    </div>
                    <div class="approver-btn custom-control custom-radio ">
                      <input type="radio" class="custom-control-input" id="email_btn" name="a" checked="" value="">
                      <label class="custom-control-label" for="approver"><span>Login with Email</span></label>
                    </div>
                  </div>
                  <?= $this->Flash->render('auth') ?>

                  <div id="email_login">
                    <?= $this->Form->create(null, ['id' => 'loginForm']) ?>
                    <?= $this->Form->control('logged_by', ['type' => 'hidden', 'value' => 'email', 'id' => 'loginby']); ?>
                    <span class="error userpassError" style="margin-bottom:20px;margin-top: -20px;"></span>
                    <!-- <div style="width: 100%;">
                      <div class="material-textfield">
                        <div
                          class="ant-select material-select   ant-select-auto-complete ant-select-single ant-select-show-search">
                          <div class="ant-select-selector"><span class="ant-select-selection-search"><input
                                autocomplete="off" type="search" class="ant-select-selection-search-input"
                                role="combobox" aria-haspopup="listbox" aria-owns="rc_select_0_list"
                                aria-autocomplete="list" aria-controls="rc_select_0_list"
                                aria-activedescendant="rc_select_0_list_0" value="" id="rc_select_0"></span></div>
                        </div><label class="material-label" style="left: 0px;">Workspace url</label>
                        <p class="material-rightLabel">.fts-pl.com</p>
                      </div>
                    </div> -->
                    <div style="width: 100%;">
                      <div class="material-textfield mb-0 form-group">
                        <input class="material-input sentence form-control" placeholder="Enter Username/Email"
                          required="required" type="text" name="username" value="">
                        <label class="material-label" style="left: 0px;">Username/Email</label>
                      </div>

                    </div>
                    <div style="width: 100%;">
                      <div class="material-textfield signin-textfield form-group"><input
                          class="material-input form-control" placeholder="Enter Password" type="password" id="password"
                          name="password" required="required" value=""><label class="material-label"
                          style="left: 0px;">Password</label>

                        <p class="material-rightLabel"><i class="fa fa-eye-slash" id="eye" aria-hidden="true"
                            style="cursor: pointer;"></i></p>

                        <p class="material-rightBottomLabel" style="text-decoration:none !important;"><a href="users/forget_pwd"
                            class="forget-pwd">Forgot
                            Password ?</a></p>
                      </div>

                    </div><button type="button" id="loginclick"
                      class="ant-btn btn btn__get-started-btn sub-btn">SUBMIT</button>
                    <!-- <p class="signupform__signin--dontHaveAccount">Don't have an account?<a
                        style="cursor: pointer;">Create An Account</a></p> -->
                    <?= $this->Form->end() ?>
                    <p style="text-align: center;font-weight: 500;margin-top:10px;"><a href="mailto: support@fts-pl.com"
                        style="margin-right: 5px;border-right: 1px solid;padding-right: 10px;">Help</a> <a
                        href="https://www.fts-pl.com/privacy-policy/">Privacy Terms</a></p>
                    <p class="text-center" style="text-align:center"> <img
                        src="<?= $this->Url->build('/') ?>img/ftspl.png" width="120px"></p>

                  </div>
                  <div class="row" id="mobile_login" style="width:100%; display: none;">
                    <?= $this->Form->create() ?>
                    <span class="error userpassError" style="margin-bottom:20px;"></span>
                    <div class="input-group mb-3">
                      <div class="material-textfield" style="margin-bottom:10px;">
                        <input class="material-input " placeholder="Mobile" id="mobile" type="tel" maxlength="10"
                          name="mobile" pattern="[0-9]{10}" value="">
                        <label class="material-label" style="left: 0px;">Mobile +91</label>
                      </div>
                    </div>
                    <button type="button" class="sub-btn ant-btn btn btn__get-started-btn mb-4" id="getotp">
                      <span>Get OTP</span>
                    </button>
                    <?= $this->Form->end() ?>
                    <p style="text-align: center;font-weight: 500;margin-top:20px;"><a href="mailto: support@fts-pl.com"
                        style="margin-right: 5px;border-right: 1px solid;padding-right: 10px;">Help</a> <a
                        href="https://www.fts-pl.com/privacy-policy/">Privacy Terms</a></p>
                    <p class="text-center" style="text-align:center"> <img
                        src="<?= $this->Url->build('/') ?>img/ftspl.png" width="120px"></p>
                  </div>
                </div>


                <div class="row" id="mobile_login_otp" style="display:none;">
                  <?= $this->Form->create(null, ['id' => 'otpForm']) ?>
                  <?= $this->Form->control('mobile', ['type' => 'hidden', 'id' => 'user_mobile']); ?>
                  <?= $this->Form->control('logged_by', ['type' => 'hidden', 'value' => 'mobile', 'id' => 'loginby']); ?>
                  <span style="color:red;" id="otp_error"></span>
                  <div class="input-group mb-3">
                    <div class="material-textfield">
                      <input class="material-input " placeholder="Enter OTP" id="otp" type="tel" maxlength="10"
                        name="otp" maxlength="6" pattern="[0-9]{6}">
                      <label class="material-label" style="left: 0px;">OTP</label>
                    </div>
                  </div>

                  <div class="row">
                    <!-- /.col -->
                    <div class="col-4">
                      <!-- <?= $this->Form->button(__('Sign in'), ['class' => 'btn btn-primary btn-block', 'id' => 'loginclick']); ?> -->
                      <button class="sub-btn ant-btn btn btn__get-started-btn mb-4" id="otploginclick"
                        type="button">Sign in</button>
                    </div>
                    <!-- /.col -->
                  </div>
                  <?= $this->Form->end() ?>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?= $this->Url->build('/') ?>js/5.b662bfe1.chunk.js"></script>
  <script src="<?= $this->Url->build('/') ?>js/main.d308f349.chunk.js"></script>
  <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.6/ScrollMagic.min.js"></script>
  <script>
    // background particles
    particlesJS("particles-js", {
      "particles": {
        "number": {
          "value": 290,
          "density": {
            "enable": true,
            "value_area": 800
          }
        },
        "color": {
          "value": "#ffffff"
        },
        "shape": {
          "type": "triangle",
          "stroke": {
            "width": 0,
            "color": "#000000"
          },
          "polygon": {
            "nb_sides": 10
          },
          "image": {
            "src": "img/github.svg",
            "width": 150,
            "height": 150
          }
        },
        "opacity": {
          "value": 0.5,
          "random": false,
          "anim": {
            "enable": false,
            "speed": 1,
            "opacity_min": 0.1,
            "sync": false
          }
        },
        "size": {
          "value": 3,
          "random": true,
          "anim": {
            "enable": false,
            "speed": 40,
            "size_min": 0.1,
            "sync": false
          }
        },
        "line_linked": {
          "enable": true,
          "distance": 150,
          "color": "#ffffff",
          "opacity": 0.4,
          "width": 1
        },
        "move": {
          "enable": true,
          "speed": 6,
          "direction": "none",
          "random": true,
          "straight": false,
          "out_mode": "out",
          "bounce": true,
          "attract": {
            "enable": false,
            "rotateX": 600,
            "rotateY": 1200
          }
        }
      },
      "interactivity": {
        "detect_on": "window",
        "events": {
          "onhover": {
            "enable": true,
            "mode": "repulse"
          },
          "onclick": {
            "enable": true,
            "mode": "push"
          },
          "resize": true
        },
        "modes": {
          "grab": {
            "distance": 140,
            "line_linked": {
              "opacity": 1
            }
          },
          "bubble": {
            "distance": 400,
            "size": 100,
            "duration": 2,
            "opacity": 8,
            "speed": 3
          },
          "repulse": {
            "distance": 100,
            "duration": 0.4
          },
          "push": {
            "particles_nb": 4
          },
          "remove": {
            "particles_nb": 2
          }
        }
      },
      "retina_detect": true
    });


    $('#loginclick').click(function (e) {
      e.preventDefault(); // Prevent the form from submitting normally
      // var formData = $(this).serialize();

      $.ajax({
        type: "POST",
        url: "<?php echo \Cake\Routing\Router::url(array('/controller' => 'users-controller', 'action' => 'api-login')); ?>",
        data: $("#loginForm").serialize(),
        dataType: 'json',
        success: function (response) {
          if (response.status == '1') {
            window.location.href = response.redirect.controller;
          } else {
            $('span.userpassError').empty().append(response.message);
          }

        }
      });
    });



    $('#otploginclick').click(function (e) {
      $('#otp_error').empty();
      e.preventDefault(); // Prevent the form from submitting normally
      $.ajax({
        type: "POST",
        url: "<?php echo \Cake\Routing\Router::url(array('/controller' => 'users-controller', 'action' => 'api-login')); ?>",
        data: $("#otpForm").serialize(),
        dataType: 'json',
        success: function (response) {
          if (response.status == '1') {
            window.location.href = response.redirect.controller;
          } else {
            $('#otp_error').empty().append(response.message);
          }

        }
      });
    });

    

    //end
    $(document).ready(function () {

      setTimeout(function () {
            $('.success').fadeOut('slow');
        }, 2000); // <-- time in milliseconds

      $(document).on("change", "#mobile_btn", function () {
        $('span.userpassError').empty();
        $('#email_login').hide();
        $('#mobile_login').show();
        $("#mobile_login_otp").hide();
        $('#loginby').val('mobile');

      });

      $(document).on("change", "#email_btn", function () {
        $('span.userpassError').empty();
        $('#email_login').show();
        $('#mobile_login').hide();
        $("#mobile_login_otp").hide();
        $('#loginby').val('email');
      });

      $(document).on("change, keyup", ".sentence", function () {
        $(this).val($(this).val().toLowerCase())
      });

      $("#getotp").click(function () {
        $('span.userpassError').empty();
        var request = $.ajax({
          url: "users/get-otp",
          method: "POST",
          headers: {
            'X-CSRF-Token': $('[name="_csrfToken"]').val()
          },
          data: {
            mobile: $("#mobile").val()
          },
          dataType: "json",
          success: function (response) {
            if (response.status == '1') {
              // window.location.href = response.redirect.controller;
            } else {
              $('span.userpassError').empty().append(response.message);
            }
          }
        });

        request.done(function (response) {
          if (response.status == '1') {
            $("#mobile_login_otp").show();
            $("#mobile_login").hide();
            $("#user_mobile").val($("#mobile").val());
          } 
        });

        request.fail(function (jqXHR, textStatus) {
          console.log("Request failed: " + textStatus);
        });
      });

      $("#loginForm").validate({
        rules: {
          username: {
            required: true,
            email: true
          },
          password: {
            required: true
          }
        },
        messages: {
          username: {
            required: "Please enter username",
            email: "Please enter valid email id"
          },
          password: {
            required: "Please enter password"
          }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        },
        submitHandler: function (form, event) {
          event.preventDefault();
          $('#loginForm')[0].submit();
          return false;
        }
      });
    });
    // for password hide/show
    $('#eye').click(function () {

      if ($(this).hasClass('fa-eye-slash')) {

        $(this).removeClass('fa-eye-slash');

        $(this).addClass('fa-eye');

        $('#password').attr('type', 'text');

      } else {

        $(this).removeClass('fa-eye');

        $(this).addClass('fa-eye-slash');

        $('#password').attr('type', 'password');
      }
    });
  </script>
</body>

</html>