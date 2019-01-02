<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('accountant/header', array('page' => $page, 'title' => $title)); ?>
<section class="grey-body">
    <div class="container-fluid">
        <div class="account_sum">
            <div class="col-md-12 alert dashboardErrors">
                <?php echo $this->session->flashdata('dashboardErrors'); ?>
            </div>
            <ul class="nav nav-tabs">
                <li class="tab-bg active">
                    <a data-toggle="tab" href="#sectionA">Accounts Due</a>
                </li>
                <li class="tab-bg">
                    <a data-toggle="tab" href="#sectionB">Returns Due</a>
                </li>
                <li class="tab-bg">
                    <a data-toggle="tab" href="#sectionC">VAT Due</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="sectionA" class="tab-pane fade in active ">
                    <div class="table-responsive">
                          <?php $this->load->view('accountant/dashboard/annual_return'); ?>
                      
                    </div>
                </div>
                <div id="sectionB" class="tab-pane fade in  col-md-12">
                    <div class="table-responsive">
                           <?php $this->load->view('accountant/dashboard/final_account'); ?>
                       
                    </div>
                </div>
                <div id="sectionC" class="tab-pane fade in  col-md-12">
                    <div class="table-responsive">
                        <?php $this->load->view('accountant/dashboard/vatdue'); ?>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</section>
<div id="dialog"></div>
<?php $this->load->view('accountant/footer'); ?>