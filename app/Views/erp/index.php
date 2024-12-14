<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php //echo //$g_settings["company_name"]; 
        ?>
    </title>
    <link rel="icon" href="<?php echo base_url() . 'assets/images/favicon.png'; ?>" sizes="16x16 32x32"
        type="image/png" />
    <!-- <link rel="stylesheet" type="text/css" href="<?php //echo base_url() . 'assets/css/font-awesome.css';    ?>"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.0.1/skins/content/dark/content.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/style.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/custom-style.css'; ?>">

    <link rel="stylesheet" href="<?php echo base_url() . "assets/plugins/summernote/summernote.css" ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
    <script src="<?= base_url() . 'assets/js/notify.js' ?>"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url('assets/plugins/ckeditor5/ckeditor.js'); ?>"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.45/moment-timezone.min.js"></script>






</head>

<body>
    <!-----------Alert-------------- -->
    <div class="ModalAlert" style="z-index : 900;">
        <p class="textFlow st_success"></p>
        <a type="button" class="HoverA"><i class="fa fa-close"></i></a>
    </div>
    <!-----------Alert-------------- -->
    <nav class="sidebar poF <?php echo "active"; ?>">
        <div class="flex">
            <div class="logo">
                <img src="<?php echo get_logo_url(); ?>" alt="" class="img1 ">
                <img src="<?php echo base_url() . 'assets/images/favicon.png'; ?>" alt="" class="img2 ">
            </div>
            <a type="button" class="closeIcon2 HoverA"><i class="fa fa-close"></i></a>
        </div>
        <ul class="navbar">


            <li><a href="<?php echo url_to('erp.dashboard'); ?>" class="navLink"><i class="fa fa-dashboard"></i>
                    <span>Dashboard</span> </a></li>


            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle  <?php
            if ($menu == 'crm') {
                echo 'active';
            }
            ?>"><i class="fa fa-th"></i>
                    <span>CRM</span></a>
                <div class="navdown_container " <?php
                if ($menu == 'crm') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?php echo url_to('erp.crm.leads'); ?>" class="navLink <?php
                           if ($menu == 'crm' && $submenu == 'lead') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Leads</a></li>
                        <li><a href="<?php echo url_to('erp.crm.task'); ?>" class="navLink <?php
                           if ($menu == 'crm' && $submenu == 'task') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Task</a></li>
                        <li><a href="<?php echo url_to('erp.crm.customers'); ?>" class="navLink  <?php
                           if ($menu == 'crm' && $submenu == 'customer') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Customers</a></li>
                        <li><a href="<?php echo url_to('erp.crm.marketing'); ?>" class="navLink <?php
                           if ($menu == 'crm' && $submenu == 'marketing') {
                               echo 'active';
                           }
                           ?> "><i class="fa fa-circle-o"></i> Marketing</a></li>
                        <li><a href="<?php echo url_to('erp.crm.tickets'); ?>" class="navLink <?php
                           if ($menu == 'crm' && $submenu == 'support') {
                               echo 'active';
                           }
                           ?> "><i class="fa fa-circle-o"></i> Support</a></li>
                        <!-- <li><a href="<?php echo base_url() . 'erp/crm/request'; ?>" class="navLink <?php
                             if ($menu == 'crm' && $submenu == 'request') {
                                 echo 'active';
                             }
                             ?>"><i class="fa fa-circle-o"></i> Request</a></li> -->
                        <li><a href="<?php echo url_to('erp.crm.crmreport'); ?>" class="navLink <?php
                           if ($menu == 'crm' && $submenu == 'report') {
                               echo 'active';
                           }
                           ?> "><i class="fa fa-circle-o"></i> Reports</a></li>
                        <!-- <li><a href="#" class="navLink"><i class="fa fa-circle-o"></i> Reports</a></li> -->
                    </ul>
                </div>
            </li>


            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle <?php
            if ($menu == 'sale') {
                echo 'active';
            }
            ?>"><i class="fa fa-line-chart"></i> <span>Sales</span></a>
                <div class="navdown_container" <?php
                if ($menu == 'sale') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?php echo url_to('erp.sale.estimates'); ?>" class="navLink <?php
                           if ($menu == 'sale' && $submenu == 'estimate') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Estimates</a></li>

                        <li><a href="<?php echo url_to('erp.sale.quotations'); ?>" class="navLink <?php
                           if ($menu == 'sale' && $submenu == 'quotation') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Quotations</a></li>

                        <li><a href="<?php echo url_to('erp.sale.orders'); ?>" class="navLink <?php
                           if ($menu == 'sale' && $submenu == 'order') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Orders</a></li>

                        <li><a href="<?= url_to('erp.sale.invoice'); ?>" class="navLink <?php
                          if ($menu == 'sale' && $submenu == 'invoice') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> Invoices</a></li>

                        <li><a href="<?= url_to('erp.sale.payments'); ?>" class="navLink <?php
                          if ($menu == 'sale' && $submenu == 'payments') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> Payments</a></li>

                        <li><a href="<?= url_to('erp.sale.creditnotes'); ?>" class="navLink <?php
                          if ($menu == 'sale' && $submenu == 'creditnote') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> Credit Notes</a></li>

                        <!-- <li><a href="" class="navLink <?php
                        //    if ($menu == 'sale' && $submenu == 'request') {
                        //        echo 'active';
                        //    }
                        //       ?>"><i
                                    class="fa fa-circle-o"></i> Request</a></li> -->

                        <li><a href="<?php echo url_to('erp.sale.reports'); ?>" class="navLink <?php
                           if ($menu == 'sale' && $submenu == 'reports') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Reports</a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle  <?php
            if ($menu == 'finance') {
                echo 'active';
            }
            ?>"><i class="fa fa-university"></i> <span>Finance</span></a>
                <div class="navdown_container" <?php
                if ($menu == 'finance') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?= url_to('erp.finance.accountgroup') ?>" class="navLink <?php
                          if ($menu == 'finance' && $submenu == 'accountgroup') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> Account Group</a></li>
                        <li><a href="<?= url_to('erp.finance.glaccounts'); ?>" class="navLink <?php
                          if ($menu == 'finance' && $submenu == 'glaccount') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> GL Accounts </a></li>
                        <li><a href="<?= url_to('erp.finance.bankaccounts'); ?>" class="navLink <?php
                          if ($menu == 'finance' && $submenu == 'bankaccount') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> Bank Accounts</a></li>
                        <li><a href="<?php echo url_to('erp.finance.journalentry'); ?>" class="navLink <?php
                           if ($menu == 'finance' && $submenu == 'journalentry') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Journal Entry </a></li>
                        <li><a href="<?php echo url_to('erp.finance.automation'); ?>" class="navLink <?php
                           if ($menu == 'finance' && $submenu == 'automation') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Automation</a></li>
                        <li><a href="<?php echo url_to('erp.finance.paymentmode'); ?>" class="navLink <?php
                           if ($submenu == 'paymentmode') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Payment Mode</a></li>
                        <li><a href="<?php echo url_to('erp.finance.tax'); ?>" class="navLink <?php
                           if ($submenu == 'tax') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Tax</a></li>
                        <li><a href="<?php echo url_to('erp.finance.currency'); ?>" class="navLink <?php
                           if ($submenu == 'currency') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Currency</a></li>
                        <li><a href="<?php echo url_to('erp.finance.financereport'); ?>" class="navLink <?php
                           if ($submenu == 'financereport') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Reports</a></li>
                    </ul>
                </div>
            </li>


            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle <?php
            if ($menu == 'inventory') {
                echo 'active';
            }
            ?>"><i class="fa fa-cubes"></i>
                    <span>Inventory</span></a>
                <div class="navdown_container" <?php
                if ($menu == 'inventory') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?php echo url_to('erp.inventory.rawmaterials'); ?>" class="navLink <?php
                           if ($menu == 'inventory' && $submenu == 'rawmaterial') {
                               echo 'active';
                           } ?>"><i class="fa fa-circle-o"></i> Raw Materials</a>
                        </li>
                        <li><a href="<?php echo url_to('erp.inventory.semifinished'); ?>" class="navLink <?php
                           if ($menu == 'inventory' && $submenu == 'semifinished') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Semi Finished</a></li>
                        <li><a href="<?php echo url_to('erp.inventory.finishedgoods'); ?>" class="navLink <?php
                           if ($menu == 'inventory' && $submenu == 'finishedgood') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Finished Goods </a></li>
                        <li><a href="<?php echo url_to('erp.inventory.services'); ?>" class="navLink <?php
                           if ($menu == 'inventory' && $submenu == 'service') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Services </a></li>
                        <li><a href="<?php echo url_to('erp.inventory.propertytype'); ?>" class="navLink <?php
                           if ($menu == 'inventory' && $submenu == 'propertytype') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Property Type </a></li>
                        <li><a href="<?php echo url_to('erp.inventory.amenity'); ?>" class="navLink <?php
                           if ($menu == 'inventory' && $submenu == 'amenity') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Amenity </a></li>
                        <li><a href="<?php echo url_to('erp.inventory.property'); ?>" class="navLink <?php
                           if ($menu == 'inventory' && $submenu == 'property') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Property </a></li>
                        <li><a href="<?php echo url_to('erp.inventory.pricelist'); ?>" class="navLink <?php
                           if ($menu == 'inventory' && $submenu == 'pricelist') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Price List</a></li>
                        <li><a href="<?php echo url_to('erp.inventory.units'); ?>" class="navLink <?php
                           if ($menu == 'inventory' && $submenu == 'unit') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Units</a></li>
                        <li><a href="<?php echo url_to('erp.inventory.brands'); ?>" class="navLink <?php
                           if ($menu == 'inventory' && $submenu == 'brand') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Brands</a></li>


                        <!-- 
                        <li><a href="" class="navLink <?php
                        //  if ($menu == 'inventory' && $submenu == 'prod_manager') {
                        //      echo 'active';
                        //  }
                        //     ?>">
                             <i
                                    class="fa fa-circle-o"></i> Production Manager</a></li> -->



                    </ul>
                </div>
            </li>



            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle <?php
            if ($menu == 'warehouse') {
                echo 'active';
            }
            ?>"><i class="fa fa-home"></i>
                    <span>Warehouse</span></a>
                <div class="navdown_container" <?php
                if ($menu == 'warehouse') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?= url_to('erp.warehouses'); ?>" class="navLink <?php
                          if ($menu == 'warehouse' && $submenu == 'warehouse') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> All Warehouses</a></li>

                        <li><a href="<?= url_to('erp.warehouse.grn'); ?>" class="navLink <?php
                          if ($menu == 'warehouse' && $submenu == 'grn') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> GRN</a></li>

                        <!-- <li><a href="<?php echo base_url() . 'erp/warehouse/packs'; ?>" class="navLink <?php
                             if ($menu == 'warehouse' && $submenu == 'pack') {
                                 echo 'active';
                             }

                             ?>"><i class="fa fa-circle-o"></i> Packs</a></li> -->

                        <li><a href="<?= url_to('erp.warehouse.current_stock'); ?>" class="navLink <?php
                          if ($menu == 'warehouse' && $submenu == 'currentstock') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> Current Stock</a></li>

                        <li><a href="<?= url_to('erp.warehouse.managestock'); ?>" class="navLink <?php
                          if ($menu == 'warehouse' && $submenu == 'managestock') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> Manage Stock</a></li>

                        <li><a href="<?php echo base_url() . 'erp/warehouse/dispatch'; ?>" class="navLink <?php
                             if ($menu == 'warehouse' && $submenu == 'dispatch') {
                                 echo 'active';
                             }
                             ?>"><i class="fa fa-circle-o"></i> Dispatch</a></li>
                    </ul>
                </div>
            </li>


            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle <?php
            if ($menu == 'supplier') {
                echo 'active';
            }
            ?>"><i class="fa fa-id-badge"></i>
                    <span>Supplier</span></a>
                <div class="navdown_container" <?php
                if ($menu == 'supplier') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?= url_to('erp.supplier.page'); ?>" class="navLink <?php
                          if ($menu == 'supplier' && $submenu == 'supplier') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> Suppliers</a></li>
                        <li><a href="<?php echo base_url() . 'erp/supplier/sources'; ?>" class="navLink <?php
                             if ($menu == 'supplier' && $submenu == 'source') {
                                 echo 'active';
                             }
                             ?>"><i class="fa fa-circle-o"></i> Sources</a></li>
                        <li><a href="<?php echo url_to('erp.supplier.segments'); ?>" class="navLink <?php
                           if ($menu == 'supplier' && $submenu == 'segment') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Segments</a></li>
                        <li><a href="<?php echo url_to('erp.supplier.selectionrules'); ?>" class="navLink <?php
                           if ($menu == 'supplier' && $submenu == 'selectionrule') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Selection Rules</a></li>
                    </ul>
                </div>
            </li>


            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle <?php
            if ($menu == 'procurement') {
                echo 'active';
            }
            ?>"><i class="fa fa-shopping-cart"></i> <span>Procurement</span></a>
                <div class="navdown_container" <?php
                if ($menu == 'procurement') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?php echo url_to("erp.procurement.requisition"); ?>" class="navLink <?php
                           if ($menu == 'procurement' && $submenu == 'requisition') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Requisition</a></li>
                        <li><a href="<?php echo url_to('erp.procurement.rfq'); ?>" class="navLink <?php
                           if ($menu == 'procurement' && $submenu == 'rfq') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> RFQ</a></li>
                        <li><a href="<?php echo base_url() . 'erp/procurement/orders'; ?>" class="navLink <?php
                             if ($menu == 'procurement' && $submenu == 'order') {
                                 echo 'active';
                             }
                             ?>"><i class="fa fa-circle-o"></i> Order</a></li>
                        <li><a href="<?php echo url_to('erp.procurement.invoices'); ?>" class="navLink <?php
                           if ($menu == 'procurement' && $submenu == 'invoice') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Invoice</a></li>
                        <li><a href="<?php echo url_to('erp.procurement.returns'); ?>" class="navLink <?php
                           if ($menu == 'procurement' && $submenu == 'return') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Returns</a></li>
                        <li><a href="<?php echo url_to('erp.procurement.Report'); ?>" class="navLink <?php
                           if ($menu == 'procurement' && $submenu == 'Report') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Reports</a></li>
                    </ul>
                </div>
            </li>


            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle <?php
            if ($menu == 'transport') {
                echo 'active';
            }
            ?>"><i class="fa fa-truck"></i>
                    <span>Transport</span></a>
                <div class="navdown_container" <?php
                if ($menu == 'transport') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?= url_to('erp.transport.types'); ?>" class="navLink <?php
                          if ($menu == 'transport' && $submenu == 'type') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-circle-o"></i> Types</a></li>

                        <li><a href="<?php echo url_to('erp.transport.transports'); ?>" class="navLink <?php
                           if ($menu == 'transport' && $submenu == 'transport') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Transports</a></li>

                        <li><a href="<?php echo url_to('erp.transport.deliveryrecords'); ?>" class="navLink <?php
                           if ($menu == 'transport' && $submenu == 'deliveryrecord') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Delivery Records</a></li>
                    </ul>
                </div>
            </li>


            <!-- <li><a href="#" class="navLink"><i class="fa fa-chain"></i> <span>supply chain</span></a></li> -->


            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle <?php
            if ($menu == 'project') {
                echo 'active';
            }
            ?>"><i class="fa fa-tasks"></i>
                    <span>Project Management</span></a>
                <div class="navdown_container" <?php
                if ($menu == 'project') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?php echo url_to('erp.project.teams'); ?>" class="navLink <?php
                           if ($menu == 'project' && $submenu == 'team') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Teams</a></li>
                        <li><a href="<?php echo url_to('erp.project.workgroups'); ?>" class="navLink <?php
                           if ($menu == 'project' && $submenu == 'workgroup') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Work Groups</a></li>
                        <li><a href="<?php echo url_to('erp.project.projects'); ?>" class="navLink <?php
                           if ($menu == 'project' && $submenu == 'project') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Projects</a></li>
                    </ul>
                </div>
            </li>


            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle <?php
            if ($menu == 'service') {
                echo 'active';
            }
            ?>"><i class="fa-solid fa-building"></i>
                    <span>Service Management</span></a>
                <div class="navdown_container" <?php
                if ($menu == 'service') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?php echo url_to('erp.service.service'); ?>" class="navLink <?php
                           if ($menu == 'service' && $submenu == 'service-list') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Service List</a></li>
                        <li><a href="<?php echo url_to('erp.service.report'); ?>" class="navLink <?php
                           if ($menu == 'service' && $submenu == 'service-report') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Report</a></li>
                    </ul>
                </div>
            </li>

            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle <?php
            if ($menu == 'mrp') {
                echo 'active';
            }
            ?>"><i class="fa-solid fa-industry"></i>
                    <span>MRP</span></a>


                <div class="navdown_container" <?php
                if ($menu == 'mrp') {
                    echo 'style="display:block"';
                }
                ?>>

                    <ul>
                        <li><a href="<?= url_to('erp.mrp.dashboard') ?>" class="navLink <?php
                          if ($menu == 'mrp' && $submenu == 'dashboard') {
                              echo 'active';
                          }
                          ?>"><i class="fa fa-dashboard"></i>
                                <span>Dashboard</span></a></li>

                        <li><a href="<?= url_to('erp.mrp.forecasting') ?>" class="navLink <?php
                          if ($menu == 'mrp' && $submenu == 'forecasting') {
                              echo 'active';
                          }
                          ?>"><span>Forecasting</span></a></li>
                        <li><a href="<?= url_to('erp.mrp.planningschedule') ?>" class="navLink <?php
                          if ($menu == 'mrp' && $submenu == 'planning') {
                              echo 'active';
                          }
                          ?>"><span>MRP Planning</span></a></li>
                        <li><a href="<?= url_to('erp.mrp.worstationtype') ?>" class="navLink <?php
                          if ($menu == 'mrp' && $submenu == 'worstationtype') {
                              echo 'active';
                          }
                          ?>"><span>Workstation Type</span></a></li>
                        <li><a href="<?= url_to('erp.mrp.worstation') ?>" class="navLink <?php
                          if ($menu == 'mrp' && $submenu == 'worstation') {
                              echo 'active';
                          }
                          ?>"><span>Workstations</span></a></li>
                        <li><a href="<?= url_to('erp.mrp.overall.bom.list') ?>" class="navLink <?php
                          if ($menu == 'mrp' && $submenu == 'overallbomlist') {
                              echo 'active';
                          }
                          ?>"><span>BOM Overview</span></a></li>
                        
                        <li><a href="<?= url_to('erp.mrp.operation.list') ?>" class="navLink <?php
                          if ($menu == 'mrp' && $submenu == 'operation') {
                              echo 'active';
                          }
                          ?>"><span>Operation</span></a></li>
                    </ul>
                </div>


            <li><a href="<?= url_to('erp.assets'); ?>" class="navLink <?php
              if ($menu == 'asset') {
                  echo 'active';
              }
              ?>"><i class="fa fa-archive"></i>
                    <span>Equipments</span></a></li>

            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle <?php
            if ($menu == 'hr') {
                echo 'active';
            }
            ?>"><i class="fa fa-users"></i>
                    <span>Human Resource</span></a>
                <div class="navdown_container" <?php
                if ($menu == 'hr') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?php echo url_to('erp.hr.departments'); ?>" class="navLink <?php
                           if ($menu == 'hr' && $submenu == 'department') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Department</a></li>

                        <li><a href="<?php echo url_to('erp.hr.designation'); ?>" class="navLink <?php
                           if ($menu == 'hr' && $submenu == 'designation') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Designation</a></li>
                        <li><a href="<?php echo url_to('erp.hr.employees'); ?>" class="navLink <?php
                           if ($menu == 'hr' && $submenu == 'employee') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Employee</a></li>
                        <li><a href="<?php echo url_to('erp.hr.contractors'); ?>" class="navLink <?php
                           if ($menu == 'hr' && $submenu == 'contractor') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Contractor</a></li>
                        <li><a href="<?php echo url_to('erp.hr.attendance'); ?>" class="navLink <?php
                           if ($menu == 'hr' && $submenu == 'attendance') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Attendance</a></li>
                        <li><a href="<?php echo url_to('erp.hr.deductions'); ?>" class="navLink <?php
                           if ($menu == 'hr' && $submenu == 'deduction') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Deductions</a></li>
                        <li><a href="<?php echo url_to('erp.hr.additions'); ?>" class="navLink <?php
                           if ($menu == 'hr' && $submenu == 'addition') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Additions</a></li>
                        <li><a href="<?php echo url_to('erp.hr.payrolls'); ?>" class="navLink <?php
                           if ($menu == 'hr' && $submenu == 'payroll') {
                               echo 'active';
                           }
                           ?>"><i class="fa fa-circle-o"></i> Payroll</a></li>
                    </ul>
                </div>
            </li>


            <!-- Expenses -->
            <li>
            <li class="navdown">
                <a type="button" class="navdownBtn dropdownToggle">
                    <i class="fa-regular fa-file-lines menu-icon"></i>
                    <span>Expenses</span>
                </a>
                <div class="navdown_container" <?php
                if ($menu == 'Expenses') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?php echo url_to('erp.expensesview') ?>" class="navLink 
                        <?php if ($menu == 'Expenses' && $submenu === 'Expenses') {
                            echo 'active';
                        } ?>"><i class="fa fa-circle-o"></i> Expenses</a>
                        </li>
                        <li><a href="<?php echo url_to('erp.expensesreportview') ?>" class="navLink 
                        <?php if ($menu == 'Expenses' && $submenu === 'report') {
                            echo 'active';
                        } ?>"><i class="fa fa-circle-o"></i> Expenses Report</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- contracts -->
            <li><a href="<?php echo url_to('erp.contractview') ?>" class="navLink
             <?php if ($menu == 'contract') {
                 echo 'active';
             } ?>">
                    <i class="fa-solid fa-file-contract"></i>
                    <span>Contracts</span></a>
            </li>

            <!-- Goals -->
            <li><a href="<?php echo url_to('erp.goalsview') ?>" class="navLink
             <?php if ($menu == 'goals') {
                 echo 'active';
             } ?>">
                    <i class="fa-solid fa-bullseye"></i>
                    <span>Goals</span></a>
            </li>
            <!-- Announcement -->
            <li><a href="<?php echo url_to('erp.announcements') ?>" class="navLink
             <?php if ($menu == 'announcements') {
                 echo 'active';
             } ?>">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span>Announcement</span></a>
            </li>

            <!-- Knowledgebase -->
            <li class="navdown">
                <a type="button" class="navdownBtn dropdownToggle">
                    <i class="fa-solid fa-folder-closed"></i>
                    <span>Knowledge Base</span>
                </a>
                <div class="navdown_container" <?php
                if ($menu == 'knowledgebase') {
                    echo 'style="display:block"';
                }
                ?>>
                    <ul>
                        <li><a href="<?php echo url_to('erp.Knowledgebase') ?>" class="navLink 
                        <?php if ($menu == 'knowledgebase' && $submenu === 'knowledgebase') {
                            echo 'active';
                        } ?>"><i class="fa fa-circle-o"></i> Knowledge Base</a>
                        </li>
                        <li><a href="<?php echo url_to('erp.Knowledgebase.report') ?>" class="navLink 
                        <?php if ($menu == 'knowledgebase' && $submenu === 'report') {
                            echo 'active';
                        } ?>"><i class="fa fa-circle-o"></i> Knowledge Base Article</a>
                        </li>
                    </ul>
                </div>
            </li>




            <?php
            if (is_admin()) { ?>
                <li class="navdown"><a type="button" class="navdownBtn dropdownToggle"><i class="fa fa-gear"></i>
                        <span>Settings</span></a>
                    <div class="navdown_container">
                        <ul>
                            <li><a href="<?php echo url_to('erp.setting.users'); ?>" class="navLink <?php
                               if ($menu == 'setting' && $submenu === 'user') {
                                   echo 'active';
                               }
                               ?>"><i class="fa fa-circle-o"></i> Users</a></li>


                            <li><a href="<?php echo url_to('erp.setting.roles'); ?>" class="navLink <?php
                               if ($menu == 'setting' && $submenu === 'role') {
                                   echo 'active';
                               }
                               ?> "><i class="fa fa-circle-o"></i> Roles</a></li>


                            <li><a href="<?php echo url_to('erp.setting.smtp'); ?>" class="navLink  <?php
                               if ($menu == 'setting' && $submenu === 'smtp') {
                                   echo 'active';
                               }
                               ?>"><i class="fa fa-circle-o"></i> SMTP settings</a></li>



                            <li><a href="<?= url_to('email.templates.list'); ?>" class="navLink  <?php
                              if ($menu == 'setting' && $submenu === 'mailtemplate') {
                                  echo 'active';
                              }
                              ?>"><i class="fa fa-circle-o"></i> Email Templates</a></li>



                            <li><a href="<?php echo url_to('erp.setting.groups'); ?>" class="navLink  <?php
                               if ($menu == 'setting' && $submenu === 'group') {
                                   echo 'active';
                               }
                               ?>"><i class="fa fa-circle-o"></i> Groups</a></li>


                            <li><a href="<?php echo url_to('erp.setting.finance'); ?>" class="navLink <?php
                               if ($menu == 'setting' && $submenu === 'finance') {
                                   echo 'active';
                               }
                               ?>"><i class="fa fa-circle-o"></i> Finance</a></li>
                            <!-- <li><a href="<?php //url_to('erp.finance.accountgroup'); 
                                ?>" class="navLink <?php
                                //if($menu=='setting' && $submenu==='finance'){
                                //    echo 'active';
                                //}
                                ?>" ><i class="fa fa-circle-o"></i> Finance</a></li> -->

                            <li>
                                <a href="<?php echo url_to('erp.setting.companyinformation'); ?>" class="navLink <?php
                                   if ($menu == 'setting' && $submenu == 'company_info') {
                                       echo 'active';
                                   }
                                   ?>"><i class="fa fa-circle-o"></i>Company Information</a>
                            </li>

                            <li>
                                <a href="<?php echo url_to('erp.setting.activitylog'); ?>" class="navLink <?php
                                   if ($menu == 'setting' && $submenu == 'activitylog') {
                                       echo 'active';
                                   }
                                   ?>"><i class="fa fa-circle-o"></i>Activity Log</a>
                            </li>
                            <!-- udhaya -->
                            <li>
                                <a href="<?php echo url_to('er.setting.contracttype'); ?>" class="navLink <?php
                                   if ($menu == 'setting' && $submenu == 'contracttype') {
                                       echo 'active';
                                   }
                                   ?>"><i class="fa fa-circle-o"></i>Contract Type</a>
                            </li>
                            <!-- udhaya -->
                            <li>
                                <a href="<?php echo url_to('erp.setting.databasebackupview'); ?>" class="navLink <?php
                                   if ($menu == 'setting' && $submenu == 'database_backup') {
                                       echo 'active';
                                   }
                                   ?>"><i class="fa fa-circle-o"></i>Database Backup</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php
            }
            ?>


        </ul>
    </nav>

    <main class="main ">
        <div class="topNav flex">
            <ul class="topNavLeft flex">
                <li><a type="button" class="closeIcon HoverA"><i class="fa-solid fa-bars"></i> </a></li>
                <li class="search">
                    <a type="button" class="searchBtn HoverA"><i class="fa fa-search"></i></a>
                    <div class="search_container poA bg-shadow">
                        <div class="searchBox bg-white poA flex">
                            <p><i class="fa fa-search"></i></p>
                            <input type="text" class="form_control" placeholder="Search Anything">
                            <a type="button" class="closeBtn"><i class="fa fa-close"></i></a>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="topNavRight">
                <ul class="flex">
                    <li><a type="button" class="HoverA fullscreen"><i class="fa fa-arrows-alt"></i></a></li>
                    <li class="position-relative">
                        <button class="timesheet_icon HoverA"><i class="fa-regular fa-clock"></i></button>
                        <div class="timer_dropdown">
                            <div id="timer_contents">
                                <?php if (empty(session()->get('UserTimers'))) { ?>
                                    <div class="text-center">
                                        <button class="btn bg-success my-4" onclick="start_the_timer()">Start timer</button>
                                    </div>
                                <?php } else {
                                    $timer_info = session()->get('UserTimers');
                                    ?>
                                    <div class="text-center text-dark mb-1">
                                        <p class="text-center fs-16 font-weight-bold mb-1 overflow-hidden">Running Timer</p>
                                        <p class="text-center fs-13"><?= $timer_info->start_timer ?? ""; ?></p>
                                        <p class="text-center fs-13 mb-1">Total logged time:
                                            <?= $timer_info->logged_time ?? ""; ?>
                                        </p>
                                        <div class="w-100 d-flex justify-content-around align-items-center">
                                            <button class="my-2 btn-outline-danger timer_sheet_toggle"
                                                data-timer_id='<?= $timer_info->id ?? ''; ?>'>Stop Timer</button>
                                            <a href="#" class="text-danger"
                                                onclick="delete_timer(<?= $timer_info->id ?? ''; ?>)">Delete</a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <hr>
                            <a href="<?= url_to('erp.timesheet.list'); ?>"
                                class="mx-3 px-5 mt-2 text-center text-primary">View All Timesheets</a>
                        </div>
                    </li>
                    <!-- data-notify-url="<? //= url_to('erp.notify.getNotifyByUser')
                    ?>" -->
                    <li class="dropdown " id="push_notifier"><a type="button" class="dropBtn HoverA notifier-invoker"><i
                                class="fa-solid fa-bell"></i>
                            <?= empty(session("UserNotification")) ? "" : "<span class='bg-danger br50'></span>" ?></a>
                        <div class="dropdown_container">
                            <ul class="messageDropdown">
                                <li>
                                    <h4>Notification <i class="text-success fa-solid fa-bell"></i></h4>
                                </li>

                                <div id="notificationContainer" data-notify-center-url="">

                                    <?php
                                    if (!empty(session("UserNotification"))) {
                                        $all_notifications = session("UserNotification");
                                        foreach ($all_notifications as $notification) { ?>
                                            <li>
                                                <a
                                                    href="<?= empty($notification["related_base_url"]) ? "#" : url_to($notification["related_base_url"], $notification["related_id"]); ?>">
                                                    <h4 class="textFlow">
                                                        <?= $notification["title"]; ?>
                                                    </h4>
                                                    <p>
                                                        <?= $notification["notify_text"]; ?>
                                                    </p>
                                                </a>
                                            </li>
                                        <?php }
                                    } ?>
                                </div>
                                <!-- <li>
                                     <a href="#" data-ajax-url="" class="textCenter text-danger notifier-clear-all">Clear All Notification</a>
                                </li> -->
                                <li>
                                    <a href="<?= empty(session("UserNotification")) ? "#" : url_to('erp.notification.center') ?>"
                                        class="textCenter text-success notifier-clear-all">
                                        <?php
                                        if (!empty(session("UserNotification"))) {
                                            echo "View All Notifications";
                                        } else {
                                            echo "No notification Available Right Now";
                                        }
                                        ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="navProfile dropdown">
                        <a type="button" class="dropBtn"><img
                                src="<?php echo base_url() . 'assets/images/admin.png'; ?>" alt="admin"
                                class="img_cover br50"> <span class="bg-success br50"></span></a>
                        <div class="dropdown_container">
                            <div class="profileHead flex">
                                <div class="profileHeadLeft">
                                    <img src="<?php echo base_url() . 'assets/images/admin.png'; ?>" alt="admin"
                                        class="img_cover br50"> <span class="bg-success br50"></span>
                                </div>
                                <div class="profileHeadRight">
                                    <h4 class="textFlow">
                                        <?php echo get_user_name(); ?>
                                    </h4>
                                    <p class="textFlow">
                                        <?php echo get_role_name(); ?>
                                    </p>
                                </div>
                            </div>
                            <ul class="profileHeadul">
                                <li><a href="<?php echo url_to('erp.setting.useredit', get_user_id()); ?>"><i
                                            class="fa fa-user-o"></i> Profile</a></li>
                                <!-- <li><a href="#"><i class="fa fa-comment-o"></i> Chat <div
                                            class="NotificationCount bg-danger">2</div></a></li> -->
                                <li><a href="#"><i class="fa fa-envelope-o"></i> Mail</a></li>
                                <li><a href="<?= url_to('erp.logout'); ?>"><i class="fa fa-lock"></i> Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="timer_model">
            <button class="timer_close_btn HoverA timer_sheet_toggle"><i class="fa fa-close"></i></button>
            <div class="px-4 py-3 text-center">
                <h1>Stop Timer</h1>
                <div class="w-100">
                    <select class="form_control my-3" id="task_name">
                        <option selected disabled>Select a task</option>
                    </select>
                </div>
                <div class="w-100">
                    <textarea type="text" class="form_control mb-3" id="task_notes" placeholder="Task Notes"></textarea>
                </div>
                <div class="w-100">
                    <button class="btn bg-success mx-auto" onclick="stop_the_timer()">Confirm</button>
                </div>
            </div>
        </div>
        <div class="main_container">
            <?php include_once $page . ".php"; ?>
            <script>
                let timer_id = 0;
                function start_the_timer() {
                    let input_data = { 'assigned_to': "<?= session()->get('erp_userid') ?>" };

                    $.ajax({
                        type: "POST",
                        url: "<?= url_to("erp.timesheet.add_timer"); ?>",
                        data: input_data,
                        success: function (response, status) {
                            console.log("status", status);
                            console.log(response);
                            if (response.result == 'success') {
                                let structure = `  <div class="text-center text-dark mb-1">
                                    <p class="text-center fs-16 font-weight-bold mb-1 overflow-hidden">Test Job You need to
                                        Done</p>
                                    <p class="text-center fs-13">${response.data.created_at}</p>
                                    <p class="text-center fs-13 mb-1">Total logged time:
                                        ${response.data.logged_time}</p>
                                    <div class="w-100 d-flex justify-content-around align-items-center">
                                        <button class="my-2 btn-outline-danger timer_sheet_toggle">Stop Timer</button>
                                        <a href="#" class="text-danger"
                                            onclick="delete_timer(${response.data.id})">Delete</a>
                                    </div>
                                </div>`;
                                document.querySelector('#timer_contents').innerHTML = structure;
                            } else {
                                alert('Can \'t Start the timer !');
                            }
                            datatable.fetch_data('<?= url_to('erp.timesheet.datatable') ?>');
                        },
                        error: function (jqXHR, status) {
                            console.log(jqXHR);
                            alert('fail: ' + status);
                        }
                    });
                }

                function start_the_timer_by_user_id(user_id) {
                    let input_data = { 'assigned_to': user_id };

                    $.ajax({
                        type: "POST",
                        url: "<?= url_to("erp.timesheet.add_timer"); ?>",
                        data: input_data,
                        success: function (response, status) {
                            console.log("status", status);
                            console.log(response);
                            if (response.result == 'success') {
                                if (user_id == "<?= session()->get('erp_userid') ?>") {
                                    let structure = `  <div class="text-center text-dark mb-1">
                                        <p class="text-center fs-16 font-weight-bold mb-1 overflow-hidden">Test Job You need to
                                            Done</p>
                                        <p class="text-center fs-13">${response.data.created_at}</p>
                                        <p class="text-center fs-13 mb-1">Total logged time:
                                            ${response.data.logged_time}</p>
                                        <div class="w-100 d-flex justify-content-around align-items-center">
                                            <button class="my-2 btn-outline-danger timer_sheet_toggle">Stop Timer</button>
                                            <a href="#" class="text-danger"
                                                onclick="delete_timer(${response.data.id})">Delete</a>
                                        </div>
                                    </div>`;
                                    document.querySelector('#timer_contents').innerHTML = structure;
                                }

                                document.querySelector('#task_start_btn').innerHTML = "Stop timer";
                                document.querySelector('#task_start_btn').classList.replace("bg-success", "bg-danger");


                            } else {
                                alert('Can \'t Start the timer !');
                            }
                            datatable.fetch_data('<?= url_to('erp.timesheet.datatable') ?>');
                        },
                        error: function (jqXHR, status) {
                            console.log(jqXHR);
                            alert('fail: ' + status);
                        }
                    });
                }

                function get_data_for_timesheet_model() {
                    $.ajax({
                        type: "POST",
                        url: "<?= url_to("erp.timesheet.get_all_tasks"); ?>",
                        success: function (response, status) {
                            console.log("status", status);
                            console.log(response);
                            let structure = `<option selected disabled>Select a task</option>`;
                            response.data.forEach(data => {
                                console.log(data.url);
                                structure += `<option value="${data.url}">${data.text}</option>`;
                            });
                            $('#task_name').html(structure);
                        },
                        error: function (jqXHR, status) {
                            console.log(jqXHR);
                            alert('fail: ' + status);
                        }
                    });
                }

                $(document).on('click', '.timer_sheet_toggle', function (event) {
                    $('.timer_dropdown').removeClass('show');
                    $('.timer_model').toggleClass('show');
                    if (event.target.dataset.timer_id) {
                        timer_id = event.target.dataset.timer_id
                    }
                    get_data_for_timesheet_model();
                });

                function stop_the_timer() {
                    let index_num = document.getElementById('task_name').selectedIndex;
                    let subject = document.getElementById('task_name').options[index_num].innerHTML;
                    subject = subject == 'Select a task' ? "" : subject;
                    let input_data = {
                        'timer_id': timer_id,
                        'related_to': subject,
                        'related_url': $('#task_name').val(),
                        'note': $('#task_notes').val(),
                    };
                    $.ajax({
                        type: "POST",
                        url: "<?= url_to("erp.timesheet.stop_timer"); ?>",
                        data: input_data,
                        success: function (response, status) {
                            console.log("status", status);
                            console.log(response);
                            if (response.result == 'success') {
                                let structure = `<div class="text-center">
                                    <button class="btn bg-success my-4" onclick="start_the_timer()">Start timer</button>
                                </div>`;
                                document.querySelector('#timer_contents').innerHTML = structure;
                            } else {
                                alert('Can \'t Stop the timer !');
                            }
                            $('.timer_model').removeClass('show');
                            datatable.fetch_data('<?= url_to('erp.timesheet.datatable') ?>');
                        },
                        error: function (jqXHR, status) {
                            $('.timer_model').removeClass('show');
                            console.log(jqXHR);
                            alert('fail: ' + status);
                        }
                    });
                }

                function delete_timer(timer_id) {
                    let input_data = {
                        'timer_id': timer_id,
                    };
                    $.ajax({
                        type: "POST",
                        url: "<?= url_to("erp.timesheet.delete_timer_api"); ?>",
                        data: input_data,
                        success: function (response, status) {
                            console.log("status", status);
                            console.log(response);
                            if (response.result == 'success') {
                                let structure = `<div class="text-center">
                                    <button class="btn bg-success my-4" onclick="start_the_timer()">Start timer</button>
                                </div>`;
                                document.querySelector('#timer_contents').innerHTML = structure;
                            } else {
                                alert('can \' Delete the timer !');
                            }
                            datatable.fetch_data('<?= url_to('erp.timesheet.datatable') ?>');
                        },
                        error: function (jqXHR, status) {
                            console.log(jqXHR);
                            alert('fail: ' + status);
                        }
                    });
                }
            </script>