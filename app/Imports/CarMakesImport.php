<?php

namespace App\Imports;

use App\Models\CarMake;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithUpserts;

/**
 * Class CarMakesImport
 * @package App\Imports
 * @author Bojte Szabolcs
 */
class CarMakesImport implements ToModel, WithUpserts, WithHeadingRow,WithBatchInserts,WithChunkReading,WithProgressBar
{
    use RemembersRowNumber;
    use RemembersChunkOffset;
    use Importable;

    /**
     * @param array $row
     *
     * @return CarMake
     */
    public function model(array $row)
    {

        return new CarMake([
            'id' => $row['id'],
            'name'     => $row['name'],
        ]);
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return ['name'];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

}
