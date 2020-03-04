<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product.js" type="text/javascript"></script>

<!-- Edit Product Start -->

<div class="content-wrapper">

    <section class="content-header">

        <div class="header-icon">

            <i class="pe-7s-note2"></i>

        </div>

        <div class="header-title">

            <h1><?php echo display('product_edit') ?></h1>

            <small><?php echo display('edit_your_product') ?></small>

            <ol class="breadcrumb">

                <li><a href="index.html"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>

                <li><a href="#"><?php echo display('product') ?></a></li>

                <li class="active"><?php echo display('product_edit') ?></li>

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

        <!-- Purchase report -->

        <div class="row">

            <div class="col-sm-12">

                <div class="panel panel-bd lobidrag">

                    <div class="panel-heading">

                        <div class="panel-title">

                            <h4><?php echo display('product_edit') ?> </h4>

                        </div>

                    </div>

                    <?php echo form_open_multipart('Cproduct/item_update', array('class' => 'form-vertical', 'id' => 'product_update', 'name' => 'product_update')) ?>

                    <div class="panel-body">

                        <div class="row">

                            <div class="col-sm-6">

                                <div class="form-group row">

                                    <label for="product_name" class="col-sm-4 col-form-label"><?php echo display('product_name') ?> <i class="text-danger">*</i></label>

                                    <div class="col-sm-8">

                                        <input class="form-control" name="product_name" type="text" id="product_name" placeholder="<?php echo display('product_name') ?>" required tabindex="1" value="<?=$product_name?>" >

                                        <input type="hidden" name="product_id" value="<?=$product_id?>">

                                    </div>

                                </div>

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group row">

                                    <label for="serial_no" class="col-sm-4 col-form-label"><?php echo display('serial_no') ?> </label>

                                    <div class="col-sm-8">

                                        <input type="text" tabindex="" class="form-control" id="serial_no" name="serial_no" placeholder="<?php echo display('serial_no') ?>"  value="<?=$serial_no?>"/>

                                    </div>

                                </div>

                            </div>

                        </div>



                        <div class="row">

                            <div class="col-sm-6">

                                <div class="form-group row">

                                    <label for="product_model" class="col-sm-4 col-form-label"><?php echo display('model') ?> <i class="text-danger">*</i></label>

                                    <div class="col-sm-8">

                                        <input type="text" tabindex="" class="form-control" name="model" placeholder="<?php echo display('model') ?>"  required  value="<?=$product_model?>"/>

                                    </div>

                                </div>

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group row">

                                    <label for="category_id" class="col-sm-4 col-form-label"><?php echo display('category') ?></label>

                                    <div class="col-sm-8">

                                        <select class="form-control" id="category_id" name="category_id" tabindex="3">

                                            

                                            <?php
                                           // print_r($category_list);
                                            foreach ($category_list as $category){

                                                    if($selected_category_id == $category['category_id'])
                                                        $selected = "SELECTED";
                                                    else
                                                        $selected = "";
                                                ?>
 <option selected value="<?=$category['category_id']?>" <?=$selected?>><?=$category['category_name']?> </option>
                                              
                            <?php } ?>
                                        </select>

                                    </div>

                                </div>

                            </div>



                        </div>                        



                        <div class="row">

                            <div class="col-sm-6">

                                <div class="form-group row">

                                    <label for="image" class="col-sm-4 col-form-label"><?php echo display('sell_price') ?> <i class="text-danger">*</i> </label>

                                    <div class="col-sm-8">

                                        <input class="form-control text-right" name="price" type="text" required="" placeholder="0.00" tabindex="5" min="0" value="<?=$price?>">

                                    </div>

                                </div> 

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group row">

                                    <label for="unit" class="col-sm-4 col-form-label"><?php echo display('unit') ?></label>

                                    <div class="col-sm-8">

                                        <select class="form-control" id="unit" name="unit" tabindex="-1" aria-hidden="true">

                                            <option value="">Select One</option>

                                            <?php

                                            

                                            foreach ($unit_list as $single) {

                                                if ($single['unit_name'] == $unit) {

                                                    ?>

                                                    <option selected value="<?php echo $single['unit_name']; ?>">

                                                        <?php echo $single['unit_name']; ?>

                                                    </option>

                                                <?php } else { ?>

                                                    <option  value="<?php echo $single['unit_name']; ?>">

                                                        <?php echo $single['unit_name']; ?>

                                                    </option>

                                                    <?php

                                                }

                                            }

                                            ?>


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

                                        <input type="file" name="image" class="form-control" tabindex="4">

                                        <img class="img img-responsive text-center" src="<?=$image?>" height="80" width="80" style="padding: 5px;">

                                        <input type="hidden" value="<?=$image?>" name="old_image">

                                    </div>

                                </div> 

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group row">

                                    <label for="image" class="col-sm-4 col-form-label"><?php echo display('tax') ?> </label>

                                    <div class="col-sm-8">

                                     
                                        <input type="text" name="tax" class="form-control" value="<?=$tax_selecete?>">

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

                                   <?php foreach($sub_products as $code => $values){?>
                                    <tr class="">
                                        <td>
                                           <input style="margin-top: 0px;" type="checkbox" tabindex="9" class="form-control text-right" name="special[]" <?php if($values['special'] == 1){echo 'checked';}?>/>
                                        </td>
                                        <td>
                                           <input type="text" tabindex="9" class="form-control text-right" name="item_code[]" placeholder="Code" value='<?=$code?>'    min="0"/>
                                        </td>
                                        <td class="">
                                            <input type="text" tabindex="9" class="form-control text-right" name="item_color[]" placeholder="Color" value='<?=$values['color']?>'    />
                                        </td>
                                        <td class="">
                                            <input type="text" tabindex="9" class="form-control text-right" name="item_quarter[]" placeholder="0.00" value="<?=$values['unit']['Quarter']?>"    min="0"/>
                                        </td>
                                        <td class="">
                                            <input type="text" tabindex="9" class="form-control text-right" name="item_gallon[]" placeholder="0.00" value="<?=$values['unit']['Gallon']?>"    min="0"/>
                                        </td>
                                        <td class="">
                                            <input type="text" tabindex="9" class="form-control text-right" name="item_drum[]" placeholder="0.00" value="<?=$values['unit']['Drum']?>"   min="0"/>
                                        </td>
                                       

                                        <td> 
<button type="button" id="add_purchase_item" class="btn btn-info" name="add-invoice-item" onClick="addpruduct('proudt_item');"  tabindex="9"/><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                                         <button class="btn btn-danger red" type="button" value="<?php echo display('delete') ?>" onclick="deleteRow(this)" tabindex="10"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                   <?php } ?>
                                </tbody>

                            </table>

                        </div>

                        <div class="row">

                            <div class="col-sm-12">

                                <center><label for="description" class="col-form-label"><?php echo display('product_details') ?></label></center>

                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="<?php echo display('product_details') ?>" tabindex="2"><?=$product_details?></textarea>

                            </div>

                        </div><br>

                        <div class="form-group row">

                            <div class="col-sm-6">



                                <input type="submit" id="add-product" class="btn btn-primary btn-large" name="add-product" value="<?php echo display('save_changes') ?>" tabindex="10"/>



                            </div>

                        </div>

                    </div>

                    <?php echo form_close(); ?>

                </div>

            </div>

        </div>

    </section>

</div>

<!-- Edit Product End -->







