<?php

namespace App\Console\Commands;

use App\Models\AlertType;
use App\Models\CarCategory;
use App\Models\CarFuelType;
use App\Models\CarRegistrationType;
use App\Models\CarSubCategory;
use App\Models\DocumentType;
use App\Models\RecurrentType;
use Illuminate\Console\Command;

class ImportStaticDataCommand extends Command
{
    protected $carCategories = [
        "Autoturism/Automobil mixt", "Autorulota", "Autovehicul transport persoane",
        "Autovehicul transport marfa", "Autotractor", "Tractor rutier",
        "Motocicleta/Moped/Atv"
    ];

    protected $carSubCategories = [
        "Automobil mixt", "Autoturism", "Autoturism de teren", "SUV"
    ];

    protected $carRegTypes = [
        "Inmatriculat", "Inregistrat", "In vederea inmatricularii",
        "In vederea inregistrari"
    ];

    protected $carFuelTypes = [
        "Benzina", "Motorina","Electric","Benzina si GPL","Benzina si alcool",
        "Hybrid benzina", "Hybrid motorina", "Fara", "Altul"
    ];

    protected $alertTypes = [
        "ITP", "RCA","RO-Vinieta","CASCO", "Revizie", "Plata impozit", "Verificare ulei",
        "Extinctor", "Trusa medicala",
    ];

    protected $recurrentTypes = [
        'NO' => "no",
        '1M' => "1 luna",
        '3M' => "3 luni",
        '6M' => "6 luni",
        '1Y' => "1 an",
        '2Y' => "2 ani",
        '3Y' => "3 ani",
    ];

    protected $documentTypes = [
        "Talon", "RCA", "CASCO", "RO-Vinieta", "Factura", "Contract de cumparare", "Asigurare",
        "Carte de identitate a masinii"
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:static-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports and save to the db static datas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('### START ###');

        $this->importCarCategories();
        $this->importCarSubCategories();
        $this->importCarRegTypes();
        $this->importCarfuelTypes();
        $this->importAlertTypes();
        $this->importRecurrentTypes();
        $this->importDocumentTypes();

        $this->info('### END ###');
        return 0;
    }

    protected function importCarCategories()
    {
        $this->info('  Import car categories - START');
        $this->carCategories = array_sort($this->carCategories);
        $categories = [];
        foreach ($this->carCategories as $name) {
            $categories[] = ['name' => $name];
        }

        $insertedElements = CarCategory::upsert($categories, ['name'], ['name']);

        $this->info("  Import car categories - START - QTY: $insertedElements");
    }

    protected function importCarSubCategories()
    {
        $this->info('  Import car subcategories - START');
        $this->carSubCategories = array_sort($this->carSubCategories);
        $parentCategory = CarCategory::query()->where('name', '=','Autoturism/Automobil mixt')->get()->first();

        $categories = [];
        $insertedElements = 0;
        if($parentCategory) {
            foreach ($this->carSubCategories as $name) {
                $categories[] = ['name' => $name, 'parent_id' => $parentCategory->id];
            }

            $insertedElements = CarSubCategory::upsert($categories, ['name', 'parent_id'], ['name']);
        }

        $this->info("  Import car subcategories - START - QTY: $insertedElements");
    }

    protected function importCarRegTypes()
    {
        $this->info('  Import car reg types - START');
        $this->carRegTypes = array_sort($this->carRegTypes);
        $insertData = [];
        foreach ($this->carRegTypes as $name) {
            $insertData[] = ['name' => $name];
        }

        $insertedElements = CarRegistrationType::upsert($insertData, ['name'], ['name']);

        $this->info("  Import car reg types - START - QTY: $insertedElements");
    }

    protected function importCarfuelTypes()
    {
        $this->info('  Import car fuel types - START');
        $this->carFuelTypes = array_sort($this->carFuelTypes);
        $insertData = [];
        foreach ($this->carFuelTypes as $name) {
            $insertData[] = ['name' => $name];
        }

        $insertedElements = CarFuelType::upsert($insertData, ['name'], ['name']);

        $this->info("  Import car fuel types - START - QTY: $insertedElements");
    }

    protected function importAlertTypes()
    {
        $this->info('  Import alert types - START');
        $this->alertTypes = array_sort($this->alertTypes);
        $insertData = [];
        foreach ($this->alertTypes as $name) {
            $insertData[] = ['name' => $name];
        }

        $insertedElements = AlertType::upsert($insertData, ['name'], ['name']);

        $this->info("  Import alert types - START - QTY: $insertedElements");
    }

    protected function importRecurrentTypes()
    {
        $this->info('  Import recurrent types - START');
        $this->recurrentTypes = array_sort($this->recurrentTypes);
        $insertData = [];
        foreach ($this->recurrentTypes as $key => $label) {
            $insertData[] = ['key' => $key, 'label' => $label];
        }

        $insertedElements = RecurrentType::upsert($insertData, ['key', 'label'], ['key', 'label']);

        $this->info("  Import recurrent types - START - QTY: $insertedElements");
    }

    protected function importDocumentTypes()
    {
        $this->info('  Import document types - START');
        $this->documentTypes = array_sort($this->documentTypes);
        $insertData = [];
        foreach ($this->documentTypes as $name) {
            $insertData[] = ['name' => $name];
        }

        $insertedElements = DocumentType::upsert($insertData, ['name'], ['name']);

        $this->info("  Import document types - START - QTY: $insertedElements");
    }
}
