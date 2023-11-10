<?php
// Function to convert CSV to SQL insert statements
function csvToSql($csvFilePath, $tableName) {
    $csvFile = fopen($csvFilePath, 'r');

    if ($csvFile !== false) {
        // Get the column names from the first row
        $columns = fgetcsv($csvFile);
        $columnNames = implode(', ', $columns);

        // Loop through the remaining rows
        while (($data = fgetcsv($csvFile)) !== false) {
            // Escape and quote values
            $escapedValues = array_map(function($value) {
                return "'" . addslashes($value) . "'";
            }, $data);

            // Combine values into a comma-separated string
            $values = implode(', ', $escapedValues);

            // Generate SQL insert statement
            $sql = "INSERT INTO $tableName ($columnNames) VALUES ($values);";

            // Output the SQL statement
            echo $sql . PHP_EOL;
        }

        fclose($csvFile);
    } else {
        echo "Error opening CSV file.";
    }
}

// Example usage
$csvFilePath = '/var/www/csv/some.csv';
$tableName = 'some_table';

csvToSql($csvFilePath, $tableName);
?>