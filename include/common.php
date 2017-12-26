<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 *  Publisher class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Include
 * @subpackage      Functions
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */

use Xoopsmodules\publisher;

$moduleDirName = basename(dirname(__DIR__));

require_once __DIR__ . '/../class/Helper.php';
require_once __DIR__ . '/../class/Utility.php';
require_once __DIR__ . '/../class/Configurator.php';

$db = \XoopsDatabaseFactory::getDatabase();

/** @var \Xoopsmodules\publisher\Utility $utility */

$helper       = publisher\Helper::getInstance();
$utility      = new publisher\Utility();
$configurator = new publisher\Configurator();
//$utilities                = new publisher\Utilities();
//$brokenHandler            = new publisher\BrokenHandler($db);
//$categoryHandler          = new publisher\CategoryHandler($db);
//$downlimitHandler         = new publisher\DownlimitHandler($db);
//$downloadsHandler         = new publisher\DownloadsHandler($db);
//$fielddataHandler         = new publisher\FielddataHandler($db);
//$fieldHandler             = new publisher\FieldHandler($db);
//$modifiedfielddataHandler = new publisher\ModifiedfielddataHandler($db);
//$modifiedHandler          = new publisher\ModifiedHandler($db);
//$ratingHandler            = new publisher\RatingHandler($db);

if (!defined('PUBLISHER_DIRNAME')) {
    define('PUBLISHER_DIRNAME', basename(dirname(__DIR__)));
    define('PUBLISHER_URL', XOOPS_URL . '/modules/' . PUBLISHER_DIRNAME);
    define('PUBLISHER_PATH', XOOPS_ROOT_PATH . '/modules/' . PUBLISHER_DIRNAME);
    define('PUBLISHER_IMAGES_URL', PUBLISHER_URL . '/assets/images');
    define('PUBLISHER_ADMIN_URL', PUBLISHER_URL . '/admin');
    define('PUBLISHER_ADMIN_PATH', PUBLISHER_PATH . '/admin/index.php');
    define('PUBLISHER_ROOT_PATH', $GLOBALS['xoops']->path('modules/' . PUBLISHER_DIRNAME));
    define('PUBLISHER_AUTHOR_LOGOIMG', PUBLISHER_URL . '/assets/images/logo.png');
    define('PUBLISHER_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . PUBLISHER_DIRNAME); // WITHOUT Trailing slash
    define('PUBLISHER_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . PUBLISHER_DIRNAME); // WITHOUT Trailing slash
}

//require_once PUBLISHER_ROOT_PATH . '/include/functions.php';
//require_once PUBLISHER_ROOT_PATH . '/include/constants.php';
require_once PUBLISHER_ROOT_PATH . '/include/seo_functions.php';
require_once PUBLISHER_ROOT_PATH . '/class/metagen.php';
require_once PUBLISHER_ROOT_PATH . '/class/session.php';
//require_once PUBLISHER_ROOT_PATH . '/class/request.php';

// module information
$mod_copyright = "<a href='https://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . PUBLISHER_AUTHOR_LOGOIMG . "' alt='XOOPS Project'></a>";

$helper->loadLanguage('common');

xoops_load('constants', PUBLISHER_DIRNAME);

$debug = false;

//This is needed or it will not work in blocks.
global $publisherIsAdmin;

// Load only if module is installed
if (is_object($helper->getModule())) {
    // Find if the user is admin of the module
    $publisherIsAdmin = publisher\Utility::userIsAdmin();
    // get current page
    $publisherCurrentPage = publisher\Utility::getCurrentPage();
}

$pathIcon16    = Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon16 = $helper->getModule()->getInfo('modicons16');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$icons = [
    'edit'    => "<img src='" . $pathIcon16 . "/edit.png'  alt=" . _EDIT . "' align='middle'>",
    'delete'  => "<img src='" . $pathIcon16 . "/delete.png' alt='" . _DELETE . "' align='middle'>",
    'clone'   => "<img src='" . $pathIcon16 . "/editcopy.png' alt='" . _CLONE . "' align='middle'>",
    'preview' => "<img src='" . $pathIcon16 . "/view.png' alt='" . _PREVIEW . "' align='middle'>",
    'print'   => "<img src='" . $pathIcon16 . "/printer.png' alt='" . _CLONE . "' align='middle'>",
    'pdf'     => "<img src='" . $pathIcon16 . "/pdf.png' alt='" . _CLONE . "' align='middle'>",
    'add'     => "<img src='" . $pathIcon16 . "/add.png' alt='" . _ADD . "' align='middle'>",
    '0'       => "<img src='" . $pathIcon16 . "/0.png' alt='" . _ADD . "' align='middle'>",
    '1'       => "<img src='" . $pathIcon16 . "/1.png' alt='" . _ADD . "' align='middle'>",
];

$debug = false;

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

// Local icons path
$GLOBALS['xoopsTpl']->assign('pathModIcon16', XOOPS_URL . '/modules/' . $moduleDirName . '/' . $pathModIcon16);
$GLOBALS['xoopsTpl']->assign('pathModIcon32', $pathModIcon32);

//module handlers
