<?php declare(strict_types = 1);

namespace Warengo\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Nette\SmartObject;
use Nettrine\Hydrator\Hydrator;
use Warengo\Serializer\Adapters\DateArrayAdapter;
use Warengo\Serializer\Adapters\ImageStringArrayAdapter;
use Warengo\Serializer\Adapters\SerializeSkipArrayAdapter;
use Warengo\Serializer\Adapters\SkipAssociationArrayAdapter;
use Nettrine\Hydrator\Factories\MetadataFactory;
use Nettrine\Hydrator\IHydration;
use Nettrine\Hydrator\PropertyAccessor;

class Serializer implements ISerializer {

	use SmartObject;

	/** @var IHydration */
	private $hydration;

	public function __construct(EntityManagerInterface $em, ImageStringArrayAdapter $imageStringArrayAdapter, SerializeSkipArrayAdapter $serializeSkipAdapter) {
		$propertyAccessor = new PropertyAccessor();
		$this->hydration = $hydration = new Hydrator(new MetadataFactory($em), $propertyAccessor);

		$hydration->addArrayAdapter(new DateArrayAdapter());
		$hydration->addArrayAdapter(new SkipAssociationArrayAdapter());
		$hydration->addArrayAdapter($imageStringArrayAdapter);
		$hydration->addArrayAdapter($serializeSkipAdapter);
	}

	public function serialize($object, array $settings = []): string {
		$array = $this->hydration->toArray($object, $settings);

		return json_encode($array);
	}

	public function serializeArray(array $array, array $settings = []): string {
		return json_encode($this->toArrays($array, $settings));
	}

	public function toArray($object, array $settings = []): array {
		$array = $this->hydration->toArray($object, $settings);
		$callback = $settings['callback'] ?? null;
		if ($callback) {
			$array = $array + $callback($object);
		}

		return $array;
	}

	public function toArrays(array $objects, array $settings = []): array {
		$callback = $settings['callback'] ?? null;
		$final = [];
		foreach ($objects as $key => $item) {
			$final[$key] = $this->hydration->toArray($item, $settings);
			if ($callback) {
				$final[$key] = $final[$key] + $callback($item);
			}
		}

		return $final;
	}

}
