<?php
declare(strict_types=1);

namespace Swaggier;

trait AlternativeScenarios
{
	final public function getAlternetives(): array
	{
		return array_slice(get_class_methods(AlternativeScenarios::class), 1);
	}

	final public function noHeaders(array $scenario): array
	{
		$scenario['headers'] = [];

		return $scenario;
	}

	final public function noRequest(array $scenario): array
	{
		$scenario['request'] = [];
		$scenario['parameters'] = [];

		return $scenario;
	}

	final public function typeJuggling(array $scenario): array
	{
		if (isset($scenario['parameters'])) {
			$scenario['parameters'] = $this->changeTypes($scenario['parameters']);
		}
		if (isset($scenario['request'])) {
			$scenario['request'] = $this->changeTypes($scenario['request']);
		}

		return $scenario;
	}

	protected function changeTypes(array $data): array
	{
		$request = [];
		$keys = array_keys($data);
		for ($index = 0, $max = count($data); $index < $max; $index++) {
			$element = $data[$keys[$index]];
			$element = match (true) {
				is_string($element) => random_int(0, 99999999),
				is_null($element) || is_int($element) || is_float($element) || is_array($element) => $this->randomString(),
				default => null,
			};
			$request[$keys[$index]] = $element;
		}

		return $request;
	}

	protected function randomString()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string = '';
		for ($index = 0, $max = random_int(6, 20); $index < $max; $index++) {
			$number = rand(0, strlen($characters) -1);
			$string .= $characters[$number];
		}

		return $string;
	}
}