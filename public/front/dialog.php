<?php include "includes/header.php" ?>

    <div class="main_container">
           
       <div class="alldiv DE_Modal mb5">
           <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iste, porro</p>
           <button class="btn outline-info modalBtn" type="button" data-target="mymodal">open dialog</button>
       </div>

       <div class="alldiv DE_Modal mb5">
           <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iste, porro</p>
           <button class="btn outline-info modalBtn" type="button" data-target="FormModal">Form Dialog</button>
       </div>

       <div class="alldiv DE_Modal mb5">
           <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iste, porro</p>
           <button class="btn outline-info alertBtn" type="button" >Modal Alert</button>
       </div>

       <div class="alldiv DE_Modal">
           <p>Multi selector</p>
          
           <div class="flex">
         
                <div class="formWidth">
                    <div class="multiSelectBox poR">
                        <div class="multiSelectBoxBtn">
                            <div class="Multi_InputContainer"></div>
                            <button class="drops2" type="button"><i class="fa fa-caret-down"></i></button>
                            <input type="hidden" value="" class="multiSelectInput">
                        </div>
                        <div role="comboBox" class="MultiselectBox_Container">
                            <label for="1" class="multiBox_label"><input type="checkBox" id="1" name="Baseball">Baseball</label>
                            <label for="2" class="multiBox_label"><input type="checkBox" id="2" name="Football">Football</label>
                            <label for="3" class="multiBox_label"><input type="checkBox" id="3" name="VolleyBall">VolleyBall</label>
                            <label for="4" class="multiBox_label"><input type="checkBox" id="4" name="Cricket">Cricket</label>
                            <label for="5" class="multiBox_label"><input type="checkBox" id="5" name="KhoKho">KhoKho</label>
                            <label for="6" class="multiBox_label"><input type="checkBox" id="6" name="LongJump">LongJump</label>
                        </div>
                    </div>
                </div>

                <div class="formWidth">
                    <div class="multiSelectBox poR">
                        <div class="multiSelectBoxBtn">
                            <div class="Multi_InputContainer"></div>
                            <button class="drops2" type="button"><i class="fa fa-caret-down"></i></button>
                            <input type="hidden" value="" class="multiSelectInput" >
                        </div>
                        <div role="comboBox" class="MultiselectBox_Container">
                            <label for="11" class="multiBox_label"><input type="checkBox" id="11" name="apple">apple</label>
                            <label for="12" class="multiBox_label"><input type="checkBox" id="12" name="banana">banana</label>
                            <label for="13" class="multiBox_label"><input type="checkBox" id="13" name="guava">guava</label>
                            <label for="14" class="multiBox_label"><input type="checkBox" id="14" name="grapes">grapes</label>
                            <label for="15" class="multiBox_label"><input type="checkBox" id="15" name="orange">orange</label>
                        </div>
                    </div>
                </div>

            </div>
       </div>

    </div>

    <div class="modal" id="mymodal" role="dialog">
        <div class="modalbody">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem ab pariatur placeat assumenda qui minima mollitia! Sed, explicabo consequuntur optio in error nemo numquam voluptate unde, cumque doloremque nulla necessitatibus!</p>
            <button class="btn bg-danger modalClose">close</button>
        </div>
    </div>

    <div class="modal" id="FormModal" role="dialog">
        <div class="modalbody">
            <h2 class="modalTitle">User Profile</h2>
            <form action="">
                <input type="text" class="form_control" placeholder="name">
                <input type="email" class="form_control" placeholder="email">
                <input type="password" class="form_control" placeholder="password">
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex"> 
                        <div class="textFlow" data-default="Age">Age</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <li role="option">1-5</li>
                        <li role="option">5-10</li>
                        <li role="option">10-20</li>
                        <li role="option">20-50</li>
                    </ul>
                </div>
            </form>
            <button class="btn outline-danger modalClose">close</button>
        </div>
    </div>
     
    <!-----------Alert-------------- -->
       <div class="ModalAlert">
            <p class="textFlow st_success">Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic voluptatem illum quos incidunt beatae officiis ipsam vero optio illo suscipit dignissimos dicta veritatis odit sed, aspernatur ex soluta dolor amet!</p>
            <a type="button" class="HoverA"><i class="fa fa-close"></i></a>
        </div>
    <!-----------Alert-------------- -->

<?php include "includes/footer.php" ?>