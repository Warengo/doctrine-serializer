<?php declare(strict_types = 1);

namespace Warengo\Serializer;

interface ISerializer {

	public function serialize($object, array $settings = []): string;

	public function serializeArray(array $array, array $settings = []): string;

	public function toArray($object, array $settings = []): array;

	public function toArrays(array $objects, array $settings = []): array;

}
