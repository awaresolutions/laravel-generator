<?php

namespace InfyOm\Generator\Generators\Aware;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;
use InfyOm\Generator\Utils\TemplateUtil;

class AwareRequestGenerator extends BaseGenerator
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
        $this->path = $commandData->config->pathRequest;
        $this->fileName = $this->commandData->modelName.'Request.php';
    }

    public function generate()
    {
        $this->generateRequest();
    }

    private function generateRequest()
    {
        $templateData = TemplateUtil::getTemplate('request', 'laravel-generator');

        $templateData = TemplateUtil::fillTemplate($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, $this->fileName, $templateData);

        $this->commandData->commandComment("\nRequest created: ");
        $this->commandData->commandInfo($this->fileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->commandData->commandComment('Request file deleted: '.$this->fileName);
        }
    }
}
