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
<script src="js/customer.js"></script>
<script src="js/common.js"></script>
<?php include('inc/container.php');?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-2">
            <?php include("menus.php"); ?> 
        </div>
        <div class="col-lg-9" style="padding-top: 50px">
            <div class="card card-default rounded-0 shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <h3 class="card-title">Customer List</h3>
                        </div>
                        <div class="col-md-3 text-end">
                            <button type="button" name="add" id="addCustomer" class="btn btn-primary bg-gradient btn-sm rounded-0" data-bs-toggle="modal" data-bs-target="#customerModal"><i class="far fa-plus-square"></i> New Customer</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="customerList" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Item Purchased</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="customerModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered rounded-0">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Customer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="customerForm">
                        <input type="hidden" name="userid" id="userid" />
                        <input type="hidden" name="btn_action" id="btn_action" value="customerAdd" />
                        <div class="mb-3">
                            <label class="control-label">Date</label>
                            <input type="date" name="date" id="date" class="form-control rounded-0" required />
                        </div>
                        <div class="mb-3">
                            <label class="control-label">Item Purchased</label>
                            <input type="text" name="item_purchased" id="item_purchased" class="form-control rounded-0" required />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" id="action" class="btn btn-sm rounded-0 btn-primary" form="customerForm">Save</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-default border" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
