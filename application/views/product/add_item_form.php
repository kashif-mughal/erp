
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product.js" type="text/javascript"></script>
<!-- Add Product Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('new_product') ?></h1>
            <small><?php echo display('add_new_product') ?></small>
            <ol class="breadcrumb">
                <li><a href="index.html"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('product') ?></a></li>
                <li class="active"><?php echo display('new_product') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">

        <!-- Alert Message -->
        <?php
        $message = $this->session->userdata('message');
        if (isset($message)) {
            ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $message ?>                    
            </div>
            <?php
            $this->session->unset_userdata('message');
        }
        $error_message = $this->session->userdata('error_message');
        if (isset($error_message)) {
            ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $error_message ?>                    
            </div>
            <?php
            $this->session->unset_userdata('error_message');
        }
        ?>

       

        <!-- Add Product -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('new_product') ?></h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('Cproduct/insert_item', array('class' => 'form-vertical', 'id' => 'insert_item', 'name' => 'add_item')) ?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="product_name" class="col-sm-4 col-form-label"><?php echo display('product_name') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="product_name" type="text" id="product_name" placeholder="<?php echo display('product_name') ?>" required tabindex="1" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="serial_no" class="col-sm-4 col-form-label"><?php echo display('serial_no') ?> </label>
                                    <div class="col-sm-8">
                                        <input type="text" tabindex="" class="form-control" id="serial_no" name="serial_no" placeholder="<?php echo display('serial_no') ?>" tabindex="2"   />
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="product_model" class="col-sm-4 col-form-label"><?php echo display('model') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <input type="text" tabindex="" class="form-control" id="product_model" name="model" placeholder="<?php echo display('model') ?>" required tabindex="3"   />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="category_id" class="col-sm-4 col-form-label"><?php echo display('category') ?></label>
                                    <div class="col-sm-8">

                                        <select class="form-control" id="category_id" name="category_id" tabindex="4">
                                            <option value=""></option>
                                            <?php if ($category_list) { 
                                                    foreach ($category_list as $key => $value) {
                                                ?>
                                                
                                                <option value="<?=$value['category_id']?>"><?=$value['category_name']?></option>
                                              
                                            <?php } 
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>                        

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="sell_price" class="col-sm-4 col-form-label"><?php echo display('sell_price') ?> <i class="text-danger">*</i> </label>
                                    <div class="col-sm-8">
                                        <input class="form-control text-right" id="sell_price" name="price" type="text"  placeholder="0.00" tabindex="5" min="0">
                                    </div>
                                </div> 
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="unit" class="col-sm-4 col-form-label"><?php echo display('unit') ?></label>
                                    <div class="col-sm-8">
                                        <? //print_r($unit_list) ?>
                                        <select class="form-control" id="unit" name="unit" tabindex="-1" aria-hidden="true">
                                            <option value="">Select One</option>
                                            <?php if ($unit_list) { ?>
                                                <?php foreach($unit_list as $key => $value) ?>
                                                <option value="<?=$value['unit_id']?>"><?=$value['unit_name']?></option>
                                                <?php ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="image" class="col-sm-4 col-form-label"><?php echo display('image') ?> </label>
                                    <div class="col-sm-8">
                                        <input type="file" name="image" class="form-control" id="image" tabindex="7">
                                    </div>
                                </div> 
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="tax" class="col-sm-4 col-form-label"><?php echo display('tax') ?> </label>
                                    <div class="col-sm-8">
                                        <?php //($tax_list);?>
                                        <select name="tax" id="tax" class="form-control dont-select-me" required="" tabindex="8">

                                            <option value=" "><?php echo display('select_one') ?></option>
                                            <?php if ($tax_list) { ?>
                                                <? foreach ($tax_list as  $tax) { ?>
                                                  
                                                <option value="<?=$tax->tax?>"><?=$tax->tax?>%</option>
                                                
                                            <?php }  } ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                          
                        </div> 


                        <div class="table-responsive" style="margin-top: 10px">
                            <table class="table table-bordered table-hover"  id="product_table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Is Special<i class="text-danger"></i></th>
                                        <th class="text-center">Code <i class="text-danger">*</i></th>
                                        <th class="text-center">Color <i class="text-danger">*</i></th>


                                        <th class="text-center">Quarter Price <i class="text-danger"></i></th>
                                        <th class="text-center">Gallon Price <i class="text-danger"></i></th>
                                        <th class="text-center">Drum Price <i class="text-danger"></i></th>
                                        <th class="text-center">Action <i class="text-danger"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="proudt_item">
                                    <tr class="">
                                        <td>
                                           <input style="margin-top: 0px;" type="checkbox" tabindex="9" class="form-control text-right" name="special[]"/>
                                        </td>
                                        <td>
                                           <input type="text" tabindex="9" class="form-control text-right" name="item_code[]" placeholder="Code"    min="0"/>
                                        </td>
                                        <td class="">
                                            <input type="text" tabindex="9" class="form-control text-right" name="item_color[]" placeholder="Color"    min="0"/>
                                        </td>
                                        <td class="">
                                            <input type="text" tabindex="9" class="form-control text-right" name="item_quarter[]" placeholder="0.00"    min="0"/>
                                        </td>
                                        <td class="">
                                            <input type="text" tabindex="9" class="form-control text-right" name="item_gallon[]" placeholder="0.00"    min="0"/>
                                        </td>
                                        <td class="">
                                            <input type="text" tabindex="9" class="form-control text-right" name="item_drum[]" placeholder="0.00"    min="0"/>
                                        </td>

                                        <td> 
<button type="button" id="add_purchase_item" class="btn btn-info" name="add-invoice-item" onClick="addpruduct('proudt_item');"  tabindex="9"/><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                                         <button class="btn btn-danger red" type="button" value="<?php echo display('delete') ?>" onclick="deleteRow(this)" tabindex="10"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <center><label for="description" class="col-form-label"><?php echo display('product_details') ?></label></center>
                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="<?php echo display('product_details') ?>" tabindex="25"></textarea>
                            </div>
                        </div><br>
                        <div class="form-group row">
                            <div class="col-sm-6">

                                <input type="submit" id="add-product" class="btn btn-primary btn-large" name="add-product" value="<?php echo display('save') ?>" tabindex="10"/>
                                <input type="submit" value="<?php echo display('save_and_add_another') ?>" name="add-product-another" class="btn btn-large btn-success" id="add-product-another" tabindex="9">
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Add Product End -->



