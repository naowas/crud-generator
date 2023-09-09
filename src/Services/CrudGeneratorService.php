<?php

namespace Naowas\CrudGenerator\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CrudGeneratorService
{
    protected static function GetStubs($type): bool|string
    {
        return file_get_contents(resource_path("crud-generator/stubs/$type.stub"));
    }
    public static function MakeController($modelName): void
    {
        $template = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $modelName,
                strtolower( Str::plural($modelName)),
                strtolower($modelName)
            ],

            self::GetStubs('controller')
        );


        file_put_contents(app_path("/Http/Controllers/{$modelName}Controller.php"), $template);

    }

    public static function MakeModel($modelName): void
    {
        $template = str_replace(
            ['{{modelName}}'],
            [$modelName],
            self::GetStubs('model')
        );

        file_put_contents(app_path("Models/{$modelName}.php"), $template);
    }

    public static function MakeRequest($modelName): void
    {
        $template = str_replace(
            ['{{modelName}}'],
            [$modelName],
            self::GetStubs('request')
        );

        if (!file_exists($path = app_path('/Http/Requests/')) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        file_put_contents(app_path("/Http/Requests/{$modelName}Request.php"), $template);
    }


    public static function MakeMigration($modelName,$columnName = null): void
    {
        $migrationTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNameSnakeCasePlural}}',
            ],
            [
                $modelName,
                Str::plural($modelName),
                Str::snake( Str::plural($modelName)),
            ],
            self::getStubs('Migration')
        );

        $tableName = Str::snake(Str::plural($modelName));

        $migrationFileName =   date('Y_m_d_His').'_create_'.$tableName.'_table.php';

        file_put_contents(database_path("/migrations/".$migrationFileName), $migrationTemplate);

    }

    public static function MakeRoute($modelName): void
    {
        $controllerPath = '\App\Http\Controllers\\' . $modelName . 'Controller::class';
        $pluralName = Str::plural(strtolower($modelName));
        $appendRoute = "Route::resource('$pluralName', $controllerPath);".PHP_EOL;
        $pathToRoutes = base_path('routes/web.php');

        File::append($pathToRoutes, $appendRoute);
    }

    public static function MakeView($modelName): void
    {
        self::createIndex($modelName);
        self::createView($modelName);
        self::createEditView($modelName);
        self::createShowView($modelName);
    }



    public static function createIndex($model): void
    {

        $tableHeaders = '';
        $tableValues = '';
        $indexViewTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{tableHeaders}}',
                '{{tableValues}}'
            ],
            [
                $model,
                Str::plural($model),
                strtolower(Str::plural($model)),
                strtolower($model),
                $tableHeaders,
                $tableValues
            ],
            self::getStubs('template/index')
        );

        self::generateViewFile($model, $indexViewTemplate, 'index');

    }

    public static function createEditView($model): void
    {

        $editViewTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $model,
                Str::plural($model),
                strtolower(Str::plural($model)),
                strtolower($model),

            ],
            self::getStubs('template/edit')
        );

        self::generateViewFile($model, $editViewTemplate, 'edit');
    }


    public static function createView($model): void
    {

        $createViewTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $model,
                Str::plural($model),
                strtolower(Str::plural($model)),
                strtolower($model),

            ],
            self::getStubs('template/create')
        );

        self::generateViewFile($model, $createViewTemplate, 'create');
    }

    public static function createShowView($model): void
    {
        $showViewTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $model,
                Str::plural($model),
                strtolower(Str::plural($model)),
                strtolower($model),
            ],
            self::getStubs('template/view')
        );

        self::generateViewFile($model, $showViewTemplate, 'view');
    }


    public static function generateViewFile($model, $viewTemplate, $viewType): void
    {

        if(!is_dir(resource_path("/views/" . strtolower(Str::plural($model)) . "/")) && !mkdir($concurrentDirectory = resource_path("/views/" . strtolower(Str::plural($model)) . "/")) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        file_put_contents(resource_path("/views/".strtolower(Str::plural($model))."/".$viewType.".blade.php"), $viewTemplate);
    }


}
