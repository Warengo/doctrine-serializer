<?php declare(strict_types = 1);

namespace Warengo\Serializer\Adapters;

use WebChemistry\DoctrineHydration\Adapters\IArrayAdapter;
use WebChemistry\DoctrineHydration\Metadata;

class DateArrayAdapter implements IArrayAdapter {

	public function isWorkable($object, string $field, Metadata $metadata, array $settings): bool {
		return !$metadata->isAssociation($field) && in_array($metadata->getFieldMapping($field)['type'], ['date', 'datetime'], true);
	}

	public function work($object, string $field, $value, Metadata $metadata, array $settings) {
		if ($value) {
			$value = $value->format('c');
		}

		return $value;
	}

}
