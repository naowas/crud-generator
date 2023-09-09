<?php

namespace Naowas\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Naowas\CrudGenerator\Services\CrudGeneratorService;
use function Laravel\Prompts\text;


class GenerateCrud extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:crud {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Crud operations with a single command';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
//        $columnName = text(
//            label: 'Database column name(s) should be coma separated',
//            placeholder: 'E.g: username, email, phone',
//            required: true
//        );
        $modelName = $this->argument('model');

        CrudGeneratorService::MakeController($modelName);
        CrudGeneratorService::MakeModel($modelName);
        CrudGeneratorService::MakeView($modelName);
        CrudGeneratorService::MakeRequest($modelName);
        CrudGeneratorService::MakeMigration($modelName);
        CrudGeneratorService::MakeRoute($modelName);

        $this->info('Crud for model ' . $modelName . ' created successfully');
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'model' => ['Model name?', 'E.g: User'],
        ];
    }
}
