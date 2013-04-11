<?php

namespace Majkl578\NetteSymfonyValidator\Caching;

use Nette\Caching\Cache;
use Nette\Object;
use Symfony\Component\Validator\Mapping\Cache\CacheInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @author Michael Moravec
 */
class NetteCacheBridge extends Object implements CacheInterface
{
	/** @var Cache */
	private $cache;

	public function __construct(Cache $cache)
	{
		$this->cache = $cache;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has($class)
	{
		return isset($this->cache[$class]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function read($class)
	{
		return $this->has($class) ? $this->cache[$class] : FALSE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function write(ClassMetadata $metadata)
	{
		$this->cache[$metadata->getClassName()] = $metadata;
	}
}
