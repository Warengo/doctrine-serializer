<?php declare(strict_types = 1);

namespace Warengo\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Nette\SmartObject;
use Warengo\Serializer\Adapters\DateArrayAdapter;
use Warengo\Serializer\Adapters\ImageStringArrayAdapter;
use Warengo\Serializer\Adapters\SerializeSkipArrayAdapter;
use Warengo\Serializer\Adapters\SkipAssociationArrayAdapter;
use WebChemistry\DoctrineHydration\Factories\MetadataFactory;
use WebChemistry\DoctrineHydration\Hydration;
use WebChemistry\DoctrineHydration\IHydration;
use WebChemistry\DoctrineHydration\PropertyAccessor;

class Serializer implements ISerializer {

	use SmartObject;

	/** @var IHydration */
	private $hydration;

	public function __construct(EntityManagerInterface $em, ImageStringArrayAdapter $imageStringArrayAdapter, SerializeSkipArrayAdapter $serializeSkipAdapter) {
		$propertyAccessor = new PropertyAccessor();
		$this->hydration = $hydration = new Hydration(new MetadataFactory($em), $propertyAccessor);

		$hydration->addArrayAdapter(new DateArrayAdapter());
		$hydration->addArrayAdapter(new SkipAssociationArrayAdapter());
		$hydration->addArrayAdapter($imageStringArrayAdapter);
		$hydration->addArrayAdapter($serializeSkipAdapter);
	}

	public function serialize($object): string {
		$array = $this->hydration->toArray($object);

		bdump($array);

		return serialize($array);
	}

}
