<?php declare(strict_types = 1);

namespace Warengo\Serializer\Adapters;

use Doctrine\Common\Annotations\Reader;
use Warengo\Serializer\Annotations\SerializeSkip;
use WebChemistry\DoctrineHydration\Adapters\IArrayAdapter;
use WebChemistry\DoctrineHydration\Metadata;
use WebChemistry\DoctrineHydration\SkipValueException;

final class SerializeSkipArrayAdapter implements IArrayAdapter {

	/** @var Reader */
	private $reader;

	public function __construct(Reader $reader) {
		$this->reader = $reader;
	}

	public function isWorkable($object, string $field, Metadata $metadata, array $settings): bool {
		return (bool) $this->reader->getPropertyAnnotation($metadata->getMetadata()->getReflectionProperty($field), SerializeSkip::class);
	}

	public function work($object, string $field, $value, Metadata $metadata, array $settings) {
		throw new SkipValueException();
	}

}
