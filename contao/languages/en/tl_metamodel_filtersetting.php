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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['check_ignorepublished'] = array('Allow parameter override', 'If you check this, filter parameters may override this setting.');
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['check_allowpreview']    = array('Ignore filter in preview mode', 'If you check this, this filter will not get applied when an user is in "Front end preview" and has the option "show unpublished items" active.');

/**
 * Reference
 */

$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typenames']['translatedcheckbox_published'] = 'Translated published state';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typedesc']['translatedcheckbox_published']  = '%s <strong>%s</strong> %s <br /> on attribute <em>%s</em>';
