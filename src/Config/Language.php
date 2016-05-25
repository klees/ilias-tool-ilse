<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * Config for the languages to be used in ILIAS.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string defaultLang()
 * @method array toInstallLangs()
 */
class Language extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "default_lang"			=> array("string", false)
			, "to_install_langs"		=> array(array("string"), false)
			);
	}

	protected static $valid_languages = array(
		"de"
		,"en");

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "to_install_langs":
			case "default_lang":
				return $this->checkContentValueInArray($value, self::$valid_languages);
			default:
				return parent::checkValueContent($key, $value);
		}
	}
}
