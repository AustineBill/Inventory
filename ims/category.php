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
<script src="js/category.js"></script>
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
                            <h3 class="card-title" style="color: white;"><i class="fas fa-tags"></i> Category List</h3>
                        </div>
                        <div class="col-lg-4 text-end">
                            <button type="button" name="add" id="categoryAdd" data-bs-toggle="modal" data-bs-target="#categoryModal" class="btn btn-primary btn-sm bg-gradient rounded-0"><i class="fas fa-plus"></i> Add Category</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                    	<div class="col-sm-12 table-responsive">
                    		<table id="categoryList" class="table table-bordered table-striped">
                    			<thead><tr>
									<th>Category Name</th>
									<th>Status</th>
									<th>Action</th>
								</tr></thead>
                    		</table>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="categoryModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-plus"></i> Add Category</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="categoryForm">
                        <input type="hidden" name="categoryId" id="categoryId"/>
                        <input type="hidden" name="btn_action" id="btn_action"/>
                        <div class="mb-3">
                            <label for="category"><i class="fas fa-tags"></i> Category Name</label>
                            <input type="text" name="category" id="category" class="form-control rounded-0" required />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="action" id="action" class="btn btn-primary btn-sm rounded-0" value="Add" form="categoryForm"/>
                    <button type="button" class="btn btn-default btn-sm rounded-0 border" data-bs-dismiss="modal">Close</button>
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
