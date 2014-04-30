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

namespace MetaModels\Attribute\TranslatedCheckbox;

use MetaModels\Attribute\TranslatedReference;
use MetaModels\Helper\ContaoController;

	/**
	 * This is the MetaModelAttribute class for handling translated checkbox fields.
	 *
	 * @package    MetaModels
	 * @subpackage AttributeTranslatedCheckbox
	 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
	 */
class TranslatedCheckbox extends TranslatedReference
{
	/**
	 * Check if the attribute is a published field.
	 *
	 * @return bool
	 */
	public function isPublishedField()
	{
		return $this->get('check_publish') == 1;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAttributeSettingNames()
	{
		return array_merge(parent::getAttributeSettingNames(), array(
			'check_publish',
			'filterable',
			'searchable',
			'sortable',
			'submitOnChange'
		));
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getValueTable()
	{
		return 'tl_metamodel_translatedcheckbox';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFieldDefinition($arrOverrides = array())
	{
		$arrFieldDef = parent::getFieldDefinition($arrOverrides);

		$arrFieldDef['inputType'] = 'checkbox';

		return $arrFieldDef;
	}

	/**
	 * Generate the operation array to be used in the dca for the given language code..
	 *
	 * @param string $strLangcode The language code to use.
	 *
	 * @param bool   $blnIsActive Flag determining if the operation shall have the active image or inactive.
	 *
	 * @return array
	 */
	protected function generateToggleAction($strLangcode, $blnIsActive)
	{
		$arrLanguages = ContaoController::getInstance()->getLanguages();
		$arrLabel     = array
		(
			0 => sprintf(
				$GLOBALS['TL_LANG']['MSC']['metamodelattribute_translatedcheckbox']['toggle'][1],
				$arrLanguages[$strLangcode]
			),
			1 => sprintf(
				$GLOBALS['TL_LANG']['MSC']['metamodelattribute_translatedcheckbox']['toggle'][1],
				$arrLanguages[$strLangcode]
			)
		);

		return array
		(
			'label'               => $arrLabel,
			'icon'                => 'visible.gif',
			'href'                => sprintf(
				'&amp;action=publishtranslatedcheckboxtoggle&amp;metamodel=%s&amp;attribute=%s&amp;lang=%s',
				$this->getMetaModel()->getTableName(),
				$this->getColName(),
				$strLangcode
			),
			'attributes'          =>
				'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleTranslatedPublishCheckbox(this, %s);"' .
				' class="' .
				($blnIsActive ? 'contextmenu' : 'edit-header') .
				'"',
			'button_callback'     => array('MetaModels\Helper\TranslatedCheckbox\Helper', 'toggleIcon'),
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getItemDCA($arrOverrides = array())
	{
		$arrDCA = parent::getItemDCA($arrOverrides);
		if ($this->isPublishedField())
		{
			$strActiveLanguage                                = $this->getMetaModel()->getActiveLanguage();
			$arrAllOperations                                 = array();
			$arrAllOperations['toggle_' . $strActiveLanguage] = $this->generateToggleAction($strActiveLanguage, true);

			foreach (array_diff($this->getMetaModel()->getAvailableLanguages(), array($strActiveLanguage)) as $strLangcode)
			{
				$arrAllOperations['toggle_' . $strLangcode] = $this->generateToggleAction($strLangcode, false);
			}

			$arrDCA = array_replace_recursive(
				$arrDCA,
				array
				(
					'config' => array
					(
						'onload_callback' => array(
							// NOTE: we need to define an explicit key here, as otherwise we will effectively kill any
							// other callback (due to usage of same key '0').
							'translatedcheckbox' => array('MetaModels\Helper\TranslatedCheckbox\Helper', 'checkToggle')
						),
					),
					'list' => array
					(
						'operations' => $arrAllOperations
					)
				)
			);

			$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/metamodelsattribute_translatedcheckbox/html/publish.js';
		}

		return $arrDCA;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTranslatedDataFor($arrIds, $strLangCode)
	{
		$arrReturn = parent::getTranslatedDataFor($arrIds, $strLangCode);

		// Per definition: all values that are not contained are defaulting to false in the fallback language.
		if (($strLangCode == $this->getMetaModel()->getFallbackLanguage()) && (count($arrReturn) < count($arrIds)))
		{
			foreach (array_diff(array_keys($arrReturn), $arrIds) as $intId)
			{
				$arrReturn[$intId] = $this->widgetToValue(false, $intId);
			}
		}

		return $arrReturn;
	}
}
