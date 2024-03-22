<?php

/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

include_once './Services/EventHandling/classes/class.ilEventHookPlugin.php';

/**
 * viplab event plugin base class
 * @author Stefan Meyer <smeyer.ilias@gmx.de>
 */
class ilViPLabEventPlugin extends ilEventHookPlugin
{
    private static $instance = null;
    const PLUGIN_NAME = 'ViPLabEvent';
    const PLUGIN_ID = 'viplabevent';


    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }

        global $DIC;
        $component_factory = $DIC["component.factory"];
        return self::$instance = $component_factory->getPlugin(self::PLUGIN_ID);
    }

    public function handleEvent($a_component, $a_event, $a_parameter): void
    {
        ilLoggerFactory::getLogger('viplabevent')->debug('New event: '. $a_event.' from component: ' .$a_component);

        $assViPLabPlugin = ilassViPLabPlugin::getInstance();

        if ($assViPLabPlugin->isActive()) {
            $assViPLabPlugin->handleEcsEvent($a_event, $a_parameter);
        }
    }

    public function getPluginName(): string
    {
        return self::PLUGIN_NAME;
    }

    protected function init(): void
    {
        $this->initAutoLoad();
    }

    protected function initAutoLoad()
    {
        spl_autoload_register(array($this, 'autoLoad'));
    }

    /**
     * Auto load implementation
     *
     * @param string class name
     */
    private function autoLoad($a_classname)
    {
        $class_file = $this->getClassesDirectory() . '/class.' . $a_classname . '.php';
        if (@include_once($class_file)) {
            return;
        }
    }

    protected function getClassesDirectory() : string
    {
        return $this->getDirectory() . "/classes";
    }
}