<div class="alldiv flex widget_title">
    <h3>View Role</h3>
    <div class="title_right">
    <a href="<?php echo base_url().'erp/setting/roles' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>

<div class="alldiv flex">
    <div class="form-width-1">
        <p><?php echo $role->role_name; ?></p>
    </div>
    <div class="form-width-1">
        <p><?php echo $role->role_desc; ?></p>
    </div>
    <div class="form-width-1">
        <div class="table_responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Menus</th>
                        <th>View Global</th>
                        <th>View Own</th>
                        <th>Create</th>
                        <th>Update</th>
                        <th>Delete</th>
                        <th>Misc</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $perms=json_decode($role->permissions,true)??array();
                    ?>
                    <tr>
                        <td>CRM Leads</td>
                        <td>
                        <?php
                            if(in_array("crm_lead_view_global",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?> 
                        </td>
                        <td>
                        <?php
                            if(in_array("crm_lead_view_own",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?> 
                        </td>
                        <td>
                        <?php
                            if(in_array("crm_lead_create",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?> 
                        </td>
                        <td>
                        <?php
                            if(in_array("crm_lead_update",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?> 
                        </td>
                        <td>
                        <?php
                            if(in_array("crm_lead_delete",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?> 
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>CRM Customers</td>
                        <td>
                        <?php
                            if(in_array("crm_customer_view_global",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?>
                        </td>
                        <td>
                        <?php
                            if(in_array("crm_customer_view_own",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?>
                        </td>
                        <td>
                        <?php
                            if(in_array("crm_customer_create",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?>
                        </td>
                        <td>
                        <?php
                            if(in_array("crm_customer_update",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?>
                        </td>
                        <td>
                        <?php
                            if(in_array("crm_customer_delete",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Notifications</td>
                        <td>
                        <?php
                            if(in_array("notify_view_global",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?>
                        </td>
                        <td>
                        <?php
                            if(in_array("notify_view_own",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?>
                        </td>
                        <td>
                        <?php
                            if(in_array("notify_create",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?>
                        </td>
                        <td>
                        <?php
                            if(in_array("notify_update",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?>
                        </td>
                        <td>
                        <?php
                            if(in_array("notify_delete",$perms)){  ?>
                                <i class="fa fa-check text-success"></i>
                        <?php    }else{  ?>
                            <i class="fa fa-times text-danger"></i>
                        <?php
                            }
                        ?>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>





<!--SCRIPT WORKS -->
</div>
    </main>
    <script src="<?php echo base_url().'assets/js/jquery.min.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/script.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/erp.js' ;?>" ></script>
    <script type="text/javascript">
        <?php
            if(session()->getFlashdata("op_success")){ ?>
                let alerts=new ModalAlert();
                alerts.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>