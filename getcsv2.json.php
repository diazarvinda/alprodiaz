<?php

function csvToJson($csvUrl) {
    $context = stream_context_create([
        'http' => [
            'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36',
        ],
    ]);


    $csvData = @file_get_contents($csvUrl, false, $context);
    if ($csvData === false) {
        return ['error' => 'Failed to fetch CSV data'];
    }

    $csvData = array_map("str_getcsv", explode("\n", $csvData));
    $headers = array_shift($csvData);

    $jsonArray = [];

    foreach ($csvData as $row) {
        $jsonArrayItem = [];
        for ($i = 0; $i < count($row); $i++) {
            $jsonArrayItem[$headers[$i]] = $row[$i];
        }
        $jsonArray[] = $jsonArrayItem;
    }

    return $jsonArray;
}

$csvUrl = 'https://testingalpro.alwaysdata.net/api/coffee.csv';
$jsonData = csvToJson($csvUrl);

header('Content-Type: application/json');

echo json_encode($jsonData);
?>