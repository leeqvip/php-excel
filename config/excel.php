<?php

use TechOne\Excel\Excel;

return [
    'time_limit' => 30,

    'memory_limit' => '128M',

    'cache' => [
        // memory|redis
        'driver' => 'memory',

        'redis' => [
            'host' => '127.0.0.1',
            'password' => null,
            'port' => 6379,
            'database' => 0,
        ],
    ],

    'exports' => [
        // Temporary path
        'temp_path' => sys_get_temp_dir(),

        // Configure e.g. delimiter, enclosure and line ending for CSV exports.
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => PHP_EOL,
            'use_bom' => true,
            'include_separator_line' => false,
            'excel_compatibility' => false,
        ],
    ],
    'imports' => [
        'read_only' => true,
        'heading_row' => [
            // Configure the heading row formatter.
            // Available options: none|slug|custom
            'formatter' => 'slug',
        ],
    ],
    // Extension detector
    'extension_detector' => [
        'xlsx' => Excel::XLSX,
        'xlsm' => Excel::XLSX,
        'xltx' => Excel::XLSX,
        'xltm' => Excel::XLSX,
        'xls' => Excel::XLS,
        'xlt' => Excel::XLS,
        'ods' => Excel::ODS,
        'ots' => Excel::ODS,
        'slk' => Excel::SLK,
        'xml' => Excel::XML,
        'gnumeric' => Excel::GNUMERIC,
        'htm' => Excel::HTML,
        'html' => Excel::HTML,
        'csv' => Excel::CSV,
        'tsv' => Excel::TSV,
       // PDF Extension
        'pdf' => Excel::DOMPDF,
    ],
];
