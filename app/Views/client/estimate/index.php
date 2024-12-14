
<div class="mt-3">
    <div class="card-style">
        <div class="title d-flex flex-wrap align-items-center justify-content-between">
            <div class="left">
                <h6 class="text-medium mb-30 h3">Estimate</h6>
            </div>
            <div class="right">
                <div class="select-style-1">
                    <div class="select-position select-sm">
                        <select class="light-bg">
                            <option value="">Today</option>
                            <option value="">Yesterday</option>
                        </select>
                    </div>
                </div>
                <!-- end select -->
            </div>
        </div>
        <!-- End Title -->
        <div class="table-responsive">
            <table class="table top-selling-table" id="myTable">
                <thead>
                    <tr>
                        <th>
                            <h6 class="text-sm text-medium">Estimate Code</h6>
                        </th>
                        <th class="min-width">
                            <h6 class="text-sm text-medium">
                                Date<i class="lni lni-arrows-vertical"></i>
                            </h6>
                        </th>
                        <th class="min-width">
                            <h6 class="text-sm text-medium">
                                Created By <i class="lni lni-arrows-vertical"></i>
                            </h6>
                        </th>

                        <th>
                            <h6 class="text-sm text-medium text-end">
                                Actions <i class="lni lni-arrows-vertical"></i>
                            </h6>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($estimates)){ ?>
                    <?php foreach($estimates as $est){ ?>
            
                    <tr>
                        <td>
                        <p class="text-sm"><?= $est['code'] ?></p>
                        </td>
                        <td>
                            <p class="text-sm"><?= $est['estimate_date'] ?></p>
                        </td>
                        <td>
                            <p class="text-sm"><?= $est['name'] ?></p>
                        </td>
                        <td>
                            <div class="action justify-content-end">
                                <button class="edit">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <button class="more-btn ml-10 dropdown-toggle" id="moreAction1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="moreAction1">
                                    <li class="dropdown-item">
                                        <a href="#0" class="text-gray">Remove</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a href="#0" class="text-gray">Edit</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
            <!-- End Table -->
        </div>
    </div>
</div>


<script src="http://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<link rel="stylesheet" href="http://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
<script>
        let table = new DataTable('#myTable');
</script>