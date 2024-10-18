<?php

namespace App\Helpers;

use App\Models\AllAddressDetail;
use App\Models\Role;

class CommonHelper
{
    public static function getUserRoleName($id){
        if($id == null){
            return "";
        }
        $roleData = Role::find($id);
        return $roleData->name;
    }
    public static function getImageUrl($image)
    {
        $imageUrl = asset('assets/img/user/user.jpg');
        if ($image !== null) {
            $imageUrl = asset('storage/' . $image);
        }
        return $imageUrl;
    }

    public static function getInitials($string){
        if($string == null){
            return "";
        }
        $words = explode(" ", $string);
        $initials = "";

        foreach ($words as $word) {
            $initials .= strtoupper($word[0]);
        }

        return $initials;
    }
    public static function importLargeData()
    {
        // File path to the data source (e.g., CSV, Excel)
        $filePath = asset('assets/excel/Locality_village_pincode_final_mar-2017 (3).csv');

        // Open the file and read data in chunks
        $handle = fopen($filePath, 'r');

        if ($handle !== false) {
            // Skip the header row if it exists
            fgetcsv($handle);

            // Define the batch size for inserts
            $batchSize = 1000;
            $dataBatch = [];

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Map data to your table's columns
                $dataBatch[] = [
                    'village_location' => $data[0], // Replace with actual column mappings
                    'office_name' => $data[1],
                    'pin_code' => $data[2],
                    'sub_district_name' => $data[3],
                    'district_name' => $data[4],
                    'state_name' => $data[5],
                    // Add more columns as needed
                ];

                // When batch size is reached, insert and reset the batch
                if (count($dataBatch) === $batchSize) {
                    $this->insertBatch($dataBatch);
                    $dataBatch = []; // Reset batch array
                }
            }

            // Insert any remaining rows that were not part of a full batch
            if (count($dataBatch) > 0) {
                $this->insertBatch($dataBatch);
            }

            fclose($handle);

            return response()->json(['message' => 'Data imported successfully.']);
        }

        return response()->json(['message' => 'Failed to open the file.'], 500);
    }
    private function insertBatch(array $dataBatch)
    {
        AllAddressDetail::insert($dataBatch);
    }
}
