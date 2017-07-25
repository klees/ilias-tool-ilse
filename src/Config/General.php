<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\ilse\Config;

/**
 * General Configuration for an ILIAS.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method \\CaT\\ilse\\Config\\Client client()
 * @method \\CaT\\ilse\\Config\\DB database()
 * @method \\CaT\\ilse\\Config\\Language language()
 * @method \\CaT\\ilse\\Config\\Server server()
 * @method \\CaT\\ilse\\Config\\Setup setup()
 * @method \\CaT\\ilse\\Config\\Tools tools()
 * @method \\CaT\\ilse\\Config\\Log log()
 * @method \\CaT\\ilse\\Config\\GitBranch gitBranch()
 * @method \\CaT\\ilse\\Config\\Categories category()
 * @method \\CaT\\ilse\\Config\\OrgUnits orgunit()
 * @method \\CaT\\ilse\\Config\\Roles role()
 * @method \\CaT\\ilse\\Config\\LDAP ldap()
 * @method \\CaT\\ilse\\Config\\Plugins plugin()
 * @method \\CaT\\ilse\\Config\\HTTPSAutoDetect httpsAutoDetect()
 * @method \\CaT\\ilse\\Config\\OrgunitTypes OrgunitType()
 * @method \\CaT\\ilse\\Config\\OrgunitTypeAssignments OrgunitTypeAssignment()
 * @method \\CaT\\ilse\\Config\\Users user()
 * @method \\CaT\\ilse\\Config\\PasswordSettings passwordSettings()
 * @method \\CaT\\ilse\\Config\\Editor editor()
 * @method \\CaT\\ilse\\Config\\JavaServer javaServer()
 * @method \\CaT\\ilse\\Config\\Certificate certificate()
 * @method \\CaT\\ilse\\Config\\Soap soap()
 * @method \\CaT\\ilse\\Config\\LearningProgress learningProgress()
 */
class General extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "client"					=> array("\\CaT\\ilse\\Config\\Client", true)
			, "database"				=> array("\\CaT\\ilse\\Config\\DB", true)
			, "language"				=> array("\\CaT\\ilse\\Config\\Language", true)
			, "server"					=> array("\\CaT\\ilse\\Config\\Server", true)
			, "setup"					=> array("\\CaT\\ilse\\Config\\Setup", true)
			, "tools"					=> array("\\CaT\\ilse\\Config\\Tools", true)
			, "log"						=> array("\\CaT\\ilse\\Config\\Log", true)
			, "git_branch"				=> array("\\CaT\\ilse\\Config\\GitBranch", true)
			, "category"				=> array("\\CaT\\ilse\\Config\\Categories", true)
			, "orgunit"					=> array("\\CaT\\ilse\\Config\\OrgUnits", true)
			, "role"					=> array("\\CaT\\ilse\\Config\\Roles", true)
			, "ldap"					=> array("\\CaT\\ilse\\Config\\LDAP", true)
			, "plugin"					=> array("\\CaT\\ilse\\Config\\Plugins", true)
			, "https_auto_detect"		=> array("\\CaT\\ilse\\Config\\HTTPSAutoDetect", true)
			, "orgunit_type" 			=> array("\\CaT\\ilse\\Config\\OrgunitTypes", true)
			, "orgunit_type_assignment" => array("\\CaT\\ilse\\Config\\OrgunitTypeAssignments", true)
			, "user" 					=> array("\\CaT\\ilse\\Config\\Users", true)
			, "password_settings" 		=> array("\\CaT\\ilse\\Config\\PasswordSettings", true)
			, "editor"					=> array("\\CaT\\ilse\\Config\\Editor", true)
			, "java_server" 			=> array("\\CaT\\ilse\\Config\\JavaServer", true)
			, "certificate" 			=> array("\\CaT\\ilse\\Config\\Certificate", true)
			, "soap" 					=> array("\\CaT\\ilse\\Config\\Soap", true)
			, "learning_progress" 		=> array("\\CaT\\ilse\\Config\\LearningProgress", true)
			);
	}
}