<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\InstILIAS\Config;

/**
 * General Configuration for an ILIAS.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method \\CaT\\InstILIAS\\Config\\Client client()
 * @method \\CaT\\InstILIAS\\Config\\DB database()
 * @method \\CaT\\InstILIAS\\Config\\Language language()
 * @method \\CaT\\InstILIAS\\Config\\Server server()
 * @method \\CaT\\InstILIAS\\Config\\Setup setup()
 * @method \\CaT\\InstILIAS\\Config\\Tools tools()
 * @method \\CaT\\InstILIAS\\Config\\Log log()
 * @method \\CaT\\InstILIAS\\Config\\GitBranch gitBranch()
 * @method \\CaT\\InstILIAS\\Config\\Categories category()
 * @method \\CaT\\InstILIAS\\Config\\OrgUnits orgunit()
 * @method \\CaT\\InstILIAS\\Config\\Roles role()
 * @method \\CaT\\InstILIAS\\Config\\LDAP ldap()
 * @method \\CaT\\InstILIAS\\Config\\Plugins plugin()
 * @method \\CaT\\InstILIAS\\Config\\HTTPSAutoDetect httpsAutoDetect()
 * @method \\CaT\\InstILIAS\\Config\\OrgunitTypes OrgunitType()
 * @method \\CaT\\InstILIAS\\Config\\OrgunitTypeAssignments OrgunitTypeAssignment()
 * @method \\CaT\\InstILIAS\\Config\\Users user()
 * @method \\CaT\\InstILIAS\\Config\\PasswordSettings passwordSettings()
 * @method \\CaT\\InstILIAS\\Config\\Editor editor()
 * @method \\CaT\\InstILIAS\\Config\\JavaServer javaServer()
 * @method \\CaT\\InstILIAS\\Config\\Certificate certificate()
 * @method \\CaT\\InstILIAS\\Config\\Soap soap()
 * @method \\CaT\\InstILIAS\\Config\\LearningProgress learningProgress()
 */
class General extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "client"	=> array("\\CaT\\InstILIAS\\Config\\Client", true)
			, "database"	=> array("\\CaT\\InstILIAS\\Config\\DB", true)
			, "language"	=> array("\\CaT\\InstILIAS\\Config\\Language", true)
			, "server"	=> array("\\CaT\\InstILIAS\\Config\\Server", true)
			, "setup"	=> array("\\CaT\\InstILIAS\\Config\\Setup", true)
			, "tools"	=> array("\\CaT\\InstILIAS\\Config\\Tools", true)
			, "log"	=> array("\\CaT\\InstILIAS\\Config\\Log", true)
			, "git_branch"	=> array("\\CaT\\InstILIAS\\Config\\GitBranch", true)
			, "category"	=> array("\\CaT\\InstILIAS\\Config\\Categories", true)
			, "orgunit"	=> array("\\CaT\\InstILIAS\\Config\\OrgUnits", true)
			, "role"	=> array("\\CaT\\InstILIAS\\Config\\Roles", true)
			, "ldap"	=> array("\\CaT\\InstILIAS\\Config\\LDAP", true)
			, "plugin"	=> array("\\CaT\\InstILIAS\\Config\\Plugins", true)
			, "https_auto_detect"	=> array("\\CaT\\InstILIAS\\Config\\HTTPSAutoDetect", true)
			, "orgunit_type" => array("\\CaT\\InstILIAS\\Config\\OrgunitTypes", true)
			, "orgunit_type_assignment" => array("\\CaT\\InstILIAS\\Config\\OrgunitTypeAssignments", true)
			, "user" => array("\\CaT\\InstILIAS\\Config\\Users", true)
			, "password_settings" => array("\\CaT\\InstILIAS\\Config\\PasswordSettings", true)
			, "editor" => array("\\CaT\\InstILIAS\\Config\\Editor", true)
			, "java_server" => array("\\CaT\\InstILIAS\\Config\\JavaServer", true)
			, "certificate" => array("\\CaT\\InstILIAS\\Config\\Certificate", true)
			, "soap" => array("\\CaT\\InstILIAS\\Config\\Soap", true)
			, "learning_progress" => array("\\CaT\\InstILIAS\\Config\\LearningProgress", true)
			);
	}
}