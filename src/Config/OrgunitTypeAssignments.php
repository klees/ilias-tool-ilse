<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * Configuration for an ILIAS Plugin.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array orgunitTypeAssignments()
 */
class OrgunitTypeAssignments extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			("orgunit_type_assignments" => array(array("\\CaT\\InstILIAS\\Config\\OrgunitTypeAssignment"), false)
			);
	}
}