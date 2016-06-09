<?php

namespace InfyOm\Generator\Commands;

use InfyOm\Generator\Commands\BaseCommand;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\Aware\AwareModelGenerator;
use InfyOm\Generator\Generators\RepositoryGenerator;
use InfyOm\Generator\Generators\Aware\AwareRequestGenerator;
use InfyOm\Generator\Generators\Aware\AwareControllerGenerator;
use InfyOm\Generator\Generators\Scaffold\RoutesGenerator;
use InfyOm\Generator\Generators\Scaffold\ViewGenerator;
use InfyOm\Generator\Generators\TestTraitGenerator;
use InfyOm\Generator\Generators\RepositoryTestGenerator;
use InfyOm\Generator\Generators\Aware\AwareAPITestGenerator;
use InfyOm\Generator\Generators\Scaffold\MenuGenerator;

/*
app/Models/Model.php
app/Repositories/ModelRepository.php
app/Requests/ModelRequest.php
app/Controllers/ModelController.php
test/traits/MakeModelTrait.php
test/repositories/ModelRepositoryTest.php
test/api/ModelApiTest.php
resources/views/model/
    create.blade.php
    edit.blade.php
    fields.blade.php
    index.blade.php
    show_fields.blade.php
    show.blade.php
    table.blade.php

Updates:
resources/views/layouts/menu.blade.php
app/Http/routes.php

*/

class AwareGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'infyom:aware';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a full CRUD API and Scaffold for given model, the Aware version';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->commandData = new CommandData($this, CommandData::$COMMAND_TYPE_SCAFFOLD_API);
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        $modelGenerator = new AwareModelGenerator($this->commandData);
        $modelGenerator->generate();

        $repositoryGenerator = new RepositoryGenerator($this->commandData);
        $repositoryGenerator->generate();

        $requestGenerator = new AwareRequestGenerator($this->commandData);
        $requestGenerator->generate();

        $controllerGenerator = new AwareControllerGenerator($this->commandData);
        $controllerGenerator->generate();

        if ($this->commandData->getAddOn('tests')) {
            $testTraitGenerator = new TestTraitGenerator($this->commandData);
            $testTraitGenerator->generate();

            $repositoryTestGenerator = new RepositoryTestGenerator($this->commandData);
            $repositoryTestGenerator->generate();

            $apiTestGenerator = new AwareAPITestGenerator($this->commandData);
            $apiTestGenerator->generate();
        }

        $viewGenerator = new ViewGenerator($this->commandData);
        $viewGenerator->generate();

        if ($this->commandData->config->getAddOn('menu.enabled')) {
            $menuGenerator = new MenuGenerator($this->commandData);
            $menuGenerator->generate();
        }

        $routeGenerator = new RoutesGenerator($this->commandData);
        $routeGenerator->generate();

        // don't run the migrations by default
        $this->performPostActions(false);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), []);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array_merge(parent::getArguments(), []);
    }
}
