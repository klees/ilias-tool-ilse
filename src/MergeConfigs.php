<?php

namespace CaT\Ilse;
use Symfony\Component\Yaml\Yaml;
use CaT\Ilse\Interfaces\Merger;

/**
 * Merge any number of config files
 */
class MergeConfigs implements Merger{

	public function __construct() {
		$this->counter = 0;
	}

	/**
	 * @inheritdoc
	 */
	public function mergeConfigs(array $configs)
	{
		$ret = Yaml::parse($configs[0]);

		if(count($configs) > 0) {
			for($i = 1; $i < count($configs); $i++) {
				$new = Yaml::parse($configs[$i]);
				$ret = $this->addMissingKeys($ret, $new);

				foreach ($ret as $key => $value) {
					if(array_key_exists($key, $new)) {
						$ret[$key] = $this->merge($value, $new[$key]);
					}
				}
			}
		}

		return Yaml::dump($ret);
	}

	protected function merge($base, $new)
	{
		if(is_array($base)) {
			$base = $this->addMissingKeys($base, $new);

			foreach ($base as $key => $value) {
				if(array_key_exists($key, $new)) {
					$base[$key] = $this->merge($value, $new[$key]);
				}
			}
		} else {
			if($base != $new) {
				$base = $new;
			}
		}

		return $base;
	}

	protected function addMissingKeys($base, $new)
	{
		$key_base = array_keys($base);
		$key_new = array_keys($new);
		$diff = array_diff($key_new, $key_base);

		if(count($diff) > 0) {
			foreach ($diff as $value) {
				$base[$value] = $new[$value];
			}
		}

		return $base;
	}
}