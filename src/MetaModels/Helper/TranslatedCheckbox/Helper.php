<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package     MetaModels
 * @subpackage  AttributeTranslatedCheckbox
 * @author      Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright   The MetaModels team.
 * @license     LGPL.
 * @filesource
 */

namespace MetaModels\Helper\TranslatedCheckbox;

/**
 * This class is used from checkbox attributes for button callbacks etc.
 *
 * @package    MetaModels
 * @subpackage AttributeTranslatedCheckbox
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 */
class Helper extends \Backend
{
    /**
     * Draw a published setting in the filter settings.
     *
     * @param array          $arrRow         The data information.
     *
     * @param string         $strLabel       The label string.
     *
     * @param \DataContainer $objDC          The data container.
     *
     * @param string         $imageAttribute The image attribute string.
     *
     * @param string         $strImage       The image name.
     *
     * @return string
     */
    public function drawPublishedSetting($arrRow, $strLabel, \DataContainer $objDC = null, $imageAttribute = '', $strImage)
    {
        $objMetaModel = Filter::getInstance()->getMetaModel($objDC);

        $objAttribute = $objMetaModel->getAttributeById($arrRow['attr_id']);

        if ($objAttribute) {
            $strAttrName = $objAttribute->getName();
        } else {
            $strAttrName = $arrRow['attr_id'];
        }

        if (!empty($arrRow['comment'])) {
            $arrRow['comment'] = sprintf(
                $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typedesc']['_comment_'],
                $arrRow['comment']
            );
        }

        $strReturn = sprintf(
            $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typedesc']['translatedcheckbox_published'],
            '<a href="' . $this->addToUrl('act=edit&amp;id=' . $arrRow['id']) . '">' . $strImage . '</a>',
            $strLabel ? $strLabel : $arrRow['type'],
            $arrRow['comment'],
            $strAttrName
        );
        return $strReturn;
    }
}
