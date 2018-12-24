<?php declare(strict_types = 1);

namespace Warengo\Serializer\Adapters;

use WebChemistry\DoctrineHydration\Adapters\IArrayAdapter;
use WebChemistry\DoctrineHydration\Metadata;
use WebChemistry\DoctrineHydration\SkipValueException;

final class SkipAssociationArrayAdapter implements IArrayAdapter {

	public function isWorkable($object, string $field, Metadata $metadata, array $settings): bool {
		return $metadata->isAssociation($field);
	}

	public function work($object, string $field, $value, Metadata $metadata, array $settings) {
		throw new SkipValueException();
	}

}
