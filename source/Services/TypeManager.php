<?php
declare(strict_types=1);

namespace Swaggier\Services;

class TypeManager
{
	public static function isAssocArray(array $array): bool
	{
		if (array() === $array) {
			return false;
		}

		return array_keys($array) !== range(0, count($array) - 1);
	}

	public static function openApiType(mixed $element): string
	{
		return match (true) {
			is_int($element) => "integer",
			is_bool($element) => "boolean",
			is_string($element) => "string",
			is_float($element) => "number",
			default => "string"
		};
	}
}