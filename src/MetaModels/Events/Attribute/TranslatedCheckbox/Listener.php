<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package    MetaModels
 * @subpackage Frontend
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace MetaModels\Events\Attribute\TranslatedCheckbox;

use ContaoCommunityAlliance\DcGeneral\Contao\DataDefinition\Definition\Contao2BackendViewDefinition;
use ContaoCommunityAlliance\DcGeneral\Contao\DataDefinition\Definition\Contao2BackendViewDefinitionInterface;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Definition\View\CommandCollectionInterface;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Definition\View\ToggleCommand;
use MetaModels\Attribute\IAttribute;
use MetaModels\Attribute\TranslatedCheckbox\TranslatedCheckbox;
use MetaModels\Events\BuildAttributeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * This class creates the default instances for property conditions when generating input screens.
 */
class Listener
	implements EventSubscriberInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getSubscribedEvents()
	{
		return array(
			BuildAttributeEvent::NAME => __CLASS__ . '::handle'
		);
	}

	/**
	 * @param CommandCollectionInterface $commands    The already existing commands.
	 *
	 * @param IAttribute                 $attribute   The attribute.
	 *
	 * @param string                     $commandName The name of the new command.
	 *
	 * @param string                     $class       The name of the CSS class for the command.
	 *
	 * @param string                     $language    The language name.
	 */
	protected function generateToggleCommand($commands, $attribute, $commandName, $class, $language)
	{
		if (!$commands->hasCommandNamed($commandName))
		{
			$toggle = new ToggleCommand();
			$toggle->setName($commandName);
			$toggle->setLabel(sprintf($GLOBALS['TL_LANG']['MSC']['metamodelattribute_translatedcheckbox']['toggle'][0], $language));
			$toggle->setDescription(sprintf($GLOBALS['TL_LANG']['MSC']['metamodelattribute_translatedcheckbox']['toggle'][1], $language));
			$extra          = $toggle->getExtra();
			$extra['icon']  = 'visible.gif';
			$extra['class'] = $class;
			$toggle->setToggleProperty($attribute->getColName());
			$commands->addCommand($toggle);
		}
	}

	/**
	 * Create the property conditions.
	 *
	 * @param BuildAttributeEvent $event The event.
	 *
	 * @return void
	 *
	 * @throws \RuntimeException When no MetaModel is attached to the event or any other important information could
	 *                           not be retrieved.
	 */
	public function handle(BuildAttributeEvent $event)
	{
		if (!(($event->getAttribute() instanceof TranslatedCheckbox) && ($event->getAttribute()->get('check_publish') == 1)))
		{
			return;
		}

		$container = $event->getContainer();

		if ($container->hasDefinition(Contao2BackendViewDefinitionInterface::NAME))
		{
			$view = $container->getDefinition(Contao2BackendViewDefinitionInterface::NAME);
		}
		else
		{
			$view = new Contao2BackendViewDefinition();
			$container->setDefinition(Contao2BackendViewDefinitionInterface::NAME, $view);
		}

		$attribute      = $event->getAttribute();
		$activeLanguage = $attribute->getMetaModel()->getActiveLanguage();
		$commands       = $view->getModelCommands();
		$commandName    = 'publishtranslatedcheckboxtoggle_' . $attribute->getColName();

		$this->generateToggleCommand($commands, $attribute, $commandName . '_' . $activeLanguage, 'contextmenu', $activeLanguage);

		foreach (array_diff($attribute->getMetaModel()->getAvailableLanguages(), array($activeLanguage)) as $langCode)
		{
			$this->generateToggleCommand($commands, $attribute, $commandName . '_' . $langCode, 'edit-header', $langCode);
		}
	}
}

