<?php declare(strict_types = 1);

namespace Warengo\Serializer\DI;

use Nette\DI\CompilerExtension;
use Warengo\Serializer\Adapters\ImageStringArrayAdapter;
use Warengo\Serializer\Adapters\SerializeSkipArrayAdapter;
use Warengo\Serializer\ISerializer;
use Warengo\Serializer\Serializer;

final class DoctrineSerializerExtension extends CompilerExtension {

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();

		$adapter = $builder->addDefinition($this->prefix('imageStringAdapter'))
			->setFactory(ImageStringArrayAdapter::class)
			->setAutowired(false);

		$skipAssociation = $builder->addDefinition($this->prefix('serializeSkipAdapter'))
			->setFactory(SerializeSkipArrayAdapter::class)
			->setAutowired(false);

		$builder->addDefinition($this->prefix('serializer'))
			->setType(ISerializer::class)
			->setFactory(Serializer::class, [
				'imageStringArrayAdapter' => $adapter,
				'serializeSkipAdapter' => $skipAssociation
			]);
	}

}
