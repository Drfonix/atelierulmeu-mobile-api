<?php

namespace App\Console\Commands;

use App\Imports\CarMakesImport;
use App\Imports\CarModelsImport;
use Illuminate\Console\Command;

class SetCarMakesFromJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set-static-data {--type=*} {--file-name=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the db data sets (based on type) with new set of data from json';

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
        $options = $this->options();
        $type = $options['type'][0] ?? null;
        $fileName = $options['file-name'][0] ?? "";

        if(!$type || !$fileName) {
            $this->error("No --type and/or file name provided.");
            return 0;
        }
        $filePath = storage_path()  . "/static-data/$fileName";

        if(!file_exists($filePath)) {
            $this->error("The file doesn't not exists.");
            return 0;
        }
        switch ($type) {
            case 'car-make':
                (new CarMakesImport)->withOutput($this->output)->import($filePath);
                break;
            case 'car-model':
                (new CarModelsImport)->withOutput($this->output)->import($filePath);
                break;
            default:
                return 0;
        }
        $this->info('### END ###');
        return 0;
    }
}
