<?php
require_once "Config.php";

// Correct variables
$Name = $Email = $Password = $Date_of_Birth = $Confirm_Password = "";

// Incorrect variables
$NameErr = $EmailErr = $PasswordErr = $Date_of_BirthErr = $Confirm_PassworddErr = "";

// Regular expressions for validation
$emailPattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
$namePattern = "/^[A-Z][a-z]*([ ]?[A-Z][a-z]*)*$/";
$passwordPattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*[@$!%*?&])(?=(?:.*\d){6}$)[A-Za-z\d@$!%*?&]{6,}$/";
$dobPattern = "/^\d{4}-\d{2}-\d{2}$/";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Email
    if (empty(trim($_POST['Email']))) {
        $EmailErr = "Please enter your email";
    } elseif (!preg_match($emailPattern, trim($_POST["Email"]))) {
        $EmailErr = "Invalid email format";
    } else {
        $sql = "SELECT Email FROM users WHERE Email=?;";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_Email);
            $param_Email = trim($_POST["Email"]);
            if (mysqli_stmt_execute($stmt)) {
                // Store the result
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $EmailErr = "This email is already taken";
                } else {
                    $Email = trim($_POST["Email"]);
                }
            } else {
                echo "Something went wrong";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate Password
    if (empty(trim($_POST["Password"]))) {
        $PasswordErr = "Please enter a password";
    } elseif (strlen(trim($_POST["Password"])) < 6) {
        $PasswordErr = "Password must have at least 6 characters";
    } else {
        $Password = trim($_POST["Password"]);
    }

    // Validate Confirm Password
    if (empty(trim($_POST["Confirm_Password"]))) {
        $Confirm_PassworddErr = "Please confirm password";
    } else {
        $Confirm_Password = trim($_POST["Confirm_Password"]);
        if (empty($PasswordErr) && $Password != $Confirm_Password) {
            $Confirm_PassworddErr = "Passwords do not match";
        }
    }

    // Validate Name
    if (empty(trim($_POST['Name']))) {
        $NameErr = "Please enter your name";
    } elseif (!preg_match($namePattern, trim($_POST["Name"]))) {
        $NameErr = "Name must be in the format 'First Last' or 'First Middle Last'";
    } else {
        $Name = trim($_POST["Name"]);
    }

    // Validate Date of Birth
    if (empty(trim($_POST["Date_of_Birth"]))) {
        $Date_of_BirthErr = "Please enter your date of birth";
    } elseif (!preg_match($dobPattern, trim($_POST["Date_of_Birth"]))) {
        $Date_of_BirthErr = "Invalid date format";
    } else {
        $Date_of_Birth = trim($_POST["Date_of_Birth"]);
    }

    // If there are no errors, insert the user data into the database
    if (empty($NameErr) && empty($EmailErr) && empty($PasswordErr) && empty($Confirm_PassworddErr) && empty($Date_of_BirthErr)) {
        $sql = "INSERT INTO users (Name, Email, Password, Date_of_birth) VALUES (?,?,?,?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $param_Name, $param_Email, $param_Password, $param_Date_of_Birth);
            $param_Name = $Name;
            $param_Email = $Email;
            $param_Password = password_hash($Password, PASSWORD_DEFAULT); // Creates a password hash
            $param_Date_of_Birth = $Date_of_Birth;

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to register.php with a success parameter set to 1
                header("Location: register.php?success=1");
                exit();
            } else {
                // Redirect to register.php with a success parameter set to 0
                header("Location: register.php?success=0");
                exit();
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
  <title>Register Form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link href="assets/css/register.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center">
  <div class="container">
    <div class="row justify-content-center" style="margin:20px;">
      <div class="col-lg-6 col-md-8 login-box">
        <div class="col-lg-12 login-title">
          Create an Account
        </div>
        <div class="col-lg-12 login-form">
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
            <div class="form-group">
              <label class="form-control-label">Name</label>
              <input type="text" name="Name" class="form-control <?php echo (!empty($NameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $Name; ?> ">
              <span class="invalid-feedback"><?php echo $NameErr; ?></span>
            </div>
            <div class="form-group">
              <label class="form-control-label">Email</label>
              <input type="email" name="Email" class="form-control <?php echo (!empty($EmailErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $Email; ?>">
              <span class="invalid-feedback"><?php echo $EmailErr; ?></span>
            </div>
            <div class="form-group">
              <label class="form-control-label">Date of Birth</label>
              <input type="date" name="Date_of_Birth" class="form-control <?php echo (!empty($Date_of_BirthErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $Date_of_Birth; ?> ">
              <span class="invalid-feedback"><?php echo $Date_of_BirthErr; ?></span>
            </div>
            <div class="form-group">
              <label class="form-control-label">Password</label>
              <input type="password" name="Password" class="form-control <?php echo (!empty($PasswordErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $Password; ?>">
              <span class="invalid-feedback"><?php echo $PasswordErr; ?></span>
            </div>
            <div class="form-group">
              <label class="form-control-label">Confirm Password</label>
              <input type="password" name="Confirm_Password" class="form-control <?php echo (!empty($Confirm_PassworddErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $Confirm_Password; ?>">
              <span class="invalid-feedback"><?php echo $Confirm_PassworddErr; ?></span>
            </div>
            <div class="col-12 login-btm login-button justify-content-center d-flex">
              <button type="submit" class="btn btn-outline-primary">Register</button>
            </div>
          </form>
          <div class="form-group text-center">
            <p class="login-text">If you have an account, <a href="login.php" class="login-link">click here</a> to log in.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
  <script src="assets/js/register.js"></script>
</body>
</html>
