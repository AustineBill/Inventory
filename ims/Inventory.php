<?php
class Inventory {
    private $host  = 'localhost';
    private $user  = 'root';
    private $password   = '';
    private $database  = 'ims_db';   
	private $userTable = 'ims_user';	
    private $customerTable = 'ims_customer';
	private $categoryTable = 'ims_category';
	private $brandTable = 'ims_brand';
	private $productTable = 'ims_product';
	private $supplierTable = 'ims_supplier';
	private $purchaseTable = 'ims_purchase';
	private $orderTable = 'ims_order';

	private $conn = null; 
	private $dbConnect = false;
    public function __construct(){
        if(!$this->dbConnect){ 
            $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            }else{
                $this->dbConnect = $conn;
            }
        }
    }

	
	private function getData($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error($this->dbConnect)); // Pass the connection as an argument
		}
		$data = array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[] = $row;
		}
		return $data;
	}
	
	private function getNumRows($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error($this->dbConnect)); // Pass the connection as an argument
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}

	public function login($email, $password){
		$password = md5($password);
		$sqlQuery = "
			SELECT userid, email, password, name, type, status
			FROM ".$this->userTable." 
			WHERE email='".$email."' AND password='".$password."'";
        return  $this->getData($sqlQuery);
	}	
	public function checkLogin(){
		if(empty($_SESSION['userid'])) {
			header("Location:login.php");
		}
	}
	
	
	public function register($email, $password, $name) {
		// Check if email already exists
		$password = md5($password);
		$sqlQuery = "SELECT userid FROM ".$this->userTable." WHERE email='".$email."'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
	
		if (mysqli_num_rows($result) > 0) {
			// Email already exists, return an error message
			return "Email already exists";
		} else {
			// Email doesn't exist, insert the new user's information
			$sqlInsert = "INSERT INTO ".$this->userTable." (email, password, name) VALUES ('$email', '$password', '$name')";
			$insertResult = mysqli_query($this->dbConnect, $sqlInsert);
	
			if ($insertResult) {
				// Registration successful
				return true;
			} else {
				// Error in registration process
				return "Registration failed";
			}
		}
	}
	public function getCustomer(){
		$sqlQuery = "
			SELECT * FROM ".$this->customerTable." 
			WHERE id = '".$_POST["userid"]."'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);	
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		echo json_encode($row);
	}
	
	public function getCustomerList() {
		$sqlQuery = "SELECT * FROM ".$this->customerTable." ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'WHERE (id LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= 'OR date LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= 'OR item_purchased LIKE "%'.$_POST["search"]["value"].'%") ';
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY id DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}
		
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$customerData = array();    
		while($customer = mysqli_fetch_assoc($result)) {        
			$customerRows = array();
			$customerRows[] = $customer['id'];
			$customerRows[] = $customer['date'];
			$customerRows[] = $customer['item_purchased'];    
			$customerRows[] = '<button type="button" name="update" id="'.$customer["id"].'" class="btn btn-primary btn-sm rounded-0 update" title="update"><i class="fa fa-edit"></i></button>';
			$customerRows[] = '<button type="button" name="delete" id="'.$customer["id"].'" class="btn btn-danger btn-sm rounded-0 delete"><i class="fa fa-trash"></i></button>';
			
			$customerData[] = $customerRows;
		}
		
		$output = array(
			"draw"                => intval($_POST["draw"]),
			"recordsTotal"        => $numRows,
			"recordsFiltered"     => $numRows,
			"data"                => $customerData
		);
		
		echo json_encode($output);
	}
	

	public function saveCustomer() {		
		$sqlInsert = "
			INSERT INTO ".$this->customerTable."(date, item_purchased) 
			VALUES ('".$_POST['date']."', '".$_POST['item_purchased']."')";		
		mysqli_query($this->dbConnect, $sqlInsert);
		echo 'New Customer Added';
	}

	
	public function updateCustomer() {
		if($_POST['userid']) {	
			$sqlUpdate = "
				UPDATE ".$this->customerTable." 
				SET date = '".$_POST['date']."', item_purchased = '".$_POST['item_purchased']."' 
				WHERE id = '".$_POST['userid']."'";		
			mysqli_query($this->dbConnect, $sqlUpdate);	
			echo 'Customer Edited';
		}	
	}
		
	public function deleteCustomer() {
		$sqlDelete = "
			DELETE FROM ".$this->customerTable." 
			WHERE id = '".$_POST['userid']."'";		
		mysqli_query($this->dbConnect, $sqlDelete);		
		echo 'Customer Deleted';
	}
	
	// Category functions
	public function getCategoryList(){		
		$sqlQuery = "SELECT * FROM ".$this->categoryTable." ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'WHERE (name LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= 'OR status LIKE "%'.$_POST["search"]["value"].'%") ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY categoryid DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$categoryData = array();	
		while( $category = mysqli_fetch_assoc($result) ) {		
			$categoryRows = array();
			$status = '';
			if($category['status'] == 'active')	{
				$status = '<span class="label label-success">Active</span>';
			} else {
				$status = '<span class="label label-danger">Inactive</span>';
			}
			
			$categoryRows[] = $category['name'];
			$categoryRows[] = $status;			
			$categoryRows[] = '<button type="button" name="update" id="'.$category["categoryid"].'" class="btn btn-primary btn-sm rounded-0 update" title="Update"><i class="fa fa-edit"></i></button><button type="button" name="delete" id="'.$category["categoryid"].'" class="btn btn-danger btn-sm rounded-0 delete"  title="Delete"><i class="fa fa-trash"></i></button>';
			$categoryData[] = $categoryRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$categoryData
		);
		echo json_encode($output);
	}
	public function saveCategory() {		
		$sqlInsert = "
			INSERT INTO ".$this->categoryTable."(name) 
			VALUES ('".$_POST['category']."')";		
		mysqli_query($this->dbConnect, $sqlInsert);
		echo 'New Category Added';
	}	
	public function getCategory(){
		$sqlQuery = "
			SELECT * FROM ".$this->categoryTable." 
			WHERE categoryid = '".$_POST["categoryId"]."'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);	
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		echo json_encode($row);
	}
	public function updateCategory() {
		if($_POST['category']) {	
			$sqlInsert = "
				UPDATE ".$this->categoryTable." 
				SET name = '".$_POST['category']."'
				WHERE categoryid = '".$_POST["categoryId"]."'";	
			mysqli_query($this->dbConnect, $sqlInsert);	
			echo 'Category Update';
		}	
	}	
	public function deleteCategory(){
		$sqlQuery = "
			DELETE FROM ".$this->categoryTable." 
			WHERE categoryid = '".$_POST["categoryId"]."'";		
		mysqli_query($this->dbConnect, $sqlQuery);		
	}
	// Brand management 
	public function getBrandList(){				
		$sqlQuery = "SELECT * FROM ".$this->brandTable." as b 
			INNER JOIN ".$this->categoryTable." as c ON c.categoryid = b.categoryid ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'WHERE b.bname LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= 'OR c.name LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= 'OR b.status LIKE "%'.$_POST["search"]["value"].'%" ';		
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY b.id DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$brandData = array();	
		while( $brand = mysqli_fetch_assoc($result) ) {			
			$status = '';
			if($brand['status'] == 'active')	{
				$status = '<span class="label label-success">Active</span>';
			} else {
				$status = '<span class="label label-danger">Inactive</span>';
			}
			$brandRows = array();
			
			$brandRows[] = $brand['name'];
			$brandRows[] = $brand['bname'];
			$brandRows[] = $status;
			$brandRows[] = '<button type="button" name="update" id="'.$brand["id"].'" class="btn btn-primary btn-sm rounded-0  update" title="Update"><i class="fa fa-edit"></i></button><button type="button" name="delete" id="'.$brand["id"].'" class="btn btn-danger btn-sm rounded-0  delete" data-status="'.$brand["status"].'" title="Delete"><i class="fa fa-trash"></i></button>';
			$brandData[] = $brandRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$brandData
		);
		echo json_encode($output);
	}
	public function categoryDropdownList(){		
		$sqlQuery = "SELECT * FROM ".$this->categoryTable." 
			WHERE status = 'active' 
			ORDER BY name ASC";	
		$result = mysqli_query($this->dbConnect, $sqlQuery);	
		$categoryHTML = '';
		while( $category = mysqli_fetch_assoc($result)) {
			$categoryHTML .= '<option value="'.$category["categoryid"].'">'.$category["name"].'</option>';	
		}
		return $categoryHTML;
	}

	public function saveBrand() {		
		$sqlInsert = "
			INSERT INTO ".$this->brandTable."(categoryid, bname) 
			VALUES ('".$_POST["categoryid"]."', '".$_POST['bname']."')";		
		mysqli_query($this->dbConnect, $sqlInsert);
		echo 'New Brand Added';
	}	
	public function getBrand(){
		$sqlQuery = "
			SELECT * FROM ".$this->brandTable." 
			WHERE id = '".$_POST["id"]."'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);	
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		echo json_encode($row);
	}	
	public function updateBrand() {		
		if($_POST['id']) {	
			$sqlUpdate = "UPDATE ".$this->brandTable." SET bname = '".$_POST['bname']."', categoryid='".$_POST['categoryid']."' WHERE id = '".$_POST["id"]."'";
			mysqli_query($this->dbConnect, $sqlUpdate);	
			echo 'Brand Update';
		}	
	}	

	public function deleteBrand(){
		$sqlQuery = "
			DELETE FROM ".$this->brandTable." 
			WHERE id = '".$_POST["id"]."'";	
		mysqli_query($this->dbConnect, $sqlQuery);		
	}



	public function getCategoryBrand($categoryid){	
		$sqlQuery = "SELECT * FROM ".$this->brandTable." 
			WHERE status = 'active' AND categoryid = '".$categoryid."'	ORDER BY bname ASC";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$dropdownHTML = '';
		while( $brand = mysqli_fetch_assoc($result) ) {	
			$dropdownHTML .= '<option value="'.$brand["id"].'">'.$brand["bname"].'</option>';
		}
		return $dropdownHTML;
	}

	public function supplierDropdownList(){	
		
		$sqlQuery = "SELECT * FROM ".$this->supplierTable." 
			WHERE status = 'active'	ORDER BY supplier_name ASC";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$dropdownHTML = '';
		while( $supplier = mysqli_fetch_assoc($result) ) {	
			$dropdownHTML .= '<option value="'.$supplier["supplier_id"].'">'.$supplier["supplier_name"].'</option>';
		}
		return $dropdownHTML;
	}
	

	// Product management 
	public function getProductList() {
		$sqlQuery = "SELECT * FROM ".$this->productTable;
	
		if (isset($_POST["search"]["value"])) {
			$sqlQuery .= ' WHERE pname LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR quantity LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR pid LIKE "%'.$_POST["search"]["value"].'%" ';
		}
	
		if (isset($_POST['order'])) {
			$sqlQuery .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= ' ORDER BY pid DESC ';
		}
	
		if ($_POST['length'] != -1) {
			$sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}
	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if (!$result) {
			echo json_encode(array(
				"draw" => intval($_POST["draw"]),
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => []
			));
			return;
		}
	
		$numRows = mysqli_num_rows($result);
		$productData = array();
		while ($product = mysqli_fetch_assoc($result)) {
			$status = ($product['status'] == 'active') ? 
				'<span class="label label-success">Active</span>' : 
				'<span class="label label-danger">Inactive</span>';
	
			$productRow = array();
			$productRow[] = $product['pname'];
			$productRow[] = $product['expire'];
			$productRow[] = $product['unit'];
			$productRow[] = $product["quantity"];
			$productRow[] = $status;
			$productRow[] = '<div class="btn-group btn-group-sm"><button type="button" name="view" id="'.$product["pid"].'" class="btn btn-light bg-gradient border text-dark btn-sm rounded-0 view" title="View"><i class="fa fa-eye"></i></button><button type="button" name="update" id="'.$product["pid"].'" class="btn btn-primary btn-sm rounded-0 update" title="Update"><i class="fa fa-edit"></i></button><button type="button" name="delete" id="'.$product["pid"].'" class="btn btn-danger btn-sm rounded-0 delete" data-status="'.$product["status"].'" title="Delete"><i class="fa fa-trash"></i></button></div>';
			$productData[] = $productRow;
		}
	
		$outputData = array(
			"draw" => intval($_POST["draw"]),
			"recordsTotal" => $numRows,
			"recordsFiltered" => $numRows,
			"data" => $productData
		);
	
		echo json_encode($outputData);
	}
	
	public function addProduct() {
		// Check if the product with the same name and unit already exists
		$checkQuery = "
			SELECT * FROM ".$this->productTable." 
			WHERE pname = '".$_POST['pname']."' 
			AND unit = '".$_POST['unit']."'";
		$checkResult = mysqli_query($this->dbConnect, $checkQuery);
		
		if (mysqli_num_rows($checkResult) > 0) {
			// Product already exists
			echo 'Error: Product with the same name and unit already exists.';
		} else {
			// Insert the new product
			$sqlInsert = "
				INSERT INTO ".$this->productTable." (pname, expire, description, quantity, unit, base_price, minimum_order) 
				VALUES ('".$_POST['pname']."', '".$_POST['expire']."', '".$_POST['description']."', '".$_POST['quantity']."', '".$_POST['unit']."', '".$_POST['base_price']."', 1)";
			mysqli_query($this->dbConnect, $sqlInsert);
			echo 'New Product Added';
		}
	}
	
	public function getProductDetails(){
		$sqlQuery = "
			SELECT * FROM ".$this->productTable." 
			WHERE pid = '".$_POST["pid"]."'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);	
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		echo json_encode($row);
		
	}
	
	public function updateProduct() {		
		if(isset($_POST['pid'])) {	
			$sqlUpdate = "UPDATE ".$this->productTable." 
				SET pname='".$_POST['pname']."', expire='".$_POST['pexpire']."', description='".$_POST['description']."', quantity='".$_POST['quantity']."', unit='".$_POST['unit']."', base_price='".$_POST['base_price']."' WHERE pid = '".$_POST["pid"]."'";			
			mysqli_query($this->dbConnect, $sqlUpdate);	
			echo 'Product Updated';
		} else {
			echo 'Error: Product ID not provided';
		}
	}
	
	public function deleteProduct(){
		$sqlQuery = "
			DELETE FROM ".$this->productTable." 
			WHERE pid = '".$_POST["pid"]."'";	
		mysqli_query($this->dbConnect, $sqlQuery);		
	}	
	
	public function viewProductDetails(){
		$sqlQuery = "SELECT * FROM ".$this->productTable." as p
			WHERE p.pid = '".$_POST["pid"]."'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$productDetails = '<div class="table-responsive">
				<table class="table table-bordered">';
		while( $product = mysqli_fetch_assoc($result) ) {
			$status = '';
			if($product['status'] == 'active') {
				$status = '<span class="label label-success">Active</span>';
			} else {
				$status = '<span class="label label-danger">Inactive</span>';
			}
			$productDetails .= '
			<tr>
				<td>Product Name</td>
				<td>'.$product["pname"].'</td>
			</tr>
			<tr>
				<td>Expiration Date</td>
				<td>'.$product["expire"].'</td>
			</tr>
			<tr>
				<td>Product Description</td>
				<td>'.$product["description"].'</td>
			</tr>
			<tr>
				<td>Available Quantity</td>
				<td>'.$product["quantity"].' '.$product["unit"].'</td>
			</tr>
			<tr>
				<td>Base Price</td>
				<td>'.$product["base_price"].'</td>
			</tr>
			<tr>
				<td>Status</td>
				<td>'.$status.'</td>
			</tr>
			';
		}
		$productDetails .= '
			</table>
		</div>
		';
		echo $productDetails;
	}
	
	// supplier 
	public function getSupplierList(){		
		$sqlQuery = "SELECT * FROM ".$this->supplierTable." ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'WHERE (supplier_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= '(address LIKE "%'.$_POST["search"]["value"].'%" ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY supplier_id DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$supplierData = array();	
		while( $supplier = mysqli_fetch_assoc($result) ) {	
			$status = '';
			if($supplier['status'] == 'active') {
				$status = '<span class="label label-success">Active</span>';
			} else {
				$status = '<span class="label label-danger">Inactive</span>';
			}
			$supplierRows = array();
			
			$supplierRows[] = $supplier['supplier_name'];	
			$supplierRows[] = $supplier['mobile'];			
			$supplierRows[] = $supplier['address'];	
			$supplierRows[] = $status;			
			$supplierRows[] = '<div class="btn-group btn-group-sm"><button type="button" name="update" id="'.$supplier["supplier_id"].'" class="btn btn-primary btn-sm rounded-0  update" title="Update"><i class="fa fa-edit"></i></button><button type="button" name="delete" id="'.$supplier["supplier_id"].'" class="btn btn-danger btn-sm rounded-0  delete"  title="Delete"><i class="fa fa-trash"></i></button></div>';
			$supplierData[] = $supplierRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$supplierData
		);
		echo json_encode($output);
	}
	public function addSupplier() {		
		$sqlInsert = "
			INSERT INTO ".$this->supplierTable."(supplier_name, mobile, address) 
			VALUES ('".$_POST['supplier_name']."', '".$_POST['mobile']."', '".$_POST['address']."')";		
		mysqli_query($this->dbConnect, $sqlInsert);
		echo 'New Supplier Added';
	}			
	public function getSupplier(){
		$sqlQuery = "
			SELECT * FROM ".$this->supplierTable." 
			WHERE supplier_id = '".$_POST["supplier_id"]."'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);	
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		echo json_encode($row);
	}

	public function updateSupplier() {
		if($_POST['supplier_id']) {	
			$sqlUpdate = "
				UPDATE ".$this->supplierTable." 
				SET supplier_name = '".$_POST['supplier_name']."', mobile= '".$_POST['mobile']."' , address= '".$_POST['address']."'	WHERE supplier_id = '".$_POST['supplier_id']."'";		
			mysqli_query($this->dbConnect, $sqlUpdate);	
			echo 'Supplier Edited';
		}	
	}	
	public function deleteSupplier(){
		$sqlQuery = "
			DELETE FROM ".$this->supplierTable." 
			WHERE supplier_id = '".$_POST['supplier_id']."'";		
		mysqli_query($this->dbConnect, $sqlQuery);		
	}


	public function OrderDropdownList(){	
		$sqlQuery = "SELECT * FROM ".$this->productTable." ORDER BY pname ASC";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$dropdownHTML = '';
		while( $product = mysqli_fetch_assoc($result) ) {	
			$dropdownHTML .= '<option value="'.$product["pid"].'">'.$product["pname"].'</option>';
		}
		return $dropdownHTML;
	}

	// purchase
	public function listPurchase(){		
		$sqlQuery = "SELECT ph.*, p.pname, s.supplier_name FROM ".$this->purchaseTable." as ph
			INNER JOIN ".$this->productTable." as p ON p.pid = ph.product_id 
			INNER JOIN ".$this->supplierTable." as s ON s.supplier_id = ph.supplier_id ";
		if(isset($_POST['order'])) {
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY ph.purchase_id DESC ';
		}
		if($_POST['length'] != -1) {
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}		
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$purchaseData = array();	
		while( $purchase = mysqli_fetch_assoc($result) ) {			
			$productRow = array();
			$productRow[] = $purchase['pname'];
			$productRow[] = $purchase['quantity'];			
			$productRow[] = $purchase['supplier_name'];			
			$productRow[] = '<div class="btn-group btn-group-sm"><button type="button" name="update" id="'.$purchase["purchase_id"].'" class="btn btn-primary btn-sm rounded-0  update" title="Update"><i class="fa fa-edit"></i></button><button type="button" name="delete" id="'.$purchase["purchase_id"].'" class="btn btn-danger btn-sm rounded-0  delete" title="Delete"><i class="fa fa-trash"></i></button></div>';
			$purchaseData[] = $productRow;
						
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$purchaseData
		);
		echo json_encode($output);		
	}
	public function productDropdownList(){	
		$sqlQuery = "SELECT * FROM ".$this->productTable." ORDER BY pname ASC";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$dropdownHTML = '';
		while( $product = mysqli_fetch_assoc($result) ) {	
			$dropdownHTML .= '<option value="'.$product["pid"].'">'.$product["pname"].'</option>';
		}
		return $dropdownHTML;
	}
	public function addPurchase() {		
		$sqlInsert = "
			INSERT INTO ".$this->purchaseTable."(product_id, quantity, supplier_id) 
			VALUES ('".$_POST['product']."', '".$_POST['quantity']."', '".$_POST['supplierid']."')";		
		mysqli_query($this->dbConnect, $sqlInsert);
		echo 'New Purchase Added';
	}	
	public function getPurchaseDetails(){
		$sqlQuery = "
			SELECT * FROM ".$this->purchaseTable." 
			WHERE purchase_id = '".$_POST["purchase_id"]."'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);	
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		echo json_encode($row);
	}
	public function updatePurchase() {
		if($_POST['purchase_id']) {	
			$sqlUpdate = "
				UPDATE ".$this->purchaseTable." 
				SET product_id = '".$_POST['product']."', quantity= '".$_POST['quantity']."' , supplier_id= '".$_POST['supplierid']."'	WHERE purchase_id = '".$_POST['purchase_id']."'";		
			mysqli_query($this->dbConnect, $sqlUpdate);	
			echo 'Purchase Edited';
		}	
	}	
	public function deletePurchase(){
		$sqlQuery = "
			DELETE FROM ".$this->purchaseTable." 
			WHERE purchase_id = '".$_POST['purchase_id']."'";		
		mysqli_query($this->dbConnect, $sqlQuery);		
	}
	
	// order
	public function listOrders() {
		$sqlQuery = "SELECT * FROM ".$this->orderTable." as o
			INNER JOIN ".$this->productTable." as p ON p.pid = o.product_id ";
		if (isset($_POST['order'])) {
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY o.order_id DESC ';
		}
		if ($_POST['length'] != -1) {
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$orderData = array();
		while ($order = mysqli_fetch_assoc($result)) {
			$orderRow = array();
			//$orderRow[] = $order['order_id'];
			$orderRow[] = $order['pname'];
			$orderRow[] = $order['total_shipped'];
			$orderRow[] = $order['date'];
			$orderRow[] = '<div class="btn-group btn-group-sm"><button type="button" name="update" id="'.$order["order_id"].'" class="btn btn-primary btn-sm rounded-0 update" title="Update"><i class="fa fa-edit"></i></button><button type="button" name="delete" id="'.$order["order_id"].'" class="btn btn-danger btn-sm rounded-0 delete" title="Delete"><i class="fa fa-trash"></i></button></div>';
			$orderData[] = $orderRow;
		}
		$output = array(
			"draw" => intval($_POST["draw"]),
			"recordsTotal" => $numRows,
			"recordsFiltered" => $numRows,
			"data" => $orderData
		);
		echo json_encode($output);
		exit();  // Ensure no further output
	}
	
	public function addOrder() {		
		$sqlInsert = "
			INSERT INTO ".$this->orderTable."(product_id, total_shipped, date) 
			VALUES ('".$_POST['product']."', '".$_POST['shipped']."', '".$_POST['date']."')";		
		mysqli_query($this->dbConnect, $sqlInsert);
		echo 'New order added';
	}		
	public function getOrderDetails() {
		$order_id = mysqli_real_escape_string($this->dbConnect, $_POST["order_id"]);
	
		$sqlQuery = "
			SELECT * FROM " . $this->orderTable . " 
			WHERE order_id = '$order_id'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if ($result) {
			$row = mysqli_fetch_assoc($result);
			echo json_encode($row);
		} else {
			echo "Error: " . mysqli_error($this->dbConnect);
		}
	}

	public function updateOrder() {
		if($_POST['order_id']) {	
			$sqlUpdate = "
				UPDATE ".$this->orderTable." 
				SET product_id = '".$_POST['product']."', total_shipped='".$_POST['shipped']."', date='".$_POST['date']."' WHERE order_id = '".$_POST['order_id']."'";		
			mysqli_query($this->dbConnect, $sqlUpdate);	
			echo 'Order Edited';
		}	
	}	
	public function deleteOrder(){
		$sqlQuery = "
			DELETE FROM ".$this->orderTable." 
			WHERE order_id = '".$_POST['order_id']."'";		
		mysqli_query($this->dbConnect, $sqlQuery);		
	}

	public function getInventoryDetails() {		
		$sqlQuery = "SELECT p.pid, p.pname, p.expire, (p.quantity) AS product_quantity, 
					(s.quantity) AS received_quantity, SUM(r.total_shipped) AS total_shipped
					FROM ".$this->productTable." as p
					LEFT JOIN ".$this->purchaseTable." as s ON s.product_id = p.pid
					LEFT JOIN ".$this->orderTable." as r ON r.product_id = p.pid 
					GROUP BY p.pid, p.pname, p.expire";	
	
		if(isset($_POST['order'])) {
			$sqlQuery .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= ' ORDER BY p.pid DESC ';
		}
	
		if($_POST['length'] != -1) {
			$sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}		
	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$inventoryData = array();	
		$i = 1;
	
		while( $inventory = mysqli_fetch_assoc($result) ) {	
	
			if(!$inventory['received_quantity']) {
				$inventory['received_quantity'] = 0;
			}
			if(!$inventory['total_shipped']) {
				$inventory['total_shipped'] = 0;
			}
	
			$inventoryInHand = ($inventory['product_quantity'] + $inventory['received_quantity']) - $inventory['total_shipped'];
	
			$expirationDate = strtotime('+5 days');
			$expirationMessage = '';
			if ($inventory['expire'] != null && strtotime($inventory['expire']) <= $expirationDate) {
				$expirationMessage = 'Expiration date is within 5 days.';
			}
	
			$inventoryRow = array();
			$inventoryRow[] = $i++;
			$inventoryRow[] = $inventory['pname'];
			$inventoryRow[] = $inventory['expire'];
			$inventoryRow[] = $inventory['product_quantity'];
			$inventoryRow[] = $inventory['received_quantity'];    
			$inventoryRow[] = $inventory['total_shipped'];
			$inventoryRow[] = $inventoryInHand;		
			$inventoryData[] = $inventoryRow;						
		}
	
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$inventoryData
		);
	
		echo json_encode($output);	
	}
	
	
}
?>