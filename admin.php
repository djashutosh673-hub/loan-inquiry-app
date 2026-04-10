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

// FETCH DATA
$sql = "SELECT * FROM Inquiries ORDER BY InquiryID DESC";
$stmt = sqlsrv_query($conn, $sql);

?>

<!DOCTYPE html>

<html>
<head>
    <title>Admin Panel - Inquiries</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
        }
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #28a745;
            color: white;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

<h2>📋 Loan Inquiries</h2>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Email</th>
    <th>City</th>
    <th>Loan</th>
    <th>Amount</th>
    <th>Income</th>
    <th>Aadhaar</th>
    <th>PAN</th>
</tr>

<?php
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>
        <td>{$row['InquiryID']}</td>
        <td>{$row['FullName']}</td>
        <td>{$row['PhoneNumber']}</td>
        <td>{$row['Email']}</td>
        <td>{$row['City']}</td>
        <td>{$row['LoanType']}</td>
        <td>{$row['LoanAmount']}</td>
        <td>{$row['MonthlyIncome']}</td>
        <td>{$row['AadhaarNumber']}</td>
        <td>{$row['PANNumber']}</td>
    </tr>";
}
?>

</table>

</body>
</html>
