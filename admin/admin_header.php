<?php

declare(strict_types=1);
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 */

use Xmf\Module\Admin;
use XoopsModules\Publisher\Helper;

require dirname(__DIR__) . '/preloads/autoloader.php';

require_once dirname(__DIR__, 3) . '/include/cp_header.php';
//require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');

require_once dirname(__DIR__) . '/include/common.php';

$moduleDirName = basename(dirname(__DIR__));

$helper = Helper::getInstance();
/** @var Xmf\Module\Admin $adminObject */
$adminObject = Admin::getInstance();

$pathIcon16 = Admin::iconUrl('', 16);
$pathIcon32 = Admin::iconUrl('', 32);
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');

$imagearray = [
    'editimg'   => "<img src='" . PUBLISHER_IMAGES_URL . "/button_edit.png' alt='" . _AM_PUBLISHER_ICO_EDIT . "' align='middle'>",
    'deleteimg' => "<img src='" . PUBLISHER_IMAGES_URL . "/button_delete.png' alt='" . _AM_PUBLISHER_ICO_DELETE . "' align='middle'>",
    'online'    => "<img src='" . PUBLISHER_IMAGES_URL . "/on.png' alt='" . _AM_PUBLISHER_ICO_ONLINE . "' align='middle'>",
    'offline'   => "<img src='" . PUBLISHER_IMAGES_URL . "/off.png' alt='" . _AM_PUBLISHER_ICO_OFFLINE . "' align='middle'>",
];

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}
