<?php
// Replace this with the path to your CSV file
$csvFilePath = '/var/www/csv/some.csv';

// Data to be inserted into the first row
$newRowData = array('ip_from', 'ip_to', 'country_code', 'country_name', 'region_name', 'city_name'); // Replace with your actual column names

// Read existing CSV file
$rows = [];
if (($handle = fopen($csvFilePath, 'r')) !== false) {
    while (($data = fgetcsv($handle)) !== false) {
        $rows[] = $data;
    }
    fclose($handle);
} else {
    die("Error opening CSV file.");
}

// Insert new row at the beginning
array_unshift($rows, $newRowData);

// Write the updated content back to the CSV file
if (($handle = fopen($csvFilePath, 'w')) !== false) {
    foreach ($rows as $row) {
        fputcsv($handle, $row);
    }
    fclose($handle);
    echo "Row inserted successfully!";
} else {
    die("Error writing to CSV file.");
}
?>