<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package     MetaModels
 * @subpackage  AttributeTranslatedCheckbox
 * @author      Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright   The MetaModels team.
 * @license     LGPL.
 * @filesource
 */

$GLOBALS['METAMODELS']['filters']['translatedcheckbox_published']['class'] =
    'MetaModels\Filter\Setting\Published\TranslatedCheckbox';
$GLOBALS['METAMODELS']['filters']['translatedcheckbox_published']['image'] =
    'system/modules/metamodels/html/visible.png';

$GLOBALS['METAMODELS']['filters']['translatedcheckbox_published']['attr_filter'][] = 'translatedcheckbox';

$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'MetaModels\Events\Attribute\TranslatedCheckbox\Listener';
