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
 * @author      Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright   The MetaModels team.
 * @license     LGPL.
 * @filesource
 */

namespace MetaModels\Attribute\TranslatedCheckbox;

use MetaModels\Attribute\TranslatedReference;

/**
 * This is the MetaModelAttribute class for handling translated checkbox fields.
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
        $arrFieldDef              = parent::getFieldDefinition($arrOverrides);
        $arrFieldDef['inputType'] = 'checkbox';

        return $arrFieldDef;
    }

    /**
     * {@inheritDoc}
     */
    public function getTranslatedDataFor($arrIds, $strLangCode)
    {
        $arrReturn = parent::getTranslatedDataFor($arrIds, $strLangCode);

        if (count($arrReturn) < count($arrIds)) {
            // Per definition:
            // - all values that are not contained are defaulting to false in the fallback language.
            // - all values in published not contained are defaulting to false.
            if ($this->isPublishedField() || ($strLangCode == $this->getMetaModel()->getFallbackLanguage())) {
                foreach (array_diff($arrIds, array_keys($arrReturn)) as $intId) {
                    $arrReturn[$intId] = $this->widgetToValue(false, $intId);
                }
            }
        }

        return $arrReturn;
    }
}
