<?php

namespace Majkl578\NetteSymfonyValidator\Config;

use Nette\Config\Compiler;
use Nette\Config\CompilerExtension;
use Nette\Config\Configurator;

/**
 * @author Michael Moravec
 */
class ORMExtension extends CompilerExtension
{
	const NAME = 'netteSymfonyValidatorORM';

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('validatorListener'))
			->setClass('Majkl578\NetteSymfonyValidator\Listeners\ORMValidatorListener')
			->addSetup('?->getEventManager()->addEventSubscriber(?)', array('@Doctrine\ORM\EntityManager', '@self'))
			->addTag('run');
	}

	public static function register(Configurator $configurator)
	{
		$configurator->onCompile[] = function (Configurator $sender, Compiler $compiler) {
			$compiler->addExtension(ORMExtension::NAME, new ORMExtension());
		};
	}
}
