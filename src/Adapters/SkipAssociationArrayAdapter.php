<?php declare(strict_types = 1);

namespace Warengo\Serializer\Adapters;

use Nettrine\Hydrator\Arguments\ArrayArgs;
use Nettrine\Hydrator\Adapters\IArrayAdapter;
use Nettrine\Hydrator\Metadata;
use Nettrine\Hydrator\SkipValueException;

final class SkipAssociationArrayAdapter implements IArrayAdapter {

	public function isWorkable(ArrayArgs $args): bool {
		return $args->metadata->isAssociation($args->field);
	}

	public function work(ArrayArgs $args): void {
		$args->unsetValue();
	}

}
