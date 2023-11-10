<?php
function csvToSql($csvFilePath, $tableName, $chunkSize = 1000) {
    $csvFile = fopen($csvFilePath, 'r');

    if ($csvFile !== false) {
        // Get the column names from the first row
        $columns = fgetcsv($csvFile);
        $columnNames = implode(', ', $columns);

        // Initialize an array to store value lists for batch insertion
        $valueLists = array();

        // Loop through the remaining rows
        while (($data = fgetcsv($csvFile)) !== false) {
            // Escape and quote values
            $escapedValues = array_map(function ($value) {
                return "'" . addslashes($value) . "'";
            }, $data);

            // Combine values into a comma-separated string
            $values = implode(', ', $escapedValues);

            // Add the value list to the array
            $valueLists[] = "($values)";

            // If the batch size is reached, insert the values and reset the array
            if (count($valueLists) >= $chunkSize) {
                $values_for_insert = implode(",\n    ", $valueLists);
                $sql = "INSERT INTO $tableName ($columnNames)\nVALUES\n    $values_for_insert;";

                $valueLists = array();
                echo $sql . PHP_EOL;
            }
        }

        // Insert any remaining values
        if (!empty($valueLists)) {
            $values_for_insert = implode(",\n    ", $valueLists);
            $sql = "INSERT INTO $tableName ($columnNames)\nVALUES\n    $values_for_insert;";
            
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