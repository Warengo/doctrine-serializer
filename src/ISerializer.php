<?php declare(strict_types = 1);

namespace Warengo\Serializer;

interface ISerializer {

	public function serialize($object): string;

}
