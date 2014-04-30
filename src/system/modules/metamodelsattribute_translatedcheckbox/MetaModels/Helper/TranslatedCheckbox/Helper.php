<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package     MetaModels
 * @subpackage  AttributeTranslatedCheckbox
 * @author      Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright   The MetaModels team.
 * @license     LGPL.
 * @filesource
 */

namespace MetaModels\Helper\TranslatedCheckbox;

use MetaModels\Attribute\TranslatedCheckbox\TranslatedCheckbox;
use MetaModels\Dca\Filter;
use MetaModels\Factory;
use MetaModels\Helper\ContaoController;
use MetaModels\IItem;

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
	 * Render a row for the list view in the backend.
	 *
	 * @param array  $arrRow        The current data row.
	 *
	 * @param string $strHref       The href to use.
	 *
	 * @param string $strLabel      The label text.
	 *
	 * @param string $strTitle      The title.
	 *
	 * @param string $strIcon       The icon to use.
	 *
	 * @param string $strAttributes The attributes.
	 *
	 * @internal param \DataContainer $objDC The DataContainer instance that called the method.
	 *
	 * @return string
	 */
	public function toggleIcon($arrRow, $strHref, $strLabel, $strTitle, $strIcon, $strAttributes)
	{
		if (preg_match('#metamodel=([^&$]*).*attribute=([^&$]*).*lang=([^&$]*)#', $strHref, $arrMatch))
		{
			$strMetaModel = $arrMatch[1];
			$strAttribute = $arrMatch[2];
			$strLang      = $arrMatch[3];
			if (!(($objMetaModel = Factory::byTableName($strMetaModel))
				&& ($objAttribute = $objMetaModel->getAttribute($strAttribute))))
			{
				return '';
			}

			/** @var TranslatedCheckbox $objAttribute */
			$arrValues = $objAttribute->getTranslatedDataFor(array($arrRow['id']), $strLang);
			$arrValue  = $arrValues[$arrRow['id']];

			if ($arrValue['value'])
			{
				$strNewState = '0';
			}
			else
			{
				$strNewState = '1';
				// Makes invisible out of visible.
				$strIcon = 'in' . $strIcon;
			}

			$strImg = $this->generateImage($strIcon, $strLabel);

			return "\n\n" . sprintf('<a href="%s" title="%s"%s>%s</a> ',
				$this->addToUrl($strHref . sprintf('&amp;tid=%s&amp;state=%s', $arrRow['id'], $strNewState)),
				specialchars($strTitle),
				$strAttributes,
				$strImg?$strImg:$strLabel
			) . "\n\n";
		}

		return '';
	}

	/**
	 * Check if a toggle operation has been triggered.
	 *
	 * If so, update the model accordingly.
	 *
	 * @return void
	 */
	public function checkToggle()
	{
		if (\Input::getInstance()->get('action') != 'publishtranslatedcheckboxtoggle')
		{
			return;
		}

		// TODO: check if the attribute is allowed to be edited by the current backend user
		// Or is this already done as the attribute would not be contained within the DCA otherwise?
		$strAttribute = \Input::getInstance()->get('attribute');
		if (($objMetaModel = Factory::byTableName(\Input::getInstance()->get('metamodel')))
		&& ($objAttribute = $objMetaModel->getAttribute($strAttribute)))
		{
			if (!($intId = intval(\Input::getInstance()->get('tid'))))
			{
				return;
			}
			$strState = \Input::getInstance()->get('state') == '1' ? '1' : '';

			if (!($strLang = \Input::getInstance()->get('lang')))
			{
				return;
			}

			/** @var IItem $objItem */
			if (!($objItem = $objMetaModel->findById($intId)))
			{
				return;
			}

			$arrIds = array($objItem->get('id'));

			// Determine variants.
			if ($objMetaModel->hasVariants() && !$objAttribute->get('isvariant'))
			{
				if ($objItem->isVariantBase())
				{
					$objChildren = $objItem->getVariants(null);
					foreach ($objChildren as $objItem)
					{
						$arrIds[] = $objItem->get('id');
					}
				}
			}

			/** @var TranslatedCheckbox $objAttribute */
			$arrValues = $objAttribute->getTranslatedDataFor($arrIds, $strLang);

			foreach ($arrIds as $intId)
			{
				if (!isset($arrValues[$intId]))
				{
					$arrValues[$intId] = $objAttribute->widgetToValue(false, $intId);
				}
				$arrValues[$intId]['value'] = $strState;
			}

			$objAttribute->setTranslatedDataFor($arrValues, $strLang);
		}

		$objEnvironment = \Environment::getInstance();

		if ($objEnvironment->isAjaxRequest)
		{
			exit;
		}

		ContaoController::getInstance()->redirect(
			$objEnvironment->base . $objEnvironment->script . '?do=' . \Input::getInstance()->get('do')
		);
	}

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

		if ($objAttribute)
		{
			$strAttrName = $objAttribute->getName();
		} else {
			$strAttrName = $arrRow['attr_id'];
		}

		if (!empty($arrRow['comment']))
		{
			$arrRow['comment'] = sprintf(
				$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typedesc']['_comment_'],
				$arrRow['comment']
			);
		}

		$strReturn = sprintf(
			$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typedesc']['translatedcheckbox_published'],
			'<a href="' . $this->addToUrl('act=edit&amp;id='.$arrRow['id']). '">' . $strImage . '</a>',
			$strLabel ? $strLabel : $arrRow['type'],
			$arrRow['comment'],
			$strAttrName
		);
		return $strReturn;
	}
}
