<?php 
ob_start();
session_start();
include('inc/header.php');
include 'Inventory.php';
$inventory = new Inventory();
$inventory->checkLogin();
?>

<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/supplier.js"></script>
<script src="js/common.js"></script>
<?php include('inc/container.php');?>



<div class="container-fluid">			
    <div class="row">
        <div class="col-lg-2">
            <?php include("menus.php"); ?> 
        </div>
        <div class="col-lg-9" style="padding-top: 50px;">
            <div class="card card-default rounded-0 shadow">
                <div class="card-header bg-orange text-white">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h3 class="card-title" style="color: white;"><i class="fas fa-truck"></i> Supplier List</h3>
                        </div>
                        <div class="col-lg-4 text-end">
                            <button type="button" name="add" id="addSupplier" data-bs-toggle="modal" data-bs-target="#supplierModal" class="btn btn-primary btn-sm bg-gradient rounded-0"><i class="fas fa-plus"></i> Add Supplier</button>
                        </div>
                    </div>					   
                    <div class="clear:both"></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table id="supplierList" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                       							
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Address</th>
                                        <th>Status</th>										
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data rows will be populated dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="supplierModal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fas fa-plus"></i> Add Supplier</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="color: orange;"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="supplierForm">
                        <input type="hidden" name="supplier_name_hidden" id="supplier_name_hidden">
                            <input type="hidden" name="btn_action" id="btn_action" />
                            <div class="mb-3">
                                <label for="supplier_name"><i class="fas fa-user"></i> Supplier Name</label>
                                <input type="text" name="supplier_name" id="supplier_name" class="form-control rounded-0" required />
                            </div>	
                            <div class="mb-3">
                                <label for="mobile"><i class="fas fa-mobile-alt"></i> Mobile</label>
                                <input type="text" name="mobile" id="mobile" class="form-control rounded-0" required />
                            </div>								
                            <div class="mb-3">
                                <label for="address"><i class="fas fa-address-card"></i> Address</label>
                                <textarea name="address" id="address" class="form-control rounded-0" rows="5" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="action" id="action" class="btn btn-primary rounded-0 btn-sm" value="Add Supplier" form="supplierForm"/>
                        <button type="button" class="btn btn-default border rounded-0 btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>	
    </div>	
</div>

<style>
html,
body {
    height: 100%;
    background-color: white; /* White background color */
}

.navbar {
    background-color: #ff9900 !important; /* White background color */
}

.card-header {
    background-color: #ff9900; /* Orange background color */
}

.card-title {
    color: #ff9900; /* Orange font color */
}

.btn-primary {
    background-color: #ff9900; /* Orange background color */
    border-color: #ff9900; /* Orange border color */
}

.btn-primary:hover {
    background-color: #e68a00; /* Darker orange on hover */
    border-color: #e68a00; /* Darker orange on hover */
}

.btn-close {
    color: #ff9900; /* Orange close button */
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(255, 153, 0, 0.1); /* Light orange stripes */
}

.table-striped tbody tr:hover {
    background-color: rgba(255, 153, 0, 0.3); /* Darker orange on hover */
}

.navbar-brand {
    font-size: 24px;
    font-weight: bold;
    color: #ff9900; /* Orange font color */
}
</style>
