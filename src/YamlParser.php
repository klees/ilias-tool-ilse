<?php
namespace CaT\InstILIAS;
use Symfony\Component\Yaml\Yaml;

class YamlParser implements \CaT\InstILIAS\interfaces\Parser {
	public function read_config($string, $class) {
		if(!class_exists($class, true)){
			throw new \LogicException("Class '$class' does not exists");
		}

		$yaml = Yaml::parse($string);

		return $this->createConfig($yaml, $class);
	}

	protected function createConfig($yaml, $class) {
		$vals = array();
		foreach ($class::fields() as $key => $type) {
			$type_val = $type[0];
			$optional = $type[1];

			if($this->isSublass($type_val, "\\CaT\\InstILIAS\\Config\\Base")) {
				$vals[] = $this->createConfig($this->yamlValue($yaml,$key,$optional), $type_val);
			}
			else if ($type_val == "string") {
				$vals[] = $this->yamlValue($yaml,$key,$optional, "");
			}
			else if ($type_val == "int") {
				$vals[] = $this->yamlValue($yaml,$key,$optional,0);
			}
			else if(is_array($type_val)) {
				assert('count($type_val) === 1');
				$content = $type_val[0];
				
				if($this->isSublass($content, "\\CaT\\InstILIAS\\Config\\Base")) {
					$sub_vals = array();
					foreach ($this->yamlValue($yaml,$key,$optional,array()) as $key => $value) {
						$sub_vals[] = $this->createConfig($value, $content);
					}
					$vals[] = $sub_vals;
				} else {
					$vals[] = $this->yamlValue($yaml,$key,$optional,array());
				}
			}
			else {
				throw new \LogicException("Unknown Type: ".$type_val);
			}
		}

		$class_handle = new \ReflectionClass($class);
		return $class_handle->newInstanceArgs($vals);
	}

	protected function yamlValue($yaml, $key, $optional, $baseValue = null) {
		if(!array_key_exists($key, $yaml) && $optional) {
			return $baseValue;
		} else if(!array_key_exists($key, $yaml) && !$optional) {
			throw new \LogicException("Key not found: ".$key);
		}

		return $yaml[$key];
	}

	protected function isSublass($type, $class) {
		$reflection = new \ReflectionClass($type);

		return $reflection->isSubclassOf($class);
	}
}