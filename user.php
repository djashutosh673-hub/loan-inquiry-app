<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB CONNECTION
$serverName = "cosmosrds.cnoo4wwa6kfo.ap-south-1.rds.amazonaws.com";

$connectionOptions = [
    "Database" => "Cosmos_Stamp",
    "Uid" => "cosmos",
    "PWD" => "4321aeiou",
    "Encrypt" => false,
    "TrustServerCertificate" => true,
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("Connection failed");
}

// GET AADHAAR FROM URL
$aadhaar = $_GET['aadhaar'];

// FETCH ONLY THAT USER DATA
$sql = "SELECT * FROM Inquiries WHERE AadhaarNumber = ?";
$stmt = sqlsrv_query($conn, $sql, [$aadhaar]);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Loan Details</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
        }

        table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #28a745;
            color: white;
            padding: 10px;
        }

        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        h2 {
            text-align: center;
        }

        .btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            padding: 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<h2>✅ Your Submitted Loan Details</h2>

<table>
<tr>
    <th>Name</th>
    <th>Phone</th>
    <th>Email</th>
    <th>City</th>
    <th>Loan</th>
    <th>Amount</th>
</tr>

<tr>
    <td><?php echo $row['FullName']; ?></td>
    <td><?php echo $row['PhoneNumber']; ?></td>
    <td><?php echo $row['Email']; ?></td>
    <td><?php echo $row['City']; ?></td>
    <td><?php echo $row['LoanType']; ?></td>
    <td><?php echo $row['LoanAmount']; ?></td>
</tr>

</table>

<a href="index.html" class="btn">⬅ Back</a>

</body>
</html>