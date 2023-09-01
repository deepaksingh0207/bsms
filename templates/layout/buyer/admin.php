<?php

/**
 * @var \App\View\AppView $this
 * @var \CakeLte\View\Helper\CakeLteHelper $this->CakeLte
 */

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->assign('title', $title); ?>
    <title><?= $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

 
    <!-- Google Font: Source Sans Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <?= $this->Html->css('CakeLte./AdminLTE/plugins/fontawesome-free/css/all.min.css') ?>

    <!-- jQuery -->
    <?= $this->Html->script('CakeLte./AdminLTE/plugins/jquery/jquery.min.js') ?>


    <!-- DataTables  & Plugins -->
    <?= $this->Html->css('CakeLte./AdminLTE//plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
    <?= $this->Html->script('CakeLte./AdminLTE/plugins/datatables/jquery.dataTables.min.js') ?>
    <?= $this->Html->script('CakeLte./AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>
    <?= $this->Html->script('CakeLte./AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>
    <?= $this->Html->css('CakeLte./AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
    <?= $this->Html->script('CakeLte./AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>
    <?= $this->Html->script('CakeLte./AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>
    <?= $this->Html->css('CakeLte./AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>
    <?= $this->Html->script('CakeLte./AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>
    <?= $this->Html->css('CakeLte./AdminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>

    <!-- Ionicons -->
    <?= $this->Html->css('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') ?>

    <!-- Theme style -->
    <?= $this->Html->css('CakeLte./AdminLTE/dist/css/adminlte.min.css') ?>
    <?= $this->Html->css('CakeLte./AdminLTE/plugins/summernote/summernote.min.css') ?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <?= $this->Html->css('CakeLte.style') ?>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <?= $this->Html->css('CakeLte./AdminLTE/plugins/toastr/toastr.min.css') ?>
    <?= $this->Html->css('custom_table.css') ?>
    <?= $this->element('layout/css') ?>
    <?= $this->fetch('css') ?>


    <!-- Bootstrap 4 -->
    <?= $this->Html->script('CakeLte./AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>
    <!-- AdminLTE App -->
    <?= $this->Html->script('CakeLte./AdminLTE/dist/js/adminlte.min.js') ?>

    <?= $this->Html->script("CakeLte./AdminLTE/plugins/jquery-validation/jquery.validate.min.js") ?>

    <!-- sweetalert2 -->
    <?= $this->Html->css('CakeLte./AdminLTE/plugins/sweetalert2/sweetalert2.min.css') ?>
    <?= $this->Html->script("CakeLte./AdminLTE/plugins/sweetalert2/sweetalert2.min.js") ?>

    <!-- toastr -->
    <?= $this->Html->script('CakeLte./AdminLTE/plugins/toastr/toastr.min.js') ?>
    <?= $this->Html->script('/js/common.js') ?>
    <script>var baseurl = "<?= $this->Url->build('/') ?>";</script>
</head>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">
        <?= $this->element('header/main') ?>

        <aside class="main-sidebar <?= $this->CakeLte->getSidebarClass() ?>">
            <a href="<?= $this->Url->build('/buyer/dashboard') ?>" class="brand-link ml-3">
                <!-- <?= $this->Html->image('ft-icon.png', ['width' => '110', 'class' => 'mt-1 ft_rect_logo brand-image', 'data-image' => '2']) ?> -->
                <span class="brand-text">
                    <?= $this->Html->image('apar_logo.png', ['width' => '110', 'class' => 'ft-text', 'data-image' => '2']) ?>
                </span>
            </a>
            <div class="sidebar" id="id_sidebar">
                <?= $this->element('sidebar/buyer/main') ?>
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?= $this->element('content/header') ?>
            <div class="content">
                <div class="container-fluid">
                    <?= $this->Flash->render() ?>
                    <?= $this->fetch('content') ?>
                </div>
            </div>
        </div>

        <aside class="control-sidebar control-sidebar-dark"><?= $this->element('aside/main') ?></aside>

        <footer class="main-footer" style="margin-top:0px;text-align: center;"><?= $this->element('footer/main') ?></footer>
    </div>

    <!-- <?= $this->Html->script('https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js') ?> -->
    <?= $this->Html->script('https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js') ?>

    <!-- DataTables  & Plugins -->
    <?= $this->Html->script('CakeLte./AdminLTE/plugins/summernote/summernote.min.js') ?>
    <?= $this->Html->script('CakeLte./AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js') ?>
    <?= $this->Html->script('/js/cscript.js') ?>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        
        <?php if($flash) : ?>
            Toast.fire({
                icon: "<?= $flash['type'] ?>",
                title: "<?= $flash['msg'] ?>",
            });
        <?php endif; ?>
        // $(document).ready(function() {});
        $(window).on('load', function () { $('#loaderss').hide(); });
        $(function () { $('[data-toggle="tooltip"]').tooltip(); $('#summernote').summernote({ width: 1000, }); });
    </script>
    <?= $this->element('layout/script') ?>
    <?= $this->fetch('script') ?>
</body>

</html>