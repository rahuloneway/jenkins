<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$this->load->view('client/header', array('page' => 'suppliers', 'title' => $title));
error_reporting(0);
?>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum">
            <h4><?php echo $this->lang->line('SUPPLIERS_PAGE_TITLE'); ?></h4>
            <?php echo $this->session->flashdata('suppliersMessage'); ?>
            <div class="panel panel-default panel_custom">
                <div class="panel-body ">
                    <div class="row">
                        <div class="col-md-6 col-sm-3 col-xs-12 padding_field">
                            <div class="wid-30">
                                <label>Supplier name</label>
                            </div>
                            <div class="wid-40">
                                <?php
                                echo form_open(site_url() . 'suppliers', array('name' => 'supplier-search', 'id' => 'supplier-search'));
                                ?>
                                <select class="form-control" id="suppplier" name="suppplier">
                                    <option selected="selected" value="0">Select Supplier</option>
                                    <?php
                                    if (!empty($supplierlist)) {
                                        foreach ($supplierlist as $key => $value) {
                                            if ($value['Id'] == $supplierId) {
                                                $sel = 'selected="selected"';
                                            } else {
                                                $sel = '';
                                            }
                                            echo '<option ' . $sel . ' value="' . $value['Id'] . '" >' . $value['suppliername'] . '</option>';
                                        }
                                    } else {
                                        
                                    }
                                    ?>
                                </select>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="clearfix"></div>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-2  col-sm-3 col-xs-12 btn_centre_bot"> 
                        <a href="#" type="button" class="btn btn-inverse add-supplier">
                            <span class="glyphicon glyphicon-plus"></span>
                            Add supplier		
                        </a> 
                    </div>
                    <div class="col-md-8 pull-right bPagination">
                        <?php
                        echo $pagination . '<br/>';
                        ?>
                    </div>
                </div>
            </div>
            <div class="listing-container"> 
                <div id="reset-position" class="table-responsive">  
                    <table id="example-advanced">
                        <thead>
                        <tr class="table-header">
                            <th><?php echo $this->lang->line('SUPPLIERS_ID'); ?></th>
                            <th><center><?php echo $this->lang->line('SUPPLIERS_NAME'); ?></center></th>
                            <th><center><?php echo $this->lang->line('SUPPLIERS_COMPANY'); ?></center></th>
                            <th><center><?php echo $this->lang->line('SUPPLIERS_EMAIL'); ?></center></th>
                            <th><center><?php echo $this->lang->line('SUPPLIERS_MOBILE'); ?></center></th>
                            <th><center><?php echo $this->lang->line('SUPPLIERS_DATE'); ?></center></th>
                            <th><center><?php echo $this->lang->line('SUPPLIERS_ADD_STATUS'); ?></center></th>
                            <th><center><?php echo $this->lang->line('SUPPLIERS_ACTION'); ?></center></th>
                        </tr>
                        </thead>
                        <tbody id="journal-listing">
                            <?php $this->load->view('client/suppliers/listing', $data); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-2  col-sm-3 col-xs-12 btn_centre_bot"> 
                        <a href="#" type="button" class="btn btn-inverse add-supplier">
                            <span class="glyphicon glyphicon-plus"></span>
                            Add supplier
                        </a> 
                    </div>
                    <div class="col-md-8 pull-right bPagination">
                        <?php
                        echo $pagination . '<br/>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<div class="modal fade modal-supplier-form" id="modal-supplier-form" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width:50%; height: auto; max-height: 100%;">
        <form id="frm-supplier" name="frm-supplier" method="POST" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body"></div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <a id="create-supplier" class="btn btn-success btn-sm spacer" href="#">

                        </a>
                        <a href="#" class="btn btn-danger " data-dismiss="modal">
                            <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;Close</a>
                    </div>
                    <div class="pull-right">

                    </div>
                </div>
            </div>
        </form>

    </div>
    <div id="dialog"></div>	
</div>
<?php $this->load->view('client/footer'); ?>
