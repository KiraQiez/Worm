<?php
include 'db.php';
$register_message = "Please fill out the form to register.";

function generate_std_id($conn)
{
    $sql = "SELECT std_id FROM students ORDER BY std_id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['std_id'];
        $num = intval(substr($last_id, 1)) + 1;
        return 'U' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        return 'U001';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $full_name = $_POST["full-name"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $std_id = generate_std_id($conn);

    // Check if the username or email already exists
    $check_sql = "SELECT * FROM students WHERE username = ? OR email = ?";
    if ($check_stmt = $conn->prepare($check_sql)) {
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $register_message = "Username or email already exists. Please try another.";
        } else {
            // Insert the new record
            $sql = "INSERT INTO students (std_id, username, full_name, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssssss", $std_id, $username, $full_name, $gender, $email, $password);
                if ($stmt->execute()) {
                    $register_message = "Registration successful!";
                } else {
                    $register_message = "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $register_message = "Error: " . $conn->error;
            }
        }
        $check_stmt->close();
    } else {
        $register_message = "Error: " . $conn->error;
    }
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            <h2 class="text-center mb-4">Register</h2>
            <hr>
            <form action="register.php" method="POST" class="needs-validation" novalidate>
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
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <div class="form-floating flex-grow-1">
                            <input type="text" class="form-control" id="full-name" name="full-name" placeholder="Full Name" required>
                            <label for="full-name">Full Name</label>
                            <div class="invalid-feedback">
                                Please enter your full name.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                        <div class="form-floating flex-grow-1">
                            <select class="form-select" id="gender" name="gender" required>
                                <option selected disabled value="">Gender</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                            <label for="gender">Gender</label>
                            <div class="invalid-feedback">
                                Please select your gender.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <div class="form-floating flex-grow-1">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                            <label for="email">Email</label>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
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
                                Please enter a password.
                            </div>
                        </div>
                    </div>
                </div>
                <button id='register-button' type="submit" class="btn btn-primary btn-block w-100">Register</button>
            </form>
            <p class="mt-3 text-center">Already have an account? <a href="login.php" class="text-primary">Login</a></p>
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
                echo $register_message;
                $register_message = "Please fill out the form to register.";
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var forms = document.querySelectorAll('.needs-validation');

            Array.prototype.slice.call(forms).forEach(function(form) {
                var inputs = form.querySelectorAll('input, select');

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

        const toastTrigger = document.getElementById('register-button')
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