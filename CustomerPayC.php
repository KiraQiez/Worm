<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .message {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>


<body>

    <?php 
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        file_put_contents('payment_status.txt', 'confirmed');
    }
    ?>
    <div class="message">
        <h1>Payment Confimation</h1>
        <p>Click the button below to confirm your payment.</p>
        <form action="CustomerPayC.php" method="POST">
        <button type="Submit">Confirm Payment</button>
        </form>
    </div>
</body>
</html>
