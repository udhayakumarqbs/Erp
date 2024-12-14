<?php include "includes/header.php" ?>

        <div class="main_container">
           
            <div class="alldiv">
                
                <div class="filterBox">
                    <div class="flex">
                        <h4>Filter and Search</h4>
                        <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
                    </div>
                    <div class="flex filterBox_container">
                        <div class="formWidth">
                            <div class="selectBox poR">
                                <div class="selectBoxBtn flex"> 
                                    <div class="textFlow" data-default="select role">select</div>
                                    <button class="close"><i class="fa fa-close"></i></button>
                                    <button class="drops"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" value="">
                                </div>
                                <ul role="listbox" class="selectBox_Container alldiv">
                                   <li role="option">Admin</li>
                                   <li role="option">Author</li>
                                   <li role="option">Editor</li>
                                   <li role="option">Maintance</li>
                                </ul>
                            </div>
                        </div>
                        <div class="formWidth">
                            <div class="selectBox poR">
                                <div class="selectBoxBtn flex"> 
                                    <div class="textFlow" data-default="select role">select</div>
                                    <button class="close"><i class="fa fa-close"></i></button>
                                    <button class="drops"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" value="">
                                </div>
                                <ul role="listbox" class="selectBox_Container alldiv">
                                   <li role="option">Admin</li>
                                   <li role="option">Author</li>
                                   <li role="option">Editor</li>
                                   <li role="option">Maintance</li>
                                </ul>
                            </div>
                        </div>
                        <div class="formWidth">
                            <div class="selectBox poR">
                                <div class="selectBoxBtn flex"> 
                                    <div class="textFlow" data-default="select role">select</div>
                                    <button class="close"><i class="fa fa-close"></i></button>
                                    <button class="drops"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" value="">
                                </div>
                                <ul role="listbox" class="selectBox_Container alldiv">
                                   <li role="option">Admin</li>
                                   <li role="option">Author</li>
                                   <li role="option">Editor</li>
                                   <li role="option">Maintance</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                 
                <div class="tableHeader flex">
                    <div class="formWidth">
                        <div class="selectBox poR">
                            <div class="selectBoxBtn flex"> 
                                <div class="textFlow" data-default="Action">Action</div>
                                <button class="close"><i class="fa fa-close"></i></button>
                                <button class="drops"><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" value="">
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                               <li role="option">Edit</li>
                               <li role="option">Delete</li>
                               <li role="option">Send</li>
                            </ul>
                        </div>
                    </div>
                    <div class="formWidth">
                        <input type="text" placeholder="search" class="form_control">
                    </div>
                    <div class="formWidth textRight">
                    <!--export button-->
                        <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export</a>
                        <div class="export_container poF">
                            <ul class="exportUl">
                                <li><a href="#"><img src="images/icons/xls.png" alt="excel" >EXCEL</a></li>
                                <li><a href="#"><img src="images/icons/pdf.png" alt="pdf" >PDF</a></li>
                                <li><a href="#"><img src="images/icons/csv.png" alt="csv" >CSV</a></li>
                            </ul>
                            <a type="button" class="closeBtn3 HoverA"><i class="fa fa-close"></i></a>
                        </div>
                    <!--export button-->
                    </div>
                </div>
                 
                <div class="table_responsive">
                    <table class="table">
                        <thead class="thead">
                            <tr>
                                <th><input type="checkbox"></th>
                                <th><a type="button">#id <i class="fa fa-long-arrow-up"></i></a></th>
                                <th><a type="button">client <i class="fa fa-long-arrow-up"></i></a></th>
                                <th><a type="button">total <i class="fa fa-long-arrow-up"></i></a></th>
                                <th><a type="button">date <i class="fa fa-long-arrow-up"></i></a></th>
                                <th><a type="button">Status <i class="fa fa-long-arrow-up"></i></a></th>
                                <th><a type="button">Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td class="text-primary">#12345</td>
                                <td class="tablename">muthukumar <span>muthu@qbrainstorm.com</span></td>
                                <td>123344</td>
                                <td>11-12-2021</td>
                                <td><span class="st st_violet">Resigned</span></td>
                                <td class="dropdown tableAction">
                                    <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown_container">
                                        <ul class="BB_UL flex">
                                            <li><a href="" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                            <li><a href="" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                            <li><a href="" title="Delete" class="bg-danger"><i class="fa fa-trash"></i> </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td class="text-primary">#12345</td>
                                <td class="tablename">muthukumar <span>muthu@qbrainstorm.com</span></td>
                                <td>123344</td>
                                <td>11-12-2021</td>
                                <td><span class="st st_primary">Resigned</span></td>
                                <td class="dropdown tableAction">
                                    <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown_container">
                                        <ul class="BB_UL flex">
                                            <li><a href="" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                            <li><a href="" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                            <li><a href="" title="Delete" class="bg-danger"><i class="fa fa-trash"></i> </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td class="text-primary">#12345</td>
                                <td class="tablename">muthukumar <span>muthu@qbrainstorm.com</span></td>
                                <td>123344</td>
                                <td>11-12-2021</td>
                                <td><span class="st st_success">Resigned</span></td>
                                <td class="dropdown tableAction">
                                    <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown_container">
                                        <ul class="BB_UL flex">
                                            <li><a href="" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                            <li><a href="" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                            <li><a href="" title="Delete" class="bg-danger"><i class="fa fa-trash"></i> </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td class="text-primary">#12345</td>
                                <td class="tablename">muthukumar <span>muthu@qbrainstorm.com</span></td>
                                <td>123344</td>
                                <td>11-12-2021</td>
                                <td><span class="st st_danger">Resigned</span></td>
                                <td class="dropdown tableAction">
                                    <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown_container">
                                        <ul class="BB_UL flex">
                                            <li><a href="" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                            <li><a href="" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                            <li><a href="" title="Delete" class="bg-danger"><i class="fa fa-trash"></i> </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td class="text-primary">#12345</td>
                                <td class="tablename">muthukumar <span>muthu@qbrainstorm.com</span></td>
                                <td>123344</td>
                                <td>11-12-2021</td>
                                <td><span class="st st_dark">Resigned</span></td>
                                <td class="dropdown tableAction">
                                    <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown_container">
                                        <ul class="BB_UL flex">
                                            <li><a href="" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                            <li><a href="" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                            <li><a href="" title="Delete" class="bg-danger"><i class="fa fa-trash"></i> </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td class="text-primary">#12345</td>
                                <td class="tablename">muthukumar <span>muthu@qbrainstorm.com</span></td>
                                <td>123344</td>
                                <td>11-12-2021</td>
                                <td><span class="st st_violet">Resigned</span></td>
                                <td class="dropdown tableAction">
                                    <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown_container">
                                        <ul class="BB_UL flex">
                                            <li><a href="" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                            <li><a href="" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                            <li><a href="" title="Delete" class="bg-danger"><i class="fa fa-trash"></i> </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td class="text-primary">#12345</td>
                                <td class="tablename">muthukumar <span>muthu@qbrainstorm.com</span></td>
                                <td>123344</td>
                                <td>11-12-2021</td>
                                <td><span class="st st_primary">Resigned</span></td>
                                <td class="dropdown tableAction">
                                    <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown_container">
                                        <ul class="BB_UL flex">
                                            <li><a href="" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                            <li><a href="" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                            <li><a href="" title="Delete" class="bg-danger"><i class="fa fa-trash"></i> </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td class="text-primary">#12345</td>
                                <td class="tablename">muthukumar <span>muthu@qbrainstorm.com</span></td>
                                <td>123344</td>
                                <td>11-12-2021</td>
                                <td><span class="st st_success">Resigned</span></td>
                                <td class="dropdown tableAction">
                                    <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown_container">
                                        <ul class="BB_UL flex">
                                            <li><a href="" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                            <li><a href="" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                            <li><a href="" title="Delete" class="bg-danger"><i class="fa fa-trash"></i> </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="tableFooter flex">
                  <div class="tableFooterLeft flex">
                      <p>Rows per page:</p>
                      <div class="selectBox miniSelectBox poR">
                            <div class="selectBoxBtn flex"> 
                                <div class="textFlow" data-default="5">5</div>
                                <button class="drops"><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" value="5">
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                               <li role="option" class="active">5</li>
                               <li role="option">10</li>
                               <li role="option">15</li>
                               <li role="option">All</li>
                            </ul>
                      </div>
                  </div>
                  
                  <div class="tableFooterRight flex">
                       <div class="pagination">1-5 of 100</div>
                       <ul class="flex paginationBtns">
                           <li><a type="button" class="HoverA"><i class="fa fa-angle-left"></i></a></li>
                           <li><a type="button" class="HoverA"><i class="fa fa-angle-right"></i></a></li>
                       </ul>
                  </div>


                </div>
            </div>

        </div>

<?php include "includes/footer.php" ?>