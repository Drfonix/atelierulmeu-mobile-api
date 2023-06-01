<?php

namespace App\Imports;

use App\Models\CarModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithUpserts;

class CarModelsImport implements ToModel, WithUpserts, WithHeadingRow,WithBatchInserts,WithChunkReading,WithProgressBar
{
    use RemembersRowNumber;
    use RemembersChunkOffset;
    use Importable;

    /**
     * @param array $row
     *
     * @return CarModel
     */
    public function model(array $row): CarModel
    {
        $currentRowNumber = $this->getRowNumber();
        $chunkOffset = $this->getChunkOffset();

        return new CarModel([
            'make_id' => (int)$row['make_id'],
            'name'     => $row['name'],
        ]);
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return ['name', 'make_id'];
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
