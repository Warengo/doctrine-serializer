<?php declare(strict_types = 1);

namespace Warengo\Serializer\Adapters;

use Nettrine\Hydrator\Adapters\IArrayAdapter;
use Nettrine\Hydrator\Arguments\ArrayArgs;
use Nettrine\Hydrator\Metadata;

class DateArrayAdapter implements IArrayAdapter {

	public function isWorkable(ArrayArgs $args): bool {
		return !$args->metadata->isAssociation($args->field) && in_array($args->metadata->getFieldMapping($args->field)['type'], ['date', 'datetime'], true);
	}

	public function work(ArrayArgs $args): void {
		if ($args->value) {
			$args->setValue($args->value->format('c'));
		}
	}

}
