<?php

declare(strict_types=1);
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */


/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 */

use Xmf\Request;
use XoopsModules\Publisher;
use XoopsModules\Tag\Utility;

/** Get item fields: title, content, time, link, uid, tags
 *
 * @param array $items pass-by-ref
 * @return bool true - items found | false - nothing found/created
 */
function publisher_tag_iteminfo(&$items)
{
    if (empty($items) || !is_array($items)) {
        return false;
    }

    $itemsIds = [];
    foreach (array_keys($items) as $cat_id) {
        // Some handling here to build the link upon cat_id
        // if cat_id is not used, just skip it
        foreach (array_keys($items[$cat_id]) as $itemId) {
            // In article, the item_id is "art_id"
            $itemsIds[] = (int)$itemId;
        }
    }
    $itemsIds = array_unique($itemsIds); // remove duplicate ids

    $helper = \XoopsModules\Publisher\Helper::getInstance();
    /** @var Publisher\ItemHandler $itemHandler */
    $itemHandler = $helper->getHandler('Item');
    $criteria    = new \Criteria('itemid', '(' . implode(', ', $itemsIds) . ')', 'IN');
    $items_obj   = $itemHandler->getObjects($criteria, 'itemid');

    //make sure Tag module tag_parse_tag() can be found
    if (!method_exists(Utility::class, 'tag_parse_tag')) {
        // allows this plugin to work with Tag <= v2.34
        require_once $GLOBALS['xoops']->path('modules/tag/include/functions.php');
        $parse_function = 'tag_parse_tag';
    } else {
        // this will be used for Tag >= v2.35
        $parse_function = 'XoopsModules\Tag\Utility::tag_parse_tag';
    }

    /** @var Publisher\Item $item_obj */
    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $itemId) {
            $item_obj                 = $items_obj[$itemId];
            $items[$cat_id][$itemId] = [
                'title'   => $item_obj->getVar('title'),
                'uid'     => $item_obj->getVar('uid'),
                'link'    => "item.php?itemid={$itemId}",
                'time'    => $item_obj->getVar('datesub'),
                'tags'    => $parse_function($item_obj->getVar('item_tag', 'n')), // optional
                'content' => '',
            ];
        }
    }
    unset($items_obj);

    return true;
}

/** Remove orphan tag-item links *
 * @param int $mid
 * @return bool
 * @return bool
 */
function publisher_tag_synchronization($mid)
{
    // Optional
    /** @var \XoopsModules\Publisher\ItemHandler $itemHandler */
    $itemHandler = \XoopsModules\Publisher\Helper::getInstance()->getHandler('Item');

    /** @var \XoopsModules\Tag\LinkHandler $itemHandler */
    $linkHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Link');

    //$mid = XoopsFilterInput::clean($mid, 'INT');
    $mid = Request::getInt('mid');

    /* clear tag-item links */
    $sql    = "    DELETE FROM {$linkHandler->table}"
              . '    WHERE '
              . "        tag_modid = {$mid}"
              . '        AND '
              . '        ( tag_itemid NOT IN '
              . "            ( SELECT DISTINCT {$itemHandler->keyName} "
              . "                FROM {$itemHandler->table} "
              . "                WHERE {$itemHandler->table}.status = "
              . _CO_PUBLISHER_PUBLISHED
              . '            ) '
              . '        )';
    $result = $linkHandler->db->queryF($sql);

    return $result ? true : false;
}
