<?php

namespace Majkl578\NetteSymfonyValidator\Config;

use Nette\Config\Compiler;
use Nette\Config\CompilerExtension;
use Nette\Config\Configurator;
use Nette\DI\Container;

/**
 * @author Michael Moravec
 */
class Extension extends CompilerExtension
{
	const NAME = 'netteSymfonyValidator';

	public $defaults = array(
		'cacheStorage' => NULL,
		'cacheNamespace' => 'netteSymfonyValidator.metadata',
		'metadataCacheDriver' => TRUE,
	);

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$nativeCache = $builder->addDefinition($this->prefix('nativeCache'))
			->setClass(
				'Nette\Caching\Cache',
				array(
					$config['cacheStorage'] ?: '@Nette\Caching\IStorage',
					$config['cacheNamespace']
				)
			)
			->setInternal(TRUE)
			->setAutowired(FALSE);

		$cache = $builder->addDefinition($this->prefix('cache'))
			->setClass('Majkl578\NetteSymfonyValidator\Caching\NetteCacheBridge', array($nativeCache))
			->setInternal(TRUE);

		$validatorBuilder = $builder->addDefinition($this->prefix('validatorBuilder'))
			->setClass('Symfony\Component\Validator\ValidatorBuilder')
			->setInternal(TRUE)
			->addSetup('enableAnnotationMapping');

		// FIXME: UGLY
		$validatorReflection = new \ReflectionClass('Symfony\Component\Validator\Validator');
		$symfonyParentPath = dirname(dirname(dirname(dirname($validatorReflection->getFileName()))));
		$validatorBuilder->addSetup(
			'Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(?, ?)',
			array('Symfony', $symfonyParentPath)
		);

		if ($config['metadataCacheDriver']) {
			$validatorBuilder->addSetup(
				'setMetadataCache',
				array($config['metadataCacheDriver'] === TRUE ? $cache : $config['metadataCacheDriver'])
			);
		}

		$builder->addDefinition($this->prefix('validator'))
			->setClass('Symfony\Component\Validator\Validator')
			->setFactory('@' . $this->prefix('validatorBuilder') . '::getValidator');
	}

	public static function register(Configurator $configurator)
	{
		$configurator->onCompile[] = function (Configurator $sender, Compiler $compiler) {
			$compiler->addExtension(Extension::NAME, new Extension());
		};
	}
}
