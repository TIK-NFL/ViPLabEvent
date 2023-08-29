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

	const PNAME = 'ViPLabEvent';
	const CTYPE = 'Services';
	const CNAME = 'EventHandling';
	const SLOT_ID = 'evhk';

	/**
	 * Get singelton instance
	 * @global ilPluginAdmin $ilPluginAdmin
	 * @return ilFhoevImportPlugin
	 */
	public static function getInstance()
	{
        if (self::$instance) {
            return self::$instance;
        }

        global $DIC;

        $component_factory = $DIC['component.factory'];
        $instance = $component_factory->getPlugin('viplabevent');

        return self::$instance = $instance;
	}
	
	/**
	 * Handle event
	 * @param type $a_component
	 * @param type $a_event
	 * @param type $a_parameter
	 */
	public function handleEvent($a_component, $a_event, $a_parameter): void
	{
		ilLoggerFactory::getLogger('viplabevent')->debug('New event: '. $a_event.' from component: ' .$a_component);
		
		if ($a_component == 'Services/WebServices/ECS' and $a_event == 'newEcsEvent') {
            $plugin = ilassViPLabPlugin::getInstance();
            $plugin->handleEcsEvent($a_event, $a_parameter);
		}
	}
	

	/**
	 * Get plugin name
	 * @return string
	 */
	public function getPluginName(): string
	{
		return self::PNAME;
	}

	/**
	 * Init auto load
	 */
	protected function init(): void
	{
		$this->initAutoLoad();
	}

	/**
	 * Init auto loader
	 * @return void
	 */
	protected function initAutoLoad()
	{
		spl_autoload_register(
				array($this, 'autoLoad')
		);
	}
	
	

	/**
	 * Auto load implementation
	 *
	 * @param string class name
	 */
	private final function autoLoad($a_classname)
	{
		$class_file = $this->getClassesDirectory() . '/class.' . $a_classname . '.php';
		if (@include_once($class_file))
		{
			return;
		}
	}

    protected function getClassesDirectory() : string
    {
        return $this->getDirectory() . "/classes";
    }

}