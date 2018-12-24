<?php declare(strict_types = 1);

namespace Warengo\Serializer\Annotations;

/**
 * @Annotation
 */
class Serialize {

	/** @var array */
	private $data;

	public function __construct(array $data) {
		$this->data = $data;
	}

	public function getOptions(): array {
		return $this->data['options'] ?? [];
	}

}
