<!-- Edit supplier page start -->

<div class="content-wrapper">

    <section class="content-header">

        <div class="header-icon">

            <i class="pe-7s-note2"></i>

        </div>

        <div class="header-title">

            <h1>Employee</h1>

            <small>Employee Edit</small>

            <ol class="breadcrumb">

                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>

                <li><a href="#"><?php echo display('employee') ?></a></li>

                <li class="active"><?php echo display('edit') ?></li>

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



        <!-- New supplier -->

        <div class="row">

            <div class="col-sm-12">

                <div class="panel panel-bd lobidrag">

                    <div class="panel-heading">

                        <div class="panel-title">

                            <h4>Employee Edit </h4>

                        </div>

                    </div>

                    <?php 
                   //print_r($employee_list);
                    echo form_open_multipart('Cemployee/employee_update',array( 'id' => 'employee_update'))?>

                    <div class="panel-body">



                    	<div class="form-group row">

                            <label for="supplier_name" class="col-sm-3 col-form-label"><?php echo display('name') ?> <i class="text-danger">*</i></label>

                            <div class="col-sm-6">

                                <input class="form-control" name ="employee_name" id="employee_name" type="text" value="<?=$employee_list[0]['employee_name']?>" placeholder="<?php echo display('name') ?>"  required="" tabindex="1">

                            </div>

                        </div>



                        <div class="form-group row">

                            <label for="mobile" class="col-sm-3 col-form-label"><?php echo display('mobile') ?> <i class="text-danger">*</i></label>

                            <div class="col-sm-6">

                                <input class="form-control" name="mobile" id="mobile" type="text" placeholder="<?php echo display('mobile') ?>" value="<?=$employee_list[0]['employee_mobile']?>" required="" min="0" tabindex="2">

                            </div>

                        </div>

                        <div class="form-group row">

                            <label for="mobile" class="col-sm-3 col-form-label"><?php echo display('email') ?> <i class="text-danger">*</i></label>

                            <div class="col-sm-6">

                                <input class="form-control" name="email" id="email" type="text" placeholder="<?php echo display('mobile') ?>" value="<?=$employee_list[0]['employee_email']?>" required="" min="0" tabindex="3">

                            </div>

                        </div>

                        
                        <div class="form-group row">
                            <label for="vehicle" class="col-sm-3 col-form-label">Vehicle</label>
                            <div class="col-sm-6">
                                <input class="form-control" name ="vehicle" id="vehicle" type="text" placeholder="Vehicle" min="0" tabindex="4" value="<?=$employee_list[0]['employee_vehicle']?>">
                            </div>
                        </div>


                        <div class="form-group row">

                            <label for="address " class="col-sm-3 col-form-label"><?php echo display('supplier_address') ?></label>

                            <div class="col-sm-6">

                                <textarea class="form-control" name="address" id="address " rows="3" placeholder="<?php echo display('address') ?>" tabindex="5"><?=$employee_list[0]['employee_address'];?></textarea>

                            </div>

                        </div>



                        

                        <input type="hidden" name="employee_id" value="<?=$employee_list[0]['employee_id']?>" />



                        <div class="form-group row">

                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>

                            <div class="col-sm-6">

                             <input type="submit" id="add-employee" class="btn btn-success btn-large" name="add-employee" value="<?php echo display('save_changes') ?>" tabindex="6" />

                         </div>

                     </div>

                 </div>

                 <?php echo form_close()?>

             </div>

         </div>

     </div>

 </section>

</div>

<!-- Edit supplier page end -->







