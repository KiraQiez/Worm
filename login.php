<?php
include 'db.php';
$login_error = "Please enter your username and password.";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT password FROM students WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Password is correct, start session
            session_start();
            $_SESSION["username"] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid password
            $login_error = "Invalid password.";
        }
    } else {
        // Invalid username
        $login_error = "Invalid username";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="rsc/style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .input-group-text {
            height: calc(3.5rem + 2px);
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>
            <img src="rsc/image/logo.svg" alt="INTERIOR Logo"> WORM
        </h1>
        <div class="nav">
            <a href="index.html">Home</a>
            <a href="aboutUs.html">About Us</a>
            <a href="contact.html">Contact</a>
            <a href="login.php" class="active">Login</a>
        </div>
    </div>
    <div class="main-content">
        <div class="login-container">
            <h2 class="text-center mb-4">Login</h2>
            <hr>
            <form action="login.php" method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <div class="form-floating flex-grow-1">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            <label for="username">Username</label>
                            <div class="invalid-feedback">
                                Please enter your username.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <div class="form-floating flex-grow-1">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                            <div class="invalid-feedback">
                                Please enter your password.
                            </div>
                        </div>
                    </div>
                </div>
                <button id='login-button' type="submit" class="btn btn-primary btn-block w-100">Login</button>
            </form>
            <p class="mt-3 text-center">Don't have an account? <a href="register.php" class="text-primary">Register</a></p>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header" style='background-color:var(--header-background-color); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-color);'>
                <img src="rsc/image/logo.svg" class="rounded me-2" alt="Worm Logo" style="height: 20px;">
                <strong class="me-auto">Worm</strong>
            </div>
            <div class="toast-body" style="background-color: var(--background-color); color: var(--text-color); border: var(--border-color);">
                <?php
                echo $login_error;
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var forms = document.querySelectorAll('.needs-validation');

            Array.prototype.slice.call(forms).forEach(function(form) {
                var inputs = form.querySelectorAll('input');

                inputs.forEach(function(input) {
                    input.addEventListener('input', function() {
                        if (input.checkValidity()) {
                            input.classList.remove('is-invalid');
                            input.classList.add('is-valid');
                        } else {
                            input.classList.remove('is-valid');
                            input.classList.add('is-invalid');
                        }
                    });
                });

                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        });

        const toastTrigger = document.getElementById('login-button')
        const toastLiveExample = document.getElementById('liveToast')

        if (toastTrigger) {
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
            toastTrigger.addEventListener('click', () => {
                toastBootstrap.show()
            })
        }
    </script>
</body>

</html>