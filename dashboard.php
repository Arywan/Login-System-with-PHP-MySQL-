<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header('Location: login.php');
    exit();
}

?>
<!doctype html>
<html lang="en">
<head>
  <title>Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link href="assets/css/dashboard.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center">
  <div class="container">
    <div class="row justify-content-center" style="margin:20px;">
      <div class="col-lg-8 col-md-10 login-box">
        <div class="col-lg-12 login-title">
          Welcome to Your Dashboard Mr <?php echo htmlspecialchars($_SESSION["Name"]); // Display user's username in the dashboard header    ?>
        </div>
        <div class="col-lg-12 login-form">
          <!-- Display user information -->
          <div class="form-group">
            <label class="form-control-label">Your Name</label>
            <p class="form-control"><?php echo htmlspecialchars($_SESSION["Name"]); ?></p>
          </div>
          <div class="form-group">
            <label class="form-control-label">Email</label>
            <p class="form-control"><?php echo htmlspecialchars($_SESSION["Email"]); ?> </p>
          </div>
          <!-- Image upload form -->
          <form method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label class="form-control-label">Upload Image</label>
              <input type="file" name="image" class="form-control">
            </div>
            <div class="col-12 login-btm login-button justify-content-center d-flex">
              <button type="submit" class="btn btn-outline-primary">Upload</button>
            </div>
          </form>
            <div class="col-12 login-btm login-button justify-content-center d-flex">
            <a href="reset-password.php" class="btn btn-outline-primary">Reset Password</a>
          </div>
          <!-- Sign out button -->
          <div class="col-12 login-btm login-button justify-content-center d-flex">
            <a href="logout.php" class="btn btn-outline-primary">Sign Out</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
  <script src="assets/js/dashboard.js"></script>
</body>
</html>
