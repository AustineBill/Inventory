<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        html,
        body {
            height: 100%;
            background-color: #d3d3d3; /* Light gray background color */
        }

        .navbar-menu {
            display: flex;
            justify-content: center;
            width: 100%;
            background-color: #d3d3d3; /* Light gray background color */
        }

        .navbar-menu .nav-item {
            margin: 0 10px;
            padding: 10px;
            background-color: #d3d3d3; /* Light gray background color */
        }

        .nav-link {
            font-size: 20px;
            display: flex;
            align-items: center;
        }

        .nav-link i {
            margin-right: 8px;
        }

        .navbar-toggler {
            background-color: #ffffff; /* White color for the navbar-toggler */
        }

        .navbar-toggler-icon {
            color: #000000; /* Black color for the navbar-toggler icon */
        }
    </style>
</head>
<body>
<nav class="nav-container navbar-dark bg-white bg-dark navbar-expand-md" style="height: 100%">

    <div class="menu-container">
        <button class="navbar-toggler align-self-start" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav navbar-menu flex-column menus" style="font-size: 20px;">
                <li class="nav-item"><a class="nav-link" href="index.php" id="index_menu"><i class="fas fa-home"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="category.php" id="category_menu"><i class="fas fa-list"></i> Category</a></li>
                <li class="nav-item"><a class="nav-link" href="brand.php" id="brand_menu"><i class="fas fa-tag"></i> Brand</a></li>
                <li class="nav-item"><a class="nav-link" href="supplier.php" id="supplier_menu"><i class="fas fa-truck"></i> Supplier</a></li>
                <li class="nav-item"><a class="nav-link" href="product.php" id="product_menu"><i class="fas fa-box"></i> Product</a></li>
                <li class="nav-item"><a class="nav-link" href="purchase.php" id="purchase_menu"><i class="fas fa-shopping-cart"></i> Purchase</a></li>
                <li class="nav-item"><a class="nav-link" href="order.php" id="order_menu"><i class="fas fa-receipt"></i> Order</a></li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
