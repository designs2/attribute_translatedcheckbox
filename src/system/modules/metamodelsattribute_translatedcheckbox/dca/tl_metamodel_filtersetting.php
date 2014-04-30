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
 * Table tl_metamodel_filtersetting
 */
// @codingStandardsIgnoreStart
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['translatedcheckbox_published extends _attribute_']['+config'] =
	array
	(
		'check_ignorepublished',
		'check_allowpreview'
	);
// @codingStandardsIgnoreEnd

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['check_ignorepublished'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['check_ignorepublished'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array(
		'alwaysSave'          => true,
		'tl_class'            => 'w50 m12',
	),
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['check_allowpreview'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['check_allowpreview'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array(
		'alwaysSave'          => true,
		'tl_class'            => 'w50 m12',
	),
);
