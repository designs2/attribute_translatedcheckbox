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

$GLOBALS['METAMODELS']['attributes']['translatedcheckbox']['class'] = 'MetaModelAttributeTranslatedCheckbox';
$GLOBALS['METAMODELS']['attributes']['translatedcheckbox']['image'] = 'system/modules/metamodelsattribute_translatedcheckbox/html/checkbox.png';

$GLOBALS['METAMODELS']['filters']['translatedcheckbox_published']['class'] = 'MetaModelFilterSettingPublishedTranslatedCheckbox';
$GLOBALS['METAMODELS']['filters']['translatedcheckbox_published']['image'] = 'system/modules/metamodels/html/visible.png';
$GLOBALS['METAMODELS']['filters']['translatedcheckbox_published']['info_callback'] = array('MetaModelAttributeTranslatedCheckboxBackendHelper', 'drawPublishedSetting');
$GLOBALS['METAMODELS']['filters']['translatedcheckbox_published']['attr_filter'][] = 'translatedcheckbox';
