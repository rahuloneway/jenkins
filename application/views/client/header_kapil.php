<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	$user = $this->session->userdata('user');
	if(isset($user['AccountantAccess']))
	{
		$hi = "Signed In as ";
	}else{
		$hi = "Hi";
	}
	if( $user['AccountantAccess'] == '' && $user['UserType'] == 'TYPE_CLI' && $user['UserID'] != '')
	{
		$menus = getMenusByClientId($user['UserID']);
	}
	else
	{
		$menus = getAllMenus();
	}	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title;?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap-theme.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/font-awesome.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/jquery-ui.min.css"/>
	<script type="text/javascript" src="<?php echo site_url();?>assets/js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="<?php echo site_url();?>assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo site_url();?>assets/js/cashman.js"></script>
	<!-- Add conditional Javascript files according to the webpage -->
	
<?php addScriptsFiles($page);?>
	<script  id="script" type="text/javascript"></script>
</head>
<body>

<header>
  <div class="container-fluid padding-zero"> 
	<div class="col-md-3 col-sm-12">
		<img src="<?php echo site_url();?>assets/images/logo.png" alt="Cashmann web application"/>
	</div>
	<div class="col-md-4 col-sm-12 pull-left padding-zero">
			<div class=" col-md-8 col-sm-12 text-right pull-right">
				<b class="blue_bg"><?php echo ucfirst(companyName($user['CompanyID']));?></b>	
			</div>
		</div>
    <div class="col-md-5 col-sm-4 col-xs-12 pull-right account">
		<div class="col-md-12 my-account  text-right">
			<?php if(isset($user['AccountantAccess'])):?>
			<a href="<?php echo site_url().'accountant_view';?>" class="btn btn-success btn-xs color">
				<i class="glyphicon glyphicon-user"></i>Back to Accountant View
			</a>&nbsp;
			<?php endif;?>
			<!--a href="#">
				<img src="<?php echo site_url();?>assets/images/acount.png" />
				<?php //echo $this->lang->line('BUTTON_MY_ACCOUNT');?>
			</a-->&nbsp;&nbsp;&nbsp;
			<a href="<?php echo site_url();?>logout">
				<img src="<?php echo site_url();?>assets/images/log_out.png" />
				<?php echo $this->lang->line('BUTTON_LOGOUT');?>
			</a>&nbsp;&nbsp;&nbsp;
		</div>
		<div class="col-md-12 text-right headr">
			<p class="text-bottom">
				<?php echo $hi;?> 
				<span>
					<?php echo ucfirst($user['Name']);?>
				</span> (Company Reg. No.<?php echo $user['CompanyRegNo'];?>, Vat Reg. No.<?php echo $user['Params']['VATRegistrationNo'];?>)</p>
		</div>
		<br/>
    </div>
  </div>
</header>

<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid"> 
    <!-- Brand and toggle get grouped for better mobile display -->
	
    <div class="row">
      <div class="col-md-12">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        </div>
        <?php 	//foreach( $menus as $menu){ echo "<pre>"; print_r($menu);	}die; ?>
        <!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="li_bg"><a href="<?php echo site_url('client');?>">Dashboard</a></li>
				<?php  
				if( !empty($menus)){
					foreach( $menus as $menu){ ?>						
						<li class="li_bg  <?php if( !empty($menu['subMenus'])){ ?>dropdown<?php } ?>">
							<a href="<?php echo site_url($menu['url']);?>" <?php if( !empty($menu['subMenus'])){ ?>class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"<?php } ?> ><?php echo ucfirst($menu['title']);?> <?php if( !empty($menu['subMenus'])){ ?><b class="caret"></b><?php } ?></a>
							<?php if( !empty($menu['subMenus'])){ ?>
							<ul  class="dropdown-menu">
							<?php	foreach($menu['subMenus'] as $subMenu){ ?>
								<li class="<?php echo ($page == 'customers') ? ' active' : ''; ?>">
									<a href="<?php echo site_url($subMenu['url']);?>">
									   <?php echo ucfirst($subMenu['title']);?>
									</a>
								</li>
							<?php } ?>	
							</ul>
							<?php } ?>
						</li>
				<?php 	} 
					}?>
				
			</ul>
		</div>
        <!-- /.navbar-collapse --> 
      </div>
		
    </div>
  </div>
  <div class="blue">
	<?php if($page == 'expenses' || $page == 'upload_expense'|| $page == 'expense_report'):?>
	<div class="col-md-5 col-md-offset-3 listing">
		<a href="#" class="uploadExpenses">
			<i class="fa fa-upload"></i>
			Upload Expenses
		</a>
		<a href="#" class="creditStatements">
			<i class="fa fa-upload"></i>
			Upload Credit Card Statement
		</a>
		<a href="<?php echo site_url();?>expense_report" class="expenseReport <?php echo ($page == 'expense_report')?' active':'';?>">
			<i class="fa fa-table "></i>
			<span>Expense Report</span>
		</a>
	</div>
	<?php elseif($page == 'banks' || $page == 'bank_statements'):?>
	<!-- div class="col-md-3 col-md-offset-1 listing">
		<a href="#" class="uploadStatements">
			<i class="fa fa-upload"></i>
			Upload Bank Statement 
		</a>
	</div -->
	<?php elseif ($page == 'journals'): ?>
		<div class="col-md-5 col-md-offset-3 listing listing_journ" style="margin-left:42%">
			<a href="#" class="uploadJournles">
				<i class="fa fa-upload"></i>
				Upload Journals
			</a>

		</div>
	<?php endif;?>
  </div>
  <!-- /.container-fluid --> 
</nav>

<?php echo $this->session->flashdata('adminDashboard');?>