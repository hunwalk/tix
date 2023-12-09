<?php

/* @var $this View */

/* @var $content string */

use app\assets\AdminAsset;
use app\assets\FixAdminAsset;
use app\modules\admin\widgets\Alert;
use app\modules\admin\widgets\Menu;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\web\View;

AdminAsset::register($this);
FixAdminAsset::register($this);
$adminAssetUrl = AdminAsset::getBaseUrl();
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/" class="nav-link">Application</a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/admin" class="brand-link">
                <img src="/img/forge-logo.png" alt="AdminLTE Logo" class="brand-image img-circle" style="opacity: .8">
                <span class="brand-text font-weight-light">Yii2 Forge</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <?php if (!Yii::$app->user->isGuest): ?>
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="<?= Yii::$app->user->identity->profile->avatar ?>" class="img-circle elevation-2"
                                 alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block"><?= Yii::$app->user->identity->profile->name ?: Yii::$app->user->identity->username ?></a>
                        </div>
                    </div>
                <?php else: ?>
                    <br>
                <?php endif; ?>


                <!-- SidebarSearch Form -->
                <!--            <div class="form-inline">-->
                <!--                <div class="input-group" data-widget="sidebar-search">-->
                <!--                    <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">-->
                <!--                    <div class="input-group-append">-->
                <!--                        <button class="btn btn-sidebar">-->
                <!--                            <i class="fas fa-search fa-fw"></i>-->
                <!--                        </button>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <?= Menu::widget([
                        'items' => [
                            [
                                'name' => 'Dashboard',
                                'icon' => 'tachometer-alt',
                                'url' => ['/admin/dashboard/index']
                            ],
                            [
                                'name' => 'System',
                                'icon' => 'cog',
                                'url' => ['/admin/system-variable/index']
                            ],
                            [
                                'name' => 'User Management',
                                'icon' => 'users',
                                'url' => ['/admin/user/admin']
                            ],
                            [
                                'name' => 'Profile',
                                'icon' => 'user',
                                'url' => ['/admin/user/settings'],
                                'items' => [
                                    [
                                        'name' => 'Edit Profile',
                                        'icon' => 'cog',
                                        'url' => ['/admin/user/settings/profile']
                                    ],
                                    [
                                        'name' => 'Account',
                                        'icon' => 'user',
                                        'url' => ['/admin/user/settings/account']
                                    ],
                                    [
                                        'name' => 'Privacy ',
                                        'icon' => 'lock',
                                        'url' => ['/admin/user/settings/privacy']
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Logout',
                                'icon' => 'sign-out-alt',
                                'linkOptions' => ['data-method' => 'POST', 'data-confirm' => 'Are you sure you want to log out?'],
                                'url' => ['/admin/user/logout']
                            ],
                        ]
                    ]) ?>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <?= Alert::widget() ?>
                        </div>
                        <div class="col-sm-6">
                            <h1><?= $this->title ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <?= Breadcrumbs::widget([
                                'homeLink' => [
                                    'url' => '/admin',
                                    'label' => 'Admin',
                                ],
                                'options' => ['class' => 'float-sm-right'],
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ]) ?>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Main content -->
            <section class="content">
                <?= $content ?>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.1.0
            </div>
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>