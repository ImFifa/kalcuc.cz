<?php declare(strict_types = 1);

namespace App\Model;

use K2D\Core\Model\AdminRole\AdminRoleRepository;
use K2D\Core\Model\AdminUser\AdminUserRepository;
use K2D\Core\Model\AdminUserGrid\AdminUserGridRepository;
use K2D\Core\Model\ConfigItem\ConfigItemRepository;
use K2D\Core\Model\Log\LogRepository;
use K2D\Core\Model\Model as CoreModel;
use K2D\File\Model\File\FileRepository;
use K2D\File\Model\Folder\FolderRepository;

/**
 * @property-read AdminUserRepository 		$adminUser
 * @property-read AdminRoleRepository 		$adminRole
 * @property-read AdminUserGridRepository 	$adminUserGrid
 * @property-read ConfigItemRepository		$configItem
 * @property-read LogRepository				$log
 * @property-read FileRepository			$file
 * @property-read FolderRepository			$folder
 */
class Model extends CoreModel
{

}
