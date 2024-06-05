<?php
ob_start();
session_start();
include('inc/header.php');

$loginError = '';
if (!empty($_POST['email']) && !empty($_POST['pwd'])) {
    include 'Inventory.php';
    $inventory = new Inventory();
    $login = $inventory->login($_POST['email'], $_POST['pwd']);
    
    if (!empty($login)) {
        // Correct email and password
        $_SESSION['userid'] = $login[0]['userid'];
        $_SESSION['name'] = $login[0]['name'];
        header("Location: index.php");
        exit();
    } else {
        // Incorrect email or password
        $loginError = "Invalid email or password!";
        // Debugging: Log the email and password to see if they are correct
        error_log("Login attempt failed for email: " . $_POST['email'] . ", password: " . $_POST['pwd']);
    }
}
?>


<style>
html,
body {
    height: 100%;
}

body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: linear-gradient(180deg, rgba(0,0,0,0.25), rgba(0,0,0,0.25));
}

#title {
    text-shadow: 2px 2px 5px #000;
}

.container {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.footer {
   
    color: white;
    width: 100%;
    text-align: center;
    padding: 0x;
    position: sticky;
    bottom: 0;
}

.card {
    width: 100%;
    max-width: 400px;
}

</style>

<div class="container">
    <h1 class="text-center my-4 py-3 text-light" id="title">Inventory System </h1>

    <div class="col-lg-4 col-md-10 col-sm-10 col-xs-12">
        <div class="card rounded-0 shadow">
            <div class="card-header">
                <div class="card-title h3 text-center mb-0 fw-bold">Login</div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <form method="post" action="">
                        <div class="form-group">
                            <?php if ($loginError) { ?>
                                <div class="alert alert-danger rounded-0 py-1"><?php echo $loginError; ?></div>
                            <?php } ?>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="control-label">Email</label>
                            <input name="email" id="email" type="email" class="form-control rounded-0" placeholder="Email address" autofocus="" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" class="form-control rounded-0" id="password" name="pwd" placeholder="Password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="login" class="btn btn-primary rounded-0">Login</button>
                        </div>
                    </form>
                    <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

