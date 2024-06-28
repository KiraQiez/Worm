<?php
$title = "Library";
include 'CustomerHeader.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #f0f0f0;
            margin: 0;
            padding: 0;
            color: black;
        }
        .content {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #ffa500;
            margin-bottom: 20px;
        }
        .payment-details {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .payment-details img {
            max-width: 200px;
            border-radius: 8px;
            margin-right: 20px;
        }
        .payment-info {
            flex-grow: 1;
        }
        .payment-info div {
            margin: 10px 0;
            font-size: 1.1em;
        }
        .total-amount {
            text-align: right;
            font-size: 1.2em;
            margin-top: 20px;
        }
        .buttons {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 20px;
        }
        .button {
            background-color: #ffa500;
            border: none;
            padding: 10px 20px;
            color: #1a1a1a;
            text-transform: uppercase;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1em;
            margin-left: 10px;
        }
        .button:hover {
            background-color: #cc8400;
        }
        .button-upload {
            display: flex;
            align-items: center;
            margin-right: auto;
        }
        .button-upload input {
            margin-left: 10px;
        }
        .button-back {
            background-color: #343A40;
            color: #f0f0f0;
        }
        .button-back:hover {
            background-color: #1a1a1a;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Payment Details</h1>
        <div class="payment-details">
            <img src="items/dotn.jpg" alt="The Diary of a CEO">
            <div class="payment-info">
                <div><strong>THE DIARY OF A CEO</strong></div>
                <div>RENTAL DURATION: 1 Month</div>
                <div>12 JAN 2024 - 12 FEB 2024</div>
                <div>DEPOSIT: RM 25.00</div>
                <div>RENTAL PRICE: RM 5.00</div>
                <div>SUBTOTAL: RM 30.00</div>
            </div>
        </div>
        <div class="total-amount">
            <strong>Total Amount: RM 30.00</strong>
        </div>
        <div class="buttons">
            <label for="receipt-upload" class="button button-upload">
                Attach your receipt: <input type="file" id="receipt-upload" style="display: none;">
            </label>
            <button class="button button-back" onclick="history.back();">Back</button>
            <button class="button">Submit</button>
        </div>
    </div>
</body>
</html>