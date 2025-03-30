<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = htmlspecialchars($_POST["name"]);
    $number = htmlspecialchars($_POST["number"]);
    $start_date = isset($_POST["start_date"]) ? htmlspecialchars($_POST["start_date"]) : null;
    $end_date = isset($_POST["end_date"]) ? htmlspecialchars($_POST["end_date"]) : null;
    if ($start_date) {
        $start_date = DateTime::createFromFormat('Y-m-d', $start_date)->format('F j, Y');
    }
    
    if ($end_date) {
        $end_date = DateTime::createFromFormat('Y-m-d', $end_date)->format('F j, Y');
    }
    
    $room_type = isset($_POST["room-type"]) ? htmlspecialchars($_POST["room-type"]) : null;
    $room_capacity = isset($_POST["room-capacity"]) ? htmlspecialchars($_POST["room-capacity"]) : null;
    $room_payment = isset($_POST["room-payment"]) ? htmlspecialchars($_POST["room-payment"]) : null;
    $current_time = isset($_POST["current_time"]) ? htmlspecialchars($_POST["current_time"]) : "Not received";

    // Days calculation
    $days = 0;
    if ($start_date && $end_date) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = $start->diff($end);
        $days = $interval->days;
    }

    // Room rates
    $rates = [
        "single" => ["regular" => 100, "deluxe" => 300, "suite" => 500],
        "double" => ["regular" => 200, "deluxe" => 500, "suite" => 800],
        "family" => ["regular" => 500, "deluxe" => 750, "suite" => 1000]
    ];

    $ratePerDay = $rates[$room_capacity][$room_type] ?? 0;

    // Payment additional charges
    $additionalCharges = [
        "cash" => 0,
        "cheque" => 0.05,
        "credit" => 0.10
    ];
    $additionalCharge = $additionalCharges[$room_payment] ?? 0;

    // Cash discount
    $discount = 0;
    if ($room_payment == "cash") {
        if ($days >= 3 && $days <= 5) {
            $discount = 0.10;
        } else if ($days >= 6) {
            $discount = 0.15;
        }
    }

    // Calculate subtotal, additional charge, and discount
    $subtotal = $ratePerDay * $days;
    $additionalChargeAmount = $subtotal * $additionalCharge;
    $discountAmount = $subtotal * $discount;

    // Final total
    $total = ($subtotal + $additionalChargeAmount) - $discountAmount;

} else {
    echo "Invalid Request.";
}
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">

    <style>
           <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .receipt-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            border: #333 solid 1px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        .customer-info, .billing-info {
            margin-top: 20px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .billing-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .billing-table th, .billing-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        .billing-table th {
            background-color: #007bff;
            color: white;
            text-align: left;
        }
        .total {
            font-weight: bold;
            color: #007bff;
        }
    </style>
    </style>
</head>
<body>
<div class="container">
        <div class="nav">
            <div id="brand">
                <h1><a href="index.html">INNOVA&Co.</a></h1>
                </div>
        
            <div id="links">
                <ul>
    
                    <li><a href="index.html">Home</a></li>
                    <li><a href="reservation.html">Reservation</a></li>
                    <li><a href="company.html">Company Profile</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </div>
    
        </div>
        
        <div class="reservation-container">
             <!-- This is the form flexbox-->
             <div class="contitle"><h1>Billing Information</h1> </div>

             <div class="receipt-container">
    <div class="header">
        <p>Enjoy our <strong>10% discount</strong> for 3-5 days of reservation or <br>
        Enjoy our <strong>15% discount</strong> for 6 days or above of reservation</p>
    </div>

    <div class="customer-info">
        <div class="row"><strong>Customer Name:</strong> <span><?php echo $name; ?></span></div>
        <div class="row"><strong>Contact Number:</strong> <span><?php  echo $number; ?></span></div>
        <div class="row"><strong>Date Reserved:</strong> <span><?php  echo $current_time?></span></div>
    </div>

    <div class="customer-info">
        <div class="row"><strong>Date of Reservation:</strong></div>
        <div class="row"><strong>From:</strong> <span><?php echo $start_date; ?></span></div>
        <div class="row"><strong>To:</strong> <span><?php  echo $end_date; ?></span></div>
    </div>

    <div class="customer-info">
        <div class="row"><strong>Room Type:</strong> <span><?php echo  $room_type ;?></span></div>
        <div class="row"><strong>Room Capacity:</strong> <span><?php echo $room_capacity; ?></span></div>
        <div class="row"><strong>Payment Type:</strong> <span><?php echo$room_payment; ?></span></div>
    </div>

    <div class="billing-info">
        <table class="billing-table">
            <tr>
                <th>BILLING STATEMENTS</th>
                <th></th>
            </tr>
            <tr>
                <td>No. of Days:</td>
                <td><?php echo$days; ?></td>
            </tr>
            <tr>
                <td>Sub-Total:</td>
                <td>₱<?php echo$subtotal; ?></td>
            </tr>
            <tr>
                <td>Discount:</td>
                <td>₱ <?php echo$discountAmount; ?></td>
            </tr>
            <tr class="total">
                <td>Total Bill:</td>
                <td>₱ <?php echo$total; ?></td>
            </tr>
        </table>
    </div>
</div>

<div class="submit-reservation">
    <div class="button-reserve">
        <button onclick="window.location.href='index.html';">Return</button>
    </div>
</div>


        </div>









        
        <div class="footer"> <h3>Innovatech Solutions © 2025</h3></div>
    </div>

    
</body>
</html>