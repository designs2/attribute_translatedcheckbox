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
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'MetaModelAttributeTranslatedCheckbox'                  => 'system/modules/metamodelsattribute_translatedcheckbox/MetaModelAttributeTranslatedCheckbox.php',
	'MetaModelAttributeTranslatedCheckboxBackendHelper'     => 'system/modules/metamodelsattribute_translatedcheckbox/MetaModelAttributeTranslatedCheckboxBackendHelper.php',
	'MetaModelFilterSettingPublishedTranslatedCheckbox'     => 'system/modules/metamodelsattribute_translatedcheckbox/MetaModelFilterSettingPublishedTranslatedCheckbox.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mm_attr_translatedcheckbox'              => 'system/modules/metamodelsattribute_translatedcheckbox/templates',
));
