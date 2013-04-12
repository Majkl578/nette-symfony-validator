<?php

namespace Majkl578\NetteSymfonyValidator\Listeners;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Majkl578\NetteSymfonyValidator\EntityValidationException;
use Symfony\Component\Validator\Validator;

/**
 * @author Michael Moravec
 */
class ORMValidatorListener implements EventSubscriber
{
	/** @var Validator */
	private $validator;

	public function __construct(Validator $validator)
	{
		$this->validator = $validator;
	}

	public function getSubscribedEvents()
	{
		return array(
			Events::onFlush,
		);
	}

	public function onFlush(OnFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		foreach ($uow->getScheduledEntityInsertions() as $entity) {
			$this->validateEntity($entity);
		}

		foreach ($uow->getScheduledEntityUpdates() as $entity) {
			$this->validateEntity($entity);
		}
	}

	protected function validateEntity($entity)
	{
		$violations = $this->validator->validate($entity);

		if ($violations->count() === 0) {
			return;
		}

		// taken from UnitOfWork::objToStr()
		$entityIdentifier = method_exists($entity, '__toString') ? (string) $entity : get_class($entity) . '@' . spl_object_hash($entity);

		throw new EntityValidationException('Entity ' . $entityIdentifier . ' is not valid: ' . $violations);
	}
}
