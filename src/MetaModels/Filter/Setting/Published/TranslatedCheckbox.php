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

namespace MetaModels\Filter\Setting\Published;

use MetaModels\Filter\Setting\Simple as MetaModelFilterSetting;
use MetaModels\Filter\Rules\SimpleQuery as MetaModelFilterRuleSimpleQuery;
use MetaModels\Filter\Rules\StaticIdList as MetaModelFilterRuleStaticIdList;
use MetaModels\Filter\IFilter as IMetaModelFilter;

/**
 * Filter setting to filter for translated checkboxes.
 *
 * @package MetaModels\Filter\Setting\Published
 */
class TranslatedCheckbox extends MetaModelFilterSetting
{
	/**
	 * {@inheritDoc}
	 */
	public function prepareRules(IMetaModelFilter $objFilter, $arrFilterUrl)
	{
		if ($this->get('check_ignorepublished') && $arrFilterUrl['ignore_published' . $this->get('id')])
		{
			return;
		}

		// Skip filter when in front end preview.
		if ($this->get('check_allowpreview') && BE_USER_LOGGED_IN)
		{
			return;
		}

		$objAttribute = $this->getMetaModel()->getAttributeById($this->get('attr_id'));
		if ($objAttribute)
		{
			$objFilterRule = new MetaModelFilterRuleSimpleQuery(sprintf(
				'SELECT item_id AS id FROM tl_metamodel_translatedcheckbox WHERE att_id=%s AND langcode=? AND value=1',
				$objAttribute->get('id')
			), array(
				$this->getMetaModel()->getActiveLanguage()
			));
			$objFilter->addFilterRule($objFilterRule);

			return;
		}
		// Attribute not found, do not return anyting to prevent leaking of items.
		$objFilter->addFilterRule(new MetaModelFilterRuleStaticIdList(array()));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParameters()
	{
		return ($this->get('check_ignorepublished')) ? array('ignore_published' . $this->get('id')) : array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParameterDCA()
	{
		if (!$this->get('check_ignorepublished'))
		{
			return array();
		}

		$objAttribute = $this->getMetaModel()->getAttributeById($this->get('attr_id'));

		$arrLabel = array();
		foreach ($GLOBALS['TL_LANG']['MSC']['metamodel_filtersetting']['ignore_published'] as $strLabel)
		{
			$arrLabel[] = sprintf($strLabel, $objAttribute->getName());
		}

		return array(
			'ignore_published' . $this->get('id') => array
			(
				'label'   => $arrLabel,
				'inputType'    => 'checkbox',
			)
		);
	}
}
