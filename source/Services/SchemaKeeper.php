<?php
declare(strict_types=1);

namespace Swaggier\Services;

class SchemaKeeper
{
	private array $keeper = [];

	public function __construct()
	{
	}

	/**
	 * @throws \JsonException
	 */
	final public function keep(array $schema): void
	{
		$this->keeper = array_merge($this->keeper, $schema);
	}

	/**
	 * @throws \JsonException
	 */
	final public function read(): array
	{
		return $this->keeper;
	}

}