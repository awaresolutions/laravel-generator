<?php

namespace InfyOm\Generator\Generators\Aware;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;
use InfyOm\Generator\Utils\TemplateUtil;

class AwareControllerGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $fileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathApiController;
        $this->fileName = $this->commandData->modelName.'Controller.php';
    }

    public function generate()
    {
        $templateData = TemplateUtil::getTemplate('controller', 'laravel-generator');

        $templateData = TemplateUtil::fillTemplate($this->commandData->dynamicVars, $templateData);
        $templateData = $this->fillDocs($templateData);

        FileUtil::createFile($this->path, $this->fileName, $templateData);

        $this->commandData->commandComment("\nController created: ");
        $this->commandData->commandInfo($this->fileName);
    }

    // handles the API documentation blocks
    private function fillDocs($templateData)
    {
        $methods = ['controller', 'index', 'store', 'show', 'update', 'destroy'];

        if ($this->commandData->getAddOn('swagger')) {
            $templatePrefix = 'controller';
            $templateType = 'swagger-generator';
        } else {
            $templatePrefix = 'api.docs.controller';
            $templateType = 'laravel-generator';
        }

        foreach ($methods as $method) {
            $key = '$DOC_'.strtoupper($method).'$';
            $docTemplate = TemplateUtil::getTemplate($templatePrefix.'.'.$method, $templateType);
            $docTemplate = TemplateUtil::fillTemplate($this->commandData->dynamicVars, $docTemplate);
            $templateData = str_replace($key, $docTemplate, $templateData);
        }

        return $templateData;
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->commandData->commandComment('Controller file deleted: '.$this->fileName);
        }
    }
}
