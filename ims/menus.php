<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
   
    <style>
        html,
        body {
            height: 100%;
            background-color: rgb(220, 220, 220);
        }

        .navbar-menu {
            display: flex;
            justify-content: center;
            width: 100%;
            
        }

        .navbar-menu .nav-item {
            margin: 0 10px;
            padding: 10px;
           
        }

        .nav-link {
            font-size: 20px;
            display: flex;
            align-items: center;
        }

        .nav-link i {
            margin-right: 8px;
            padding-left: 50px;
        }

        .navbar-toggler {
            background-color: rgb(220, 220, 220);
        }

        .navbar-toggler-icon {
            color: rgb(220, 220, 220);
        }
    </style>
</head>
<body>
<nav class="nav-container navbar-expand-md" style="height: 100%">

    <div class="menu-container">
        <button class="navbar-toggler align-self-start" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav navbar-menu flex-column menus" style="font-size: 20px;">
                <li class="nav-item"><a class="nav-link" href="index.php" id="index_menu"><i class="fas fa-home" style="margin-right: 15px;" ></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="category.php" id="category_menu"><i class="fas fa-list" style="margin-right: 15px;"></i> Category</a></li>
                <li class="nav-item"><a class="nav-link" href="brand.php" id="brand_menu"><i class="fas fa-tag" style="margin-right: 15px;"></i> Brand</a></li>
                <li class="nav-item"><a class="nav-link" href="supplier.php" id="supplier_menu"><i class="fas fa-truck" style="margin-right: 15px;"></i> Supplier</a></li>
                <li class="nav-item"><a class="nav-link" href="product.php" id="product_menu"><i class="fas fa-box" style="margin-right: 15px;"></i> Product</a></li>
                <li class="nav-item"><a class="nav-link" href="purchase.php" id="purchase_menu"><i class="fas fa-shopping-cart" style="margin-right: 15px;"></i>Purchase</a></li>
                <li class="nav-item"><a class="nav-link" href="order.php" id="order_menu"><i class="fas fa-receipt" style="margin-right: 15px;"></i> Order</a></li>
            </ul>
        </div>
    </div>
</nav>


</body>
</html>
