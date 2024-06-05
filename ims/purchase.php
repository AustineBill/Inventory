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
<script src="js/purchase.js"></script>
<script src="js/common.js"></script>
<?php include('inc/container.php');?>

<div class="container-fluid">			
	<div class="row">
		<div class="col-lg-2">
			<?php include("menus.php"); ?> 
		</div>
		<div class="col-lg-9" style="padding-top: 50px;">
			<div class="card card-default rounded-0 shadow">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
							<h3 class="card-title" style="color: white;"><i class="fas fa-shopping-cart"></i>Purchase List</h3>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 text-end">
							<button type="button" name="addPurchase" id="addPurchase" class="btn btn-primary btn-sm rounded-0"><i class="far fa-plus-square me-1"></i> Add Purchase</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-sm-12 table-responsive">
							<table id="purchaseList" class="table table-bordered table-striped">
								<thead>
									<tr>
																		
										<th>Product</th>	
										<th>Quantity</th>	
										<th>Supplier</th>                                           
										<th>Action</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="purchaseModal" class="modal fade">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><i class="fa fa-plus me-1"></i> Add Purchase</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">                           
					<form method="post" id="purchaseForm">
						<input type="hidden" name="purchase_id" id="purchase_id" />
						<input type="hidden" name="btn_action" id="btn_action" />
						<div class="form-group">
							<label>Product Name</label>
							<select name="product" id="product" class="form-select rounded-0" required>
								<option value="">Select Product</option>
								<?php echo $inventory->productDropdownList();?>
							</select>
						</div>	                           
						<div class="form-group">
							<label>Product Quantity</label>
							<div class="input-group">
								<input type="text" name="quantity" id="quantity" class="form-control rounded-0" required pattern="[+-]?([0-9]*[.])?[0-9]+" />        
							</div>
						</div>                           
						<div class="form-group">
							<label>Supplier</label>
							<select name="supplierid" id="supplierid" class="form-select rounded-0" required>
								<option value="">Select Supplier</option>
								<?php echo $inventory->supplierDropdownList();?>
							</select>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<input type="submit" name="action" id="action" class="btn btn-primary btn-sm rounded-0" value="Add" form="purchaseForm"/>
					<button type="button" class="btn btn-default border btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include('inc/footer.php'); ?>

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