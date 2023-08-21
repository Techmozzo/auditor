<!DOCTYPE html>
<html>

<head>
    <base href="" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?php echo e(config('app.name')); ?></title>
    <link rel="icon" href="/dashboard/dist/assets/images/favicon2.png">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,400;0,500;0,600;0,700;1,400&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" />


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <link rel="stylesheet" href="/dashboard/dist/assets/css/vendors.bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.9.1/styles/github.min.css" />
    <link rel="stylesheet" href="/dashboard/dist/assets/css/main.bundle.min.css" />
    <link rel="stylesheet" href="/dashboard/dist/assets/css/custom.css" />
    <?php echo $__env->yieldContent('css'); ?>
</head>

<body>
    <div class="app-admin-wrap-layout-1 sidebar-full sidebar-theme-slate">
        <div class="sidebar-panel">
            <div class="sidebar-compact-switch"><span></span>
                <div></div>
                <span></span>
            </div>
            <div class="brand">
                <a href="<?php echo e(route('home')); ?>">

                    <span class="app-logo-text ml-2 text-20"><?php echo e(config('app.name')); ?></span>
                </a>
            </div>

            <!-- Start:: side-nav-->

            <div class="scroll-nav" data-perfect-scrollbar="" data-suppress-scroll-x="true">
                <div class="app-user text-center">
                    <div class="app-user-photo">
                        <?php if ($auditor->profile->dp) : ?>
                            <img src="<?php echo e($auditor->profile->dp); ?>" alt="" />
                        <?php else : ?>
                            <?php if ($auditor->profile->gender == 'felmale') : ?>
                                <img src="/dashboard/src/images/avatars/006-woman-1.svg" alt="" />
                            <?php elseif ($auditor->profile->gender == 'male') : ?>
                                <img src="/dashboard/src/images/avatars/001-man.svg" alt="" />
                            <?php else : ?>
                                <img src="/dashboard/src/images/avatars/005-man-2.svg" alt="" />
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="app-user-info"><span class="app-user-name"><?php echo e($auditor->name()); ?></span>
                        <div class="app-user-control">
                            <a class="control-item" href="#"><i class="material-icons">
                                    settings</i></a>
                            <a class="control-item" href="<?php echo e(route('help-center')); ?>"><i class="material-icons">
                                    email</i></a>
                            <a class="control-item" href="<?php echo e(route('logout')); ?>"><i class="material-icons">exit_to_app</i></a>
                        </div>
                    </div>
                </div>
                <div class="side-nav">
                    <div class="main-menu">
                        <nav class="sidebar-nav">
                            <ul class="metismenu show-on-load" id="ul-menu">
                                <li><a href="<?php echo e(route('home')); ?>"><i class="material-icons nav-icon text-16">home</i>Home</a></li>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('admin')) : ?>
                                    <li><a class="has-arrow" href="#"><i class="material-icons nav-icon text-16">settings</i>Self Service</a>
                                        <ul class="mm-collapse">
                                            <li><a href="#"><i class="material-icons nav-icon">lock</i>Password
                                                    Change</a></li>
                                            <li><a href="#"><i class="material-icons nav-icon">toggle_off</i>Account
                                                    Switch-Off/On</a></li>
                                        </ul>
                                    </li>

                                    <li><a class="control-item" href="<?php echo e(route('help-center')); ?>" title="Help Center"><i class="material-icons nav-icon text-16">help</i>Help</a>
                                    </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')) : ?>
                                    <li><a href="<?php echo e(route('comfirmation-requests.index')); ?>"><i class="material-icons nav-icon text-16">add_card</i>Confirmation Requests</a>
                                    </li>
                                    <li><a href="<?php echo e(route('auditors.index')); ?>"><i class="material-icons nav-icon text-16">manage_accounts</i>Auditors Management</a>
                                    </li>
                                <?php endif; ?>

                                <li><a class="control-item" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="material-icons nav-icon text-16">exit_to_app</i>Logout</a>
                                </li>
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                </form>

                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content-wrap">
            <!-- Start::Mobile header-->
            <div class="ul-mobile-top-header bg-slate">
                <a href="https://Ea-Auditor.com/">

                    <span class="app-logo-text ml-2 text-20 text-white"><?php echo e(config('app.name')); ?></span>
                </a>
                <div class="flex-grow-1"></div>
                <button class="sidebar-full-toggle btn btn-icon btn-primary rounded-circle text-white"><i class="material-icons">menu</i></button>

                <i class="material-icons text-white ul-mobile-header-toggle">more_horiz</i>
            </div>
            <!-- End::Mobile header  -->
            <!-- Start::Main header  -->
            <header class="main-header bg-card d-flex flex-row justify-content-between align-items-center px-lg">
                <!-- Start::Header menu-->
                <div>
                    <div class="ul-header-menu-wrap"><i class="material-icons ul-header-menu-toggle">close</i>
                        <div class="ul-header-menu">
                            <ul class="ul-header-nav">
                                <li class="ul-menu-item ul-menu-item--active"><a class="ul-menu-link" href="<?php echo e(route('home')); ?>">Dashboards</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End::Header menu-->
                <div class="ul-header-topbar">
                    <div class="flex-grow-1"></div>
                    <div class="dropdown profile-dropdown ml-xs">
                        <div class="header-btn-group">
                            <button class="btn d-flex py-1 pl-2 pr-0 rounded" id="dropdownTopUserProfile" type="button" data-toggle="dropdown"><span class="m-0 mr-2 font-weight-normal">Hi,
                                    <?php echo e($auditor->name()); ?></span>
                                <span class="fa fa-user"></span>
                            </button>
                            <div aria-labelledby="dropdownTopUserProfile">
                                <div class="card dropdown-menu p-0 ul-avatar-dropdown">
                                    <div class="card-header bg-primary">
                                        <div class="ul-avatar-dropdown-container">
                                            <?php if ($auditor->profile->dp) : ?>
                                                <img class="avatar-md rounded-circle mr-2" src="<?php echo e($auditor->profile->dp); ?>" alt="" />
                                            <?php else : ?>
                                                <?php if ($auditor->profile->gender == 'felmale') : ?>
                                                    <img class="avatar-md rounded-circle mr-2" src="/dashboard/src/images/avatars/006-woman-1.svg" alt="" />
                                                <?php elseif ($auditor->profile->gender == 'male') : ?>
                                                    <img class="avatar-md rounded-circle mr-2" src="/dashboard/src/images/avatars/001-man.svg" alt="" />
                                                <?php else : ?>
                                                    <img class="avatar-md rounded-circle mr-2" src="/dashboard/src/images/avatars/005-man-2.svg" alt="" />
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <div class="content">
                                                <p class="text-white font-weight-semi m-0">
                                                    <?php echo e($auditor->name()); ?>

                                                </p>
                                                <p class="text-small text-muted my-xs">TSB, User</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-xl">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('admin')) : ?>


                                            <a class="dropdown-item link-alt" href="#"><i class="material-icons icon icon-sm">settings</i>Settings</a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer text-muted">
                                        <a href="<?php echo e(route('logout')); ?>" class="btn btn-raised btn-raised-primary btn-sm" type="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- End::Main header-->
            <!-- Start:: content body-->
            <div class="main-content-body">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')) : ?>
                    <div class="subheader px-lg">
                        <div class="subheader-container">
                            <div class="subheader-main d-none d-lg-flex">
                                <nav class="ul-breadcrumb" aria-label="breadcrumb">
                                    <ol class="ul-breadcrumb-items">
                                        <li class="breadcrumb-home"><a href="<?php echo e(route('home')); ?>"> <i class="material-icons">home</i></a></li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="flex-grow-1"></div>
                            <?php if (request()->routeIs('admin.faqs.index')) : ?>
                                <div class="subheader-toolbar"><a class="btn btn-opacity-primary btn-sm mr-2" href="<?php echo e(route('faqs.create')); ?>">Add new FAQ </a>
                                <?php else : ?>
                                    <div class="subheader-toolbar">
                                        <?php switch ($auditor):
                                            case ($auditor->is_verified == 1): ?>
                                                <a class="btn btn-opacity-success btn-sm mr-2">Verified <i class="fa fa-check-circle"></i></a>
                                                <?php break; ?>

                                            <?php
                                            case ($auditor->is_verified == 0): ?>
                                                <a class="btn btn-opacity-warning btn-sm mr-2">Pending Verification <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i></a>
                                                <?php break; ?>

                                            <?php
                                            case ($auditor->is_verified == 2): ?>
                                                <a class="btn btn-opacity-danger btn-sm mr-2" href="<?php echo e(route('validation.index')); ?>">
                                                    Failed Verification <i class="fa fa-warning"></i></a>
                                                <?php break; ?>

                                            <?php
                                            default: ?>
                                                <a class="btn btn-opacity-danger btn-sm mr-2" href="<?php echo e(route('validation.index')); ?>">
                                                    Unverified <i class="fa fa-times-circle"></i></a>
                                        <?php endswitch; ?>
                                    </div>
                                <?php endif; ?>
                                </div>
                        </div>
                    <?php endif; ?>
                    <!-- Start:: content (Your custom content)-->

                    <?php echo $__env->yieldContent('custom_content'); ?>

                    <!-- Start:: Footer-->
                    <div class="flex-grow-1"></div>
                    <div class="bg-card px-lg py-md d-flex justify-content-center align-items-center flex-wrap shadow-6dp">
                        <p class="text-muted m-0">All rights reserved &copy; <?php echo e(\Carbon\Carbon::now()->year); ?>

                            <?php echo e(config('app.name')); ?></p>
                    </div>
                    <!-- End:: Footer-->
                    </div>
                    <!-- End:: content body-->
            </div>
            <div class="ul-sidebar-panel-overlay"></div>
            <script src="/dashboard/dist/assets/js/vendors.bundle.min.js"></script>
            <script src="/dashboard/dist/assets/js/main.bundle.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.9.1/highlight.min.js"></script>
            <script src="/dashboard/dist/assets/vendors/autonumeric/autonumeric.min.js"></script>

            <?php echo $__env->yieldContent('script'); ?>
</body>

</html>
<?php /**PATH /Users/chukwuemekainya/Sites/audit-confirmation/auditor/resources/views/layouts/dashboard.blade.php ENDPATH**/ ?>