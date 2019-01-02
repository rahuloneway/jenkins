<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$user = $this->session->userdata('user');
$id = $this->encrypt->encode($user['UserID']);
/* Check if logged in user is Director or not */
if (categoryName($user['UserParams']['EmploymentLevel']) == 'Director') {
    $access = 1;
} else {
    $access = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>assets/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>assets/css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>assets/css/font-awesome.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>assets/css/style.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>assets/css/jquery-ui.min.css"/>
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/jqueryAjax.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/cashman.js"></script>
        <!-- Add conditional Javascript files according to the webpage -->
        <?php addScriptsFiles($page); ?>
    </head>
    <body>
        <header>
            <div class="container-fluid"> <img src="<?php echo site_url(); ?>assets/images/logo.png" alt="Cashmann web application"/>
                <div class="col-md-4 col-sm-4 col-xs-12 pull-right account">
                    <div class="col-md-12 my-account  text-right">
                        <a href="<?php echo site_url() . 'profile/' . $id; ?>">
                            <img src="<?php echo site_url(); ?>assets/images/acount.png" />
                            <?php echo $this->lang->line('BUTTON_MY_ACCOUNT'); ?>
                        </a>&nbsp;&nbsp;&nbsp;
                        <a href="<?php echo site_url(); ?>logout">
                            <img src="<?php echo site_url(); ?>assets/images/log_out.png" />
                            <?php echo $this->lang->line('BUTTON_LOGOUT'); ?>
                        </a>&nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="col-md-12 text-right">
                        <p class="text-bottom">Hi <span><?php echo $user['Name']; ?>
                    </div>
                </div>
            </div>
        </header>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid"> 
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="row">
                    <div class="col-md-9">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li class="li_bg<?php echo ($page == 'accountant_dashboard') ? ' active' : ''; ?>">
                                    <a href="<?php echo site_url(); ?>dashboard">
                                        Dashboard 
                                    </a>
                                </li>
                                <li class="li_bg<?php echo ($page == 'client_listing') ? ' active' : ''; ?>">
                                    <a href="<?php echo site_url(); ?>client_listing">
                                        Clients
                                    </a>
                                </li>
                                <?php if ($access): ?>
                                    <li class="li_bg<?php echo ($page == 'accountants') ? ' active' : ''; ?>">
                                        <a href="<?php echo site_url(); ?>accountants">
                                            Accountants
                                        </a>
                                    </li>
									<li class="li_bg<?php echo ($page == 'BulkUpload') ? ' active' : ''; ?>">
                                        <a href="<?php echo site_url(); ?>bulkupload">
                                            Bulk Upload
                                        </a>
                                    </li>
                                    <li class="li_bg<?php echo ($page == 'email') ? ' active' : ''; ?>">
                                        <a href="<?php echo site_url(); ?>email">
                                            Bulk Email
                                        </a>
                                    </li>
                                    <li class="li_bg<?php echo ($page == 'configuration') ? ' active' : ''; ?>">
                                        <a href="<?php echo site_url(); ?>configuration">
                                            Configuration
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="li_bg<?php echo ($page == 'terms_conditions') ? ' active' : ''; ?>">
                                    <a href="<?php echo site_url(); ?>terms_conditions">
                                        Terms And Conditions
                                    </a>
                                </li>
                                <!--<li class="li_bg<?php echo ($page == 'email') ? ' active' : ''; ?>">
                                    <a href="<?php echo site_url(); ?>email">
                                        Email
                                    </a>
                                </li>-->
                            </ul>
                        </div>
                        <!-- /.navbar-collapse --> 
                    </div>
                </div>
            </div>
            <div class="blue"></div>
            <!-- /.container-fluid --> 
        </nav>