<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to dashboard page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php");
    exit;
}

// Connect to the database
require_once "Config.php";

// Correct variables
$Email = $Password = $Name = $Date_of_Birth = "";

// Incorrect variables
$EmailErr = $PasswordErr = $loginErr = "";

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate email
    if (empty(trim($_POST["Email"]))) {
        $EmailErr = "Please enter email.";
    } else {
        $Email = trim($_POST["Email"]);
    }

    // Validate password
    if (empty(trim($_POST["Password"]))) {
        $PasswordErr = "Please enter your password.";
    } else {
        $Password = trim($_POST["Password"]);
    }

    // Validate credentials
    if (empty($EmailErr) && empty($PasswordErr)) {
        // Prepare a select statement
        $sql = "SELECT User_ID, Email, Name, Date_of_Birth, Password FROM users WHERE Email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_Email);

            // Set parameters
            $param_Email = $Email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $User_ID, $Email, $Name, $Date_of_Birth, $hashed_Password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($Password, $hashed_Password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["User_ID"] = $User_ID;
                            $_SESSION["Email"] = $Email;
                            $_SESSION["Name"] = $Name;
                            $_SESSION["Date_of_Birth"] = $Date_of_Birth;

                            // Redirect user to dashboard
                            header("Location: dashboard.php");
                        } else {
                            // Password is not valid
                            $loginErr = "Invalid email or password.";
                        }
                    }
                } else {
                    // Email doesn't exist
                    $loginErr = "No account found with that email.";
                }
            } else {
                // SQL execution error
                $loginErr = "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!doctype html>
<html lang="en">
<head>
  <title>Login Form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link href="assets/css/login.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center">
  <div class="container">
    <div class="row justify-content-center" style="margin:20px;">
      <div class="col-lg-6 col-md-8 login-box">
        <div class="col-lg-12 login-title">
          Login to Your Account
        </div>
        <div class="col-lg-12 login-form">
          <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <?php
            if (!empty($loginErr)) {
                echo '<div class="alert alert-danger">' . $loginErr . '</div>';
            }
            ?>
            <div class="form-group">
              <label class="form-control-label">Email</label>
              <input type="email" name="Email" class="form-control <?php echo (!empty($EmailErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $Email; ?>">
              <span class="invalid-feedback"><?php echo $EmailErr; ?></span>
            </div>
            <div class="form-group">
              <label class="form-control-label">Password</label>
              <input type="password" name="Password" class="form-control <?php echo (!empty($PasswordErr)) ? 'is-invalid' : ''; ?>">
              <span class="invalid-feedback"><?php echo $PasswordErr; ?></span>
            </div>
            <div class="col-12 login-btm login-button justify-content-center d-flex">
              <button type="submit" class="btn btn-outline-primary">Login</button>
            </div>
          </form>
          <div class="form-group text-center">
            <p class="login-text">Don't have an account? <a href="register.php" class="login-link">Register here</a>.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
  <script src="assets/js/login.js"></script>
</body>
</html>
