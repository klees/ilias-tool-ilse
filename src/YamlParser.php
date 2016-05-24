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

	protected function createConfig($yaml, $class, $path = "") {
		$vals = array();
		foreach ($class::fields() as $key => $type) {
			$type_val = $type[0];
			$optional = $type[1];

			if(is_subclass_of($type_val, "\\CaT\\InstILIAS\\Config\\Base") ) {
				$value = $this->yamlValue($yaml, $key, $path, $optional);

				if(!$optional || ($optional && $value !== null)) {
					$new_path = ($path == "") ? $key : $path.":".$key;
					$vals[] = $this->createConfig($value, $type_val, $new_path);
				} else {
					$vals[] = null;
				}
			}
			else if ($type_val == "string") {
				$vals[] = $this->yamlValue($yaml, $key, $path, $optional, "");
			}
			else if ($type_val == "int") {
				$vals[] = $this->yamlValue($yaml, $key, $path, $optional, 0);
			}
			else if(is_array($type_val)) {
				assert('count($type_val) === 1');
				$content = $type_val[0];
				
				if(is_subclass_of($content, "\\CaT\\InstILIAS\\Config\\Base")) {
					$sub_vals = array();
					$new_path = ($path == "") ? $key : $path.":".$key;
					foreach ($this->yamlValue($yaml, $key, $new_path, $optional, array()) as $key => $value) {
						$new_path2 = ($new_path == "") ? $key : $new_path.":".$key;
						$sub_vals[] = $this->createConfig($value, $content, $new_path2);
					}
					$vals[] = $sub_vals;
				} else {
					$vals[] = $this->yamlValue($yaml, $key, $path, $optional, array());
				}
			}
			else {
				throw new \LogicException("Unknown Type: ".$type_val);
			}
		}

		$class_handle = new \ReflectionClass($class);
		return $class_handle->newInstanceArgs($vals);
	}

	protected function yamlValue($yaml, $key, $path, $optional, $baseValue = null) {
		if(!array_key_exists($key, $yaml) && $optional) {
			return $baseValue;
		} else if(!array_key_exists($key, $yaml) && !$optional) {
			echo "Required configuration entry \"".$path.":".$key."\" was not found. Please check your config.yaml";
			die(1);
		}

		return $yaml[$key];
	}
}