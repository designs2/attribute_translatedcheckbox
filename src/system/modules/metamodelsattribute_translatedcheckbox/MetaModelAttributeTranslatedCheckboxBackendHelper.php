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

/**
 * This class is used from checkbox attributes for button callbacks etc.
 *
 * @package    MetaModels
 * @subpackage AttributeTranslatedCheckbox
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 */
class MetaModelAttributeTranslatedCheckboxBackendHelper extends Backend
{
	/**
	 * Render a row for the list view in the backend.
	 *
	 * @param array         $arrRow   the current data row.
	 * @param string        $strLabel the label text.
	 * @param DataContainer $objDC    the DataContainer instance that called the method.
	 */
	public function toggleIcon($arrRow, $strHref, $strLabel, $strTitle, $strIcon, $strAttributes)
	{
		if (preg_match('#metamodel=([^&$]*).*attribute=([^&$]*).*lang=([^&$]*)#', $strHref, $arrMatch))
		{
			$strMetaModel = $arrMatch[1];
			$strAttribute = $arrMatch[2];
			$strLang      = $arrMatch[3];
			if(!(($objMetaModel = MetaModelFactory::byTableName($strMetaModel))
				&& ($objAttribute = $objMetaModel->getAttribute($strAttribute))))
			{
				return '';
			}

			/* @var MetaModelAttributeTranslatedCheckbox $objAttribute */
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
	}

	public function checkToggle()
	{
		if (Input::getInstance()->get('action') != 'publishtranslatedcheckboxtoggle')
		{
			return;
		}

		// TODO: check if the attribute is allowed to be edited by the current backend user or is this already done as the attribute would not be contained within the DCA otherwise?
		$strAttribute = Input::getInstance()->get('attribute');
		if(($objMetaModel = MetaModelFactory::byTableName(Input::getInstance()->get('metamodel')))
		&& ($objAttribute = $objMetaModel->getAttribute($strAttribute)))
		{
			if (!($intId = intval(Input::getInstance()->get('tid'))))
			{
				return;
			}
			$strState = Input::getInstance()->get('state') == '1' ? '1' : '';

			if (!($strLang = Input::getInstance()->get('lang')))
			{
				return;
			}

			/* @var IMetaModelItem $objItem */
			if (!($objItem = $objMetaModel->findById($intId)))
			{
				return;
			}

			$arrIds = array($objItem->get('id'));

			// determine variants.
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

			/* @var MetaModelAttributeTranslatedCheckbox $objAttribute */
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

		$objEnvironment = Environment::getInstance();

		if ($objEnvironment->isAjaxRequest)
		{
			exit;
		}

		MetaModelController::getInstance()->redirect(
			$objEnvironment->base . $objEnvironment->script . '?do=' . Input::getInstance()->get('do')
		);
	}

	public function drawPublishedSetting($arrRow, $strLabel, DataContainer $objDC = null, $imageAttribute='', $strImage)
	{
		$objMetaModel = TableMetaModelFilterSetting::getInstance()->getMetaModel($objDC);

		$objAttribute = $objMetaModel->getAttributeById($arrRow['attr_id']);

		if ($objAttribute)
		{
			$strAttrName = $objAttribute->getName();
		} else {
			$strAttrName = $arrRow['attr_id'];
		}
		
		if (!empty($arrRow['comment']))
		{
			$arrRow['comment'] = sprintf($GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typedesc']['_comment_'], $arrRow['comment']);
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
