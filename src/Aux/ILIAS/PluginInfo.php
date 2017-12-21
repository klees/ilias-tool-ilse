<?php
namespace CaT\Ilse\Aux\ILIAS;
/**
 * Class PluginInfo
 * Holds the data needed for installing an ILIAS plugin.
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 * @copyright Extended GPL, see LICENSE
 */
class PluginInfo implements PluginInfoReader
{
	/**
	 * @var string
	 */
	protected $component_type;

	/**
	 * @var string
	 */
	protected $component_name;

	/**
	 * @var string
	 */
	protected $slot;

	/**
	 * @var string
	 */
	protected $plugin_name;

	/**
	 * Constructor of the class PluginInfo.
	 *
	 * @param 	string 	$component_type
	 * @param 	string 	$component_name
	 * @param 	string 	$slot
	 * @param 	string 	$plugin_name
	 * @return 	void
	 */
	public function __construct(
		$component_type,
		$component_name,
		$slot,
		$plugin_name
	) {
		assert('is_string($commponent_type)');
		assert('is_string($commponent_name)');
		assert('is_string($slot)');
		assert('is_string($plugin_name)');

		$this->component_type = $component_type;
		$this->component_name = $component_name;
		$this->slot = $slot;
		$this->plugin_name = $plugin_name;
	}

	/**
	 * Get commponent_type
	 *
	 * @return string
	 */
	public function getComponentType()
	{
		return $this->commponent_type;
	}

	/**
	 * Set commponent_type with $value
	 *
	 * @param 	string		$value
	 * @return 	self
	 */
	public function withComponentType($value)
	{
		assert('is_string($value)');
		$clone = clone $this;
		$clone->commponent_type = $value;
		return $clone;
	}

	/**
	 * Get component_name
	 *
	 * @return string
	 */
	public function getComponentName()
	{
		return $this->component_name;
	}

	/**
	 * Set component_name with $value
	 *
	 * @param 	string		$value
	 * @return 	self
	 */
	public function withComponentName($value)
	{
		assert('is_string($value)');
		$clone = clone $this;
		$clone->component_name = $value;
		return $clone;
	}

	/**
	 * Get slot
	 *
	 * @return string
	 */
	public function getSlot()
	{
		return $this->slot;
	}

	/**
	 * Set slot with $value
	 *
	 * @param 	string		$value
	 * @return 	self
	 */
	public function withSlot($value)
	{
		assert('is_string($value)');
		$clone = clone $this;
		$clone->slot = $value;
		return $clone;
	}

	/**
	 * Get plugin_name
	 *
	 * @return string
	 */
	public function getPluginName()
	{
		return $this->plugin_name;
	}
	
	/**
	 * Set plugin_name with $value
	 *
	 * @param 	string		$value
	 * @return 	self
	 */
	public function withPluginName($value)
	{
		assert('is_string($value)');
		$clone = clone $this;
		$clone->plugin_name = $value;
		return $clone;
	}
}