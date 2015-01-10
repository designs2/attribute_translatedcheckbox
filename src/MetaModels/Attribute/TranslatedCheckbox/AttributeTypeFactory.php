<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package    MetaModels
 * @subpackage AttributeTranslatedCheckbox
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace MetaModels\Attribute\TranslatedCheckbox;

use MetaModels\Attribute\AbstractAttributeTypeFactory;

/**
 * Attribute type factory for checkbox attributes.
 */
class AttributeTypeFactory extends AbstractAttributeTypeFactory
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->typeName  = 'translatedcheckbox';
        $this->typeIcon  = 'system/modules/metamodelsattribute_translatedcheckbox/html/checkbox.png';
        $this->typeClass = 'MetaModels\Attribute\TranslatedCheckbox\TranslatedCheckbox';
    }
}
