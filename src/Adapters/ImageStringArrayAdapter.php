<?php declare(strict_types = 1);

namespace Warengo\Serializer\Adapters;

use Doctrine\Common\Annotations\Reader;
use Warengo\Serializer\Annotations\Serialize;
use WebChemistry\DoctrineHydration\Adapters\IArrayAdapter;
use WebChemistry\DoctrineHydration\Metadata;
use WebChemistry\Images\IImageStorage;
use WebChemistry\Images\Resources\Transfer\LocalResource;

final class ImageStringArrayAdapter implements IArrayAdapter {

	/** @var IImageStorage */
	private $storage;

	/** @var Reader */
	private $reader;

	public function __construct(IImageStorage $storage, Reader $reader) {
		$this->storage = $storage;
		$this->reader = $reader;
	}

	public function isWorkable($object, string $field, Metadata $metadata, array $settings): bool {
		return !$metadata->isAssociation($field) && $metadata->getFieldMapping($field)['type'] === 'image';
	}

	/**
	 * @param object $object
	 * @param string $field
	 * @param LocalResource|null $value
	 * @param Metadata $metadata
	 * @param array $settings
	 * @return mixed
	 */
	public function work($object, string $field, $value, Metadata $metadata, array $settings) {
		if ($value) {
			/** @var Serialize $annotation */
			$annotation = $this->reader->getPropertyAnnotation($metadata->getMetadata()->getReflectionProperty($field), Serialize::class);
			$options = $annotation->getOptions() ? : [null];
			$aliases = [];
			foreach ($options as $alias) {
				$value->setAliases([
					$alias => [],
				]);
				$aliases[] = $this->storage->link($value);
			}
			$value = $aliases;
		}

		return $value;
	}

}
