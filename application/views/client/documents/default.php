<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	$this->load->view('client/header',array('page'=>$page,'title'=>$title));
	$this->load->helper('directory');
	$user = $this->session->userdata('user');
	
	/* Check user access for creating folder */
	if(isset($user['AccountantAccess']))
	{
		$access = 1;
	}else{
		$access = 0;
	}
	
	$folders = folder();
	$path = 'assets/uploads/documents/';
	$folder_list = folder('parent');
	//prd(folder());
?>
<section class="grey-body expenses_middle">
	<div class="container-fluid ">
		<div class="account_sum">
			<h4><?php echo $this->lang->line('DOCUMENT_PAGE_TITLE');?></h4>
			<div class="clr"></div>
			<?php echo $this->session->flashdata('uploadDocumentError');?>
			<?php if(!empty($access)):?>
			<?php echo form_open_multipart(site_url().'clients/documents/uploadDocuments',array('id'=>'mydocuments','name'=>'mydocuments'));?>
			<div class="panel panel-default panel_custom">
				<div class="panel-body">
					<div class="col-md-4 col-sm-3 col-xs-12 padding_field ">
						<div class="col-md-3">
							<label><?php echo $this->lang->line('DOCUMENT_LABEL_NAME');?></label>
						</div>
						<div class="col-md-9">
							<?php echo form_dropdown('documentsCategory',folder(),'','id="documentsCategory"');?>
						</div>
					</div>
					<div class="col-md-4 ">
						<div class="browse-file">
							<input type="file" name="file" id="file" class="filestyle" data-buttonName="btn-primary"accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" value=""/>
							<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
						</div>
					</div>
					<div class="browse-file col-md-1">
						<button id="uploadDocument" class="btn btn-primary" type="submit">
							<i class="fa fa-upload"></i> <?php echo $this->lang->line('DOCUMENT_BUTTON_UPLOAD');?>
						</button>
					</div>
				</div>
			</div>
			<input type="hidden" name="operation" value="" id="operation"/>
			<?php echo form_close();?>
			<?php endif;?>
			<div class="clr"></div><br/>
			<?php if($access):?>
			<div class="col-md-12">
				<a class="btn btn-success btn-sm" id="addFolder">
					<i class="fa fa-folder-open"></i><?php echo $this->lang->line('DOCUMENT_BUTTON_ADD_FOLDER');?>
				</a>
			</div>
			<?php endif;?>
			<div class="clr"></div><br/>
			<div class="panel panel-default panel_custom no-margin">
				<div class="row no-margin">
					<table id="example-advanced">
						<thead>
							<tr class="salary-table">
								<th>
									<?php echo $this->lang->line('DOCUMENT_LABEL_NAME');?>
								</th>
								<th width="100">
									<?php echo $this->lang->line('DOCUMENT_LABEL_KIND');?>
								</th>
								<th width="200">
									<?php echo $this->lang->line('DOCUMENT_LABEL_SIZE');?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$p = 1;
								/* Getting the uplaoded file & folder */
								//prd($directory_structure);
								if(count($directory_structure) > 0):
								foreach($directory_structure as $key=>$val)
								{
									?>
									<tr data-tt-id='<?php echo $p;?>' class="active">
										<td>
											<span class='folder'><?php echo '&nbsp;&nbsp;'.$folder_list[$key];?></span>
										</td>
										<td><?php echo $this->lang->line('DOCUMENT_LABEL_FOLDER');?></td>
										<td>--</td>
									</tr>
									<?php
									$cf = 1;
									if(is_array($val) && count($val) > 0)
									{
										//echo '<pre>';print_r($val);echo '</pre>';die;
										foreach($val as $k=>$v)
										{
											if($v->DType == 'FOLDER')
											{
												if(!file_exists($path.$folder_list[$key].'/'.$user['UserID'].'/'.$v->FolderName))
												{
													continue 1;
												}
												$link = $this->encrypt->encode($path.$folder_list[$key].'/'.$user['UserID'].'/'.$v->FolderName.'/'.$v->ID);
												$link = site_url().'clients/documents/deleteFolder/'.$link;
												?>
												<tr data-tt-id='<?php echo $p;?>-<?php echo $cf;?>' class="active" data-tt-parent-id='<?php echo $p;?>'>
													<td>
														<span class='folder'>
															<?php echo '&nbsp;&nbsp;'.$v->FolderName;?>
														</span>
														<?php if(!empty($access)):?>
														<span>
															<a class="del-f-button" data-toggle="tooltip" title="Delete" href="<?php echo $link;?>">
																<i class="fa fa-trash-o"></i>
															</a>
														</span>
														<?php endif;?>
													</td>
													<td><?php echo $this->lang->line('DOCUMENT_LABEL_FOLDER');?></td>
													<td>--</td>
												</tr>
												<?php
												//echo "<pre>";print_r($v->Files);echo "</pre>";
												/* Check if this folder have files */
												if(count($v->Files) > 0)
												{
													$files = $v->Files;
													$sf = 1;
													//echo '<br/>Count : '.count($files);
													//echo '<pre>';print_r($files);echo '</pre>';
													foreach($files as $a=>$b)
													{
														//echo '<br/>Key : '.$a;
														if(!file_exists($path.$folder_list[$key].'/'.$user['UserID'].'/'.$v->FolderName.'/'.$b->FName))
														{
															continue 1;
														}
														$dpath = $folder_list[$key].'/'.$user['UserID'].'/'.$v->FolderName.'/'.$b->ID;
														$link = $this->encrypt->encode($dpath);
														$link = site_url().'clients/documents/deleteFile/'.$link;
														
														$did = $this->encrypt->encode($dpath);
														?>
															<tr data-tt-id='<?php echo $p;?>-<?php echo $cf;?>-<?php echo $a;?>' class="active" data-tt-parent-id='<?php echo $p;?>-<?php echo $cf;?>'>
																<td>
																	<span class='fa fa-file-text-o'>
																		<a href="<?php echo site_url().'clients/documents/download/'.$did;?>">	
																		<?php echo $b->FName;?>
																		</a>
																	</span>
																	<?php if(!empty($access)):?>
																	<span>
																		<a class="del-button" data-toggle="tooltip" title="Delete" href="<?php echo $link;?>">
																			<i class="fa fa-trash-o"></i>
																		</a>
																	</span>
																	<?php endif;?>
																</td>
																<td><?php echo $this->lang->line('DOCUMENT_LABEL_FILE');?></td>
																<td>
																	<?php
																	//prd($v);
																		echo number_format((($b->FSize/(1024))/(1024)),2,'.','').'MB';
																	?>
																</td>
															</tr>
														<?php
														$sf++;
													}
												}
											}else{
												//echo '<br/>Type : '.$v->FName;
												if(!file_exists($path.$folder_list[$key].'/'.$user['UserID'].'/'.$v->FName))
												{
													continue 1;
												}
												$link = $this->encrypt->encode($folder_list[$key].'/'.$user['UserID'].'/'.$v->ID);
												$link = site_url().'clients/documents/deleteFile/'.$link;
												$did = $this->encrypt->encode($folder_list[$key].'/'.$user['UserID'].'/'.$v->ID);
												?>
													<tr data-tt-id='<?php echo $p;?>-<?php echo $cf;?>' class="active" data-tt-parent-id='<?php echo $p;?>'>
														<td>
															<span class='fa fa-file-text-o'>
																<a href="<?php echo site_url().'clients/documents/download/'.$did;?>">	
																<?php echo $v->FName;?>
																</a>
															</span>
															<?php if(!empty($access)):?>
															<span>
																<a class="del-button" data-toggle="tooltip" title="Delete" href="<?php echo $link;?>">
																	<i class="fa fa-trash-o"></i>
																</a>
															</span>
															<?php endif;?>
														</td>
														<td><?php echo $this->lang->line('DOCUMENT_LABEL_FILE');?></td>
														<td>
															<?php
																echo number_format((($v->FSize/(1024))/(1024)),2,'.','').'MB';
															?>
														</td>
													</tr>
											<?php
											}
											$cf++;
										}
									}else{
										//echo '<br/>None';
									}
									$p++;
								}
								else:
									foreach($folder_list as $key=>$val):
										?>
										<tr data-tt-id='<?php echo $p;?>' class="active">
											<td>
												<span class='folder'><?php echo '&nbsp;&nbsp;'.$val;?></span>
											</td>
											<td><?php echo $this->lang->line('DOCUMENT_LABEL_FOLDER');?></td>
											<td>--</td>
										</tr>
										<?php
									endforeach;
								endif;
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<div id="dialog"></div>	
<div class="modal fade modal-folder" id="modal-folder"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE');?></span>
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('DOCUMENT_NEW_FOLDER_TITLE');?></h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<script>
 $("#example-advanced").treetable({ expandable: true });
</script>
<?php $this->load->view('client/footer');?>