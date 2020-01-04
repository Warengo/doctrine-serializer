<?php declare(strict_types = 1);

namespace Warengo\Serializer\Adapters;

use Doctrine\Common\Annotations\Reader;
use Warengo\Serializer\Annotations\Serialize;
use Nettrine\Hydrator\Adapters\IArrayAdapter;
use Nettrine\Hydrator\Arguments\ArrayArgs;
use Nettrine\Hydrator\Metadata;
use WebChemistry\Images\IImageStorage;
use WebChemistry\Images\Resources\EmptyResource;
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

	public function isWorkable(ArrayArgs $args): bool {
		return !$args->metadata->isAssociation($args->field) && $args->metadata->getFieldMapping($args->field)['type'] === 'image';
	}

	public function work(ArrayArgs $args): void {
		$value = $args->value;
		if (!$value) {
			$value = new EmptyResource();
		}

		$options = [
			'default' => null,
		];
		if (!$args->hasSettingsSection('images')) {
			/** @var Serialize $annotation */
			$annotation = $this->reader->getPropertyAnnotation($args->metadata->getMetadata()->getReflectionProperty($args->field), Serialize::class);
			if (!$annotation) {
				$args->setValue([null]);
				return;
			}
			$filt = $annotation->getOptions() ? : [null];
			$options['default'] = $annotation->getDefault();
		} else {
			$filt = $args->getSettingsSection('images');
		}

		$filters = [];
		foreach ($filt as $name => $alias) {
			if (is_array($alias)) {
				foreach ($alias as $key => $_) {
					$value->setFilter($key);
				}
			} else {
				$value->setFilter($alias);
			}
			$filters[$name] = $this->storage->link($value, $options);
		}
		$value = $filters;

		$args->setValue($value);
	}

}
