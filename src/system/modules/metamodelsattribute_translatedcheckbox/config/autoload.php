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
	'MetaModels\Helper\TranslatedCheckbox\Helper'                => 'system/modules/metamodelsattribute_translatedcheckbox/MetaModels/Helper/TranslatedCheckbox/Helper.php',
	'MetaModels\Filter\Setting\Published\TranslatedCheckbox'     => 'system/modules/metamodelsattribute_translatedcheckbox/MetaModels/Filter/Setting/Published/TranslatedCheckbox.php',
	'MetaModels\Attribute\TranslatedCheckbox\TranslatedCheckbox' => 'system/modules/metamodelsattribute_translatedcheckbox/MetaModels/Attribute/TranslatedCheckbox/TranslatedCheckbox.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mm_attr_translatedcheckbox' => 'system/modules/metamodelsattribute_translatedcheckbox/templates',
));
