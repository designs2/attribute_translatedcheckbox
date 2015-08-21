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
     * Private flag to lock filling of missing values in getTranslatedDataFor.
     *
     * @var bool
     */
    private $doNotFixValues = false;

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

        // Per definition:
        // - all values that are not contained are defaulting to false in the fallback language.
        // - all values in published not contained are defaulting to false.
        if ($this->isFixingOfValuesNeeded($arrReturn, $arrIds, $strLangCode)) {
            // We have to lock the retrieval to prevent endless recursion.
            $this->doNotFixValues = true;

            $fixedValues = array();
            foreach (array_diff($arrIds, array_keys($arrReturn)) as $itemId) {
                $arrReturn[$itemId]   = $this->widgetToValue(false, $itemId);
                $fixedValues[$itemId] = $arrReturn[$itemId];
            }

            if (count($fixedValues)) {
                $this->setTranslatedDataFor($fixedValues, $strLangCode);
            }

            // Unlock the retrieval again as we have fixed the values in the database.
            $this->doNotFixValues = false;
        }

        return $arrReturn;
    }

    /**
     * Check if the passed values need to be fixed (filled up) with missing ids.
     *
     * @param array    $values   The retrieved values.
     *
     * @param string[] $idList   The list of ids.
     *
     * @param string   $langCode The language code.
     *
     * @return bool
     */
    private function isFixingOfValuesNeeded($values, $idList, $langCode)
    {
        if ($this->doNotFixValues) {
            return false;
        }

        if (count($values) == count($idList)) {
            return false;
        }

        return ($this->isPublishedField() || ($langCode == $this->getMetaModel()->getFallbackLanguage()));
    }
}
