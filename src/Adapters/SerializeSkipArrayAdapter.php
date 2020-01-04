<?php declare(strict_types = 1);

namespace Warengo\Serializer\Adapters;

use Doctrine\Common\Annotations\Reader;
use Warengo\Serializer\Annotations\SerializeSkip;
use Nettrine\Hydrator\Adapters\IArrayAdapter;
use Nettrine\Hydrator\Arguments\ArrayArgs;
use Nettrine\Hydrator\Metadata;
use Nettrine\Hydrator\SkipValueException;

final class SerializeSkipArrayAdapter implements IArrayAdapter {

	/** @var Reader */
	private $reader;

	public function __construct(Reader $reader) {
		$this->reader = $reader;
	}

	public function isWorkable(ArrayArgs $args): bool {
		return (bool) $this->reader->getPropertyAnnotation($args->metadata->getMetadata()->getReflectionProperty($args->field), SerializeSkip::class);
	}

	public function work(ArrayArgs $args): void {
		$args->unsetValue();
	}

}
