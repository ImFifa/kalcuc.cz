<?php declare(strict_types = 1);

namespace App\Model;

use Owly\Core\Model\AdminRole\AdminRoleRepository;
use Owly\Core\Model\AdminUser\AdminUserRepository;
use Owly\Core\Model\AdminUserGrid\AdminUserGridRepository;
use Owly\Core\Model\ConfigItem\ConfigItemRepository;
use Owly\Core\Model\Log\LogRepository;
use Owly\Core\Model\Model as CoreModel;
use Owly\File\Model\File\FileRepository;
use Owly\File\Model\Folder\FolderRepository;

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
