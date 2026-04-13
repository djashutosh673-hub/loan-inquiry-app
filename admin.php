<?php
session_start();

// LOGIN CHECK
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// ERROR REPORTING
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
    die("❌ Connection failed");
}

// FETCH DATA
$sql = "SELECT * FROM Inquiries ORDER BY InquiryID DESC";
$stmt = sqlsrv_query($conn, $sql);

if (!$stmt) {
    die("❌ Query failed");
}

// FUNCTION TO SHOW FILE LINK SAFELY
function showFile($fileName) {
    if (!empty($fileName)) {
        return "<a href='uploads/$fileName' target='_blank'>View</a>";
    } else {
        return "No File";
    }
}
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
            width: 98%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
            font-size: 14px;
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

<h2>📋 Loan Inquiries (Admin Panel)</h2>

<table>
<tr>
    <th>ID</th>
<th>Name</th>
<th>Phone</th>
<th>Email</th>
<th>Address</th>
<th>City</th>
<th>State</th>
<th>Pincode</th>
<th>Loan</th>
<th>Amount</th>
<th>Tenure</th>
<th>Employment</th>
<th>Company</th>
<th>Income</th>
<th>Profile</th>
<th>Aadhaar</th>
<th>PAN</th>
<th>Salary</th>
<th>Bank</th>
<th>GST</th>
<th>Udyam</th>
<th>Applicant Aadhaar</th>
<th>Applicant PAN</th>
<th>Co-Aadhaar</th>
<th>Co-PAN</th>
<th>ITR</th>
</tr>

<?php
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>
        <td>{$row['InquiryID']}</td>
        <td>{$row['FullName']}</td>
        <td>{$row['PhoneNumber']}</td>
        <td>{$row['Email']}</td>
        <td>{$row['AddressLine']}</td>
        <td>{$row['City']}</td>
        <td>{$row['State']}</td>
        <td>{$row['Pincode']}</td>
        <td>{$row['LoanType']}</td>
        <td>{$row['LoanAmount']}</td>
        <td>{$row['TenureMonths']}</td>
        <td>{$row['EmploymentType']}</td>
        <td>{$row['CompanyName']}</td>
        <td>{$row['MonthlyIncome']}</td>
        <td>{$row['ProfileType']}</td>
        <td>{$row['AadhaarNumber']}</td>
        <td>{$row['PANNumber']}</td>
        <td>" . showFile($row['SalaryFile']) . "</td>
        <td>" . showFile($row['BankFile']) . "</td>
        <td>" . showFile($row['GSTFile']) . "</td>
        <td>" . showFile($row['UdyamFile']) . "</td>
        <td>" . showFile($row['AadhaarApplicant']) . "</td>
<td>" . showFile($row['PANApplicant']) . "</td>
<td>" . showFile($row['AadhaarCoapp']) . "</td>
<td>" . showFile($row['PANCoapp']) . "</td>
<td>" . showFile($row['ITRFile']) . "</td>
    </tr>";
}
?>

</table>

</body>
</html>