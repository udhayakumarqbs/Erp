



<div class="title-wrapper pt-30">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="title">
                <h2>Projects List </h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                <div>
            <button class="btn btn-primary">New Task</button>
           </div>
                </nav>
            </div>
        </div>
    </div>
</div>

        <!-- tab info -->

        <style>



.tab-links-link{
    color: #333;
    padding: 10px;
    font-size: 14px;
}

.tab-links {
  display: inline-block;
  padding: 15px;
  width: 100%;
  text-align: center;
}



.link-active {
  color: #0d6efd!important;
  text-decoration: none;
  background-color:#E5E4E2;  
}
       

.content-active {
  display: block;
  opacity: 1;
}

.tab-hide {
  display: none;
}

</style>











<div class="mt-3">
    <div class="card-style">

    <div class="tab-container ">
     <div class="tab-links">
    <a class="tab-links-link link-active" href="#tab1">Project Overview</a>
    <!-- <a class="tab-links-link" href="#tab2">Phases</a> -->
    <a class="tab-links-link"  href="#tab3">Expenses</a>
    <a class="tab-links-link" href="#tab4">Raw Materials</a>
    <a class="tab-links-link" href="#tab5">Testing</a>
    <a class="tab-links-link" href="#tab6">Attachment</a>



    </div>
  
  <div class="tab-content-container">
    <div class="content-active" id="tab1div">
     <h3 class="mb-3">Overview</h3>

     <div class="row">
        <div class="col-lg-8">
            <div class="row">

<div class="col-lg-4">
    <p>Project</p>
    <p>Customer</p>
    <p>Product</p>
    <p>Status</p>

    <p>Start Date </p>
    <p>Deadline</p>
    <p>Total Rate </p>

    <p>Created on</p>
   
    <p>Description</p>
</div>

<div class="col-lg-4">
    <p>#1</p>
    <p>admin<p>
    <p>test dgedh465465</p>
    <p>In Progress</p>
    <p>2024-11-23</p>
    <p>2024-11-24</p>
    <p>$10,000.00</p>
    <p>2024-11-24</p>
  
    <p>asdf</p>
</div>
</div>

        </div>

        <div class="col-lg-4">

        </div>
     </div>

    <hr>

    <div class="row">
        <div class="col-lg-3">
            <p class="text-primary">Total Expenses</p>
            <p>$0.00</p>
        </div>

        <div class="col-lg-3">
        <p class="text-secondary">Billable Expenses</p>
        <p>$0.00</p>
        </div>

        <div class="col-lg-3">
        <p class="text-success">Billed Expenses</p>
        <p>$0.00</p>
        </div>

        <div class="col-lg-3">
        <p class="text-danger">Unbilled Expenses</p>
        <p>$0.00</p>
        </div>
    </div>

    <hr>

    <h6>Description</h6>
    <p>done the work fast</p>


    </div>

    <!-- tab2 -->
    <div class="tab-hide fade" id="tab2div">
    <h3 class="mb-3">Phases</h3>
   
    </div>


   
    </div>

    <!-- tab3 -->
    <div class="tab-hide fade" id="tab3div">

    <h3 class="mb-3">Expense</h3>
    <div class="row">
        <div class="col-lg-8">
            <div class="row">

<div class="col-lg-4">
    <p>Name</p>
    <p>Expense Date</p>
    <p>Amount</p>
    <p>Payment Mode</p>
    <p>Receipt </p>
    
</div>

<div class="col-lg-4">
    <p>test dgedh465465</p>
    <p>2024-11-23</p>
    <p>$10,000.00</p>
    <p>2024-11-24</p>
    <p>UPI</p>
</div>
</div>

        </div>

        <div class="col-lg-4">

        </div>
     </div>
    </div>
    <div class="tab-hide fade" id="tab4div">
    <h3 class="mb-3">Testing</h3>
    <div class="row">
        <div class="col-lg-8">
            <div class="row">

<div class="col-lg-4">
    <p>Raw Material</p>
    <p>Required QTY</p>
    <p>Requested for dispatch</p>
    
</div>

<div class="col-lg-8">
    <p>test dgedh465465</p>
    <p>Thamizharasi</p>
    <p>2024-11-23</p>
   
</div>
</div>

        </div>

        <div class="col-lg-4">

        </div>
     </div>  
    </div>
    <div class="tab-hide fade" id="tab5div">
    <h3 class="mb-3">Testing</h3>
    <div class="row">
        <div class="col-lg-8">
            <div class="row">

<div class="col-lg-4">
    <p>Name</p>
    <p>Assigned</p>
    <p>Complete Before</p>
    <p>Result</p>
    
</div>

<div class="col-lg-8">
    <p>test dgedh465465</p>
    <p>Thamizharasi</p>
    <p>2024-11-23</p>
    <p>Lorem ipsum dolor sit amet consectetur </p>
   
</div>
</div>

        </div>

        <div class="col-lg-4">

        </div>
     </div>              
    </div>

    <div class="tab-hide fade" id="tab6div">
        <div class="row mt-5" >

         <div class="col-lg-6">
                    <div>
                        <a href="">
                        <p><img class="mx-4" src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">ashdfs xls</p>
                        </a>
                    </div>
                    <hr>
                    <div>
                    <a href="">
                        <p><img class="mx-4" src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">ashdfs pdf</p>
                        </a>
                    </div>
                    <hr>

                    <div>
                    <a href="">
                        <p><img class="mx-4" src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">ashdfs csv</p>
                        </a>
                    </div>
                    </div>              
             </div>
         </div>

</div>
</div>
</div>








<script src="http://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<link rel="stylesheet" href="http://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
<script>
        let table = new DataTable('#myTable');
        let table = new DataTable('#myTable2');
        let table = new DataTable('#myTable3');
        let table = new DataTable('#myTable4');

</script>

<script>
 
$('.tab-links-link').click(function() {
  
  $('.tab-links-link').each(function() {
    var hashValue = this.href.split('#');
    $('a[href$="'+hashValue[1]+'"]').removeClass("link-active");
    $("#" + hashValue[1] + 'div').removeClass("content-active").addClass( "tab-hide fade" );
  });
  
  var hashValue = this.href.split('#');
  $('a[href$="'+hashValue[1]+'"]').addClass("link-active");
  $("#" + hashValue[1] + 'div').removeClass( "tab-hide fade" ).addClass( "content-active" );
}); 
</script>