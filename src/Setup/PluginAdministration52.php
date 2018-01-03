<?php
namespace CaT\Ilse\Setup;

use CaT\Ilse\Config;
use CaT\Ilse\Setup\InitILIAS;
use CaT\Ilse\Aux\ILIAS\PluginInfo;
use CaT\Ilse\Action\Plugin;

/**
 * Delegates calls to plugins in ilias
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 * @copyright Extended GPL, see LICENSE
 */
class PluginAdministration52 implements PluginAdministration
{
	use Plugin;

	const PLUGIN_CLASS_PREFIX_IL 	= "il";
	const PLUGIN_CLASS_PREFIX_CLASS = "class.";
	const PLUGIN_CLASS_SUFFIX 		= "Plugin";
	const CLASSES_FOLDER 			= "classes";

	/**
	 * @var Config\General
	 */
	protected $config;

	/**
	 * Constructor of the class PluginAdministration52
	 */
	public function __construct(Config\General $config)
	{
		$this->config = $config;
		$this->initILIASIsNotInitialized();
	}

	/**
	 * @inheritdoc
	 */
	public function install(PluginInfo $pi)
	{
		\ilPlugin::createPluginRecord(
			$pi->getComponentType(),
			$pi->getComponentName(),
			$pi->getSlotId(),
			$pi->getPluginName()
		);
	}

	/**
	 * @inheritdoc
	 */
	public function update(PluginInfo $pi)
	{
		$plugin = $this->getPluginObject($pi);
		$plugin->update();
	}

	/**
	 * @inheritdoc
	 */
	public function activate(PluginInfo $pi)
	{
		$plugin = $this->getPluginObject($pi);
		$plugin->activate();
	}

	/**
	 * @inheritdoc
	 */
	public function updateLanguage(PluginInfo $pi)
	{
		$plugin = $this->getPluginObject($pi);
		$plugin->updateLanguages();
	}

	/**
	 * @inheritdoc
	 */
	public function uninstall(PluginInfo $pi)
	{
		// necessary for plugins that not installed via ilse
		try{
			$plugin = $this->getPluginObject($pi);
		} catch (\Exception $e) {
			$this->install($name);
			$plugin = $this->getPluginObject($pi);
		}

		$plugin->deactivate();
		$plugin->uninstall();
	}

	/**
	 * @inheritdoc
	 */
	public function needsUpdate(PluginInfo $pi)
	{
		return $this->getPluginObject($pi)->needsUpdate();
	}

	/**
	 * @inheritdoc
	 */
	public function getPluginObject(PluginInfo $pi, $call_construct = true)
	{
		$full_class_name = self::PLUGIN_CLASS_PREFIX_IL.$pi->getPluginName().self::PLUGIN_CLASS_SUFFIX;

		$cur = getcwd();
		chdir($this->config->server()->absolutePath());
		if(!class_exists($full_class_name)) {
			$link = $this->createPluginMetaData($pi);
			require_once($link["path"]."/".$link["name"]."/".self::CLASSES_FOLDER."/".self::PLUGIN_CLASS_PREFIX_CLASS.$full_class_name.".php");
		}
		$class = new \ReflectionClass(self::PLUGIN_CLASS_PREFIX_IL.$pi->getPluginName().self::PLUGIN_CLASS_SUFFIX);

		if($call_construct) {
			return $class->newInstance();
		} else {
			return $class->newInstanceWithoutConstructor();
		}
		chdir($cur);
	}

	/**
	 * @inheritdoc
	 */
	public function initILIASIsNotInitialized()
	{
		if(isset($GLOBALS['DIC'])){
			return;
		}

		$cur = getcwd();
		chdir($this->config->server()->absolutePath());

		require_once("./Services/Context/classes/class.ilContext.php");
		require_once("./Services/Init/classes/class.ilInitialisation.php");
		define("CLIENT_ID", $this->config->client()->name());

		\ilContext::init(\ilContext::CONTEXT_UNITTEST);
		\ilInitialisation::initILIAS();
		chdir($cur);
	}
}
