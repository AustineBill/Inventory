<nav role="navigation" class="navbar navbar-dark navbar-static-top" style="background-color: #343a40;">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" style="color: #ffffff;">Inventory System</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="dropdown position-relative">
                <button type="button" class="badge bg-light border px-3 text-dark rounded-pill dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #6c757d; color: #ffffff;">
                    <span class="badge badge-pill bg-danger count"></span>
                    <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest'; ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="action.php?action=logout" style="color: #343a40;">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
