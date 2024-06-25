<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="rsc/style/bootstrap.css">
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
            <img src="rsc/image/logo.svg" alt="INTERIOR Logo">
            WORM
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
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <div class="form-floating flex-grow-1">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        <label for="username">Username</label>
                        <div class="invalid-feedback">
                            Please enter a username.
                        </div>
                    </div>
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <div class="form-floating flex-grow-1">
                        <input type="text" class="form-control" id="full-name" name="full-name" placeholder="Full Name" required>
                        <label for="full-name">Full Name</label>
                        <div class="invalid-feedback">
                            Please enter your full name.
                        </div>
                    </div>
                </div>
                <div class="mb-3 input-group">
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
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <div class="form-floating flex-grow-1">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email">Email</label>
                        <div class="invalid-feedback">
                            Please enter a valid email address.
                        </div>
                    </div>
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <div class="form-floating flex-grow-1">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                        <div class="invalid-feedback">
                            Please enter a password.
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block w-100">Register</button>
            </form>

            <?php
            //Connection to the database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "wormdb";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            function sanitize_input($data)
            {
                return htmlspecialchars(stripslashes(trim($data)));
            }

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

                $username = sanitize_input($_POST["username"]);
                $full_name = sanitize_input($_POST["full-name"]);
                $gender = sanitize_input($_POST["gender"]);
                $email = sanitize_input($_POST["email"]);
                $password = sanitize_input($_POST["password"]);
                $std_id = generate_std_id($conn);


                $sql = "INSERT INTO students (std_id, username, full_name, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssssss", $std_id, $username, $full_name, $gender, $email, $password);
                    if ($stmt->execute()) {
                        echo "Registration successful!";
                    } else {    
                        echo "Error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error: " . $conn->error;
                }
            }
            $conn->close();
            ?>
            
            <hr>

            <p class="mt-3 text-center">Already have an account? <a href="login.php" class="text-primary">Login</a></p>
        </div>
    </div>
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
    </script>
</body>

</html>