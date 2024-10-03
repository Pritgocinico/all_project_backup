<?php

namespace App\Imports;

use App\Models\Village;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LargeDataImport implements ToCollection, WithChunkReading, WithHeadingRow
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // Insert rows in chunks to avoid performance issues
        $batchSize = 1000; // Adjust batch size according to your server capacity
        foreach ($rows->chunk($batchSize) as $chunk) {
            $data = [];
            foreach ($chunk as $row) {
                $data[] = [
                    'village' => $row['village'], // Map your columns here
                    'office_name' => $row['office_name'],
                    'pincode' => $row['pincode'],
                    'sub_distname' => $row['sub_distname'],
                    'district_name' => $row['district_name'],
                    'state_name' => $row['state_name'],
                    // Add more columns as per your database schema
                ];
            }

            // Insert data into the database
            Village::insert($data);
        }
    }

    /**
     * Specify the chunk size for reading.
     */
    public function chunkSize(): int
    {
        return 500; // Reads 1000 rows at a time
    }
}
