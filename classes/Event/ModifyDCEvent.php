<?php


namespace HeimrichHannot\Submissions\Creator\Event;


use Contao\Module;

class ModifyDCEvent
{
    /**
     * @var array
     */
    private $dca;
    /**
     * @var Module
     */
    private $module;

    public function __construct(array $dca, Module $module)
    {
        $this->dca = $dca;
        $this->module = $module;
    }

    /**
     * @param array $dca
     */
    public function setDca(array $dca): void
    {
        $this->dca = $dca;
    }

    /**
     * @return array
     */
    public function getDca(): array
    {
        return $this->dca;
    }

    /**
     * @param Module $module
     */
    public function setModule(Module $module): void
    {
        $this->module = $module;
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }
}