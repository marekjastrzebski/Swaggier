<?php
declare(strict_types=1);

namespace Swaggier\Services;

use Swaggier\Components\BaseSchema;

class Converter
{
	private array $schemas = [];

	public function __construct(array $jsonContent, string $schemaName)
	{
		$schema = new BaseSchema($schemaName);
		$this->schemas = $this->buildSchemas($jsonContent, $schema);
	}

	private function buildSchemas(array      $schema,
								  BaseSchema $schemaInstance)
	{
		$schemasNames = array_keys($schema);
		for ($index = 0, $indexMax = count($schema); $index < $indexMax; $index++) {
			$attributeName = $schemasNames[$index];
			if (is_array($schema[$attributeName])) {
				$this->addAttributeToCurrentSchema($attributeName, $this->addNewSchema($attributeName, $schema[$attributeName], $schemaInstance), $schemaInstance);
				continue;
			}
			$this->addAttributeToCurrentSchema($attributeName, $schema[$attributeName], $schemaInstance);
		}

		return $schemaInstance->getSchema();
	}

	private function addAttributeToCurrentSchema(string|int $attributeName,
												 mixed      $attributeValue,
												 BaseSchema $schemaInstance): BaseSchema
	{
		$schemaInstance->addRequired($attributeName)
			->addProperty($attributeName)
			->setType(TypeManager::openApiType($attributeValue))
			->setExample($attributeValue)
			->getProperty();

		return $schemaInstance;
	}

	private function addNewSchema(string|int $attributeName,
								  array      $schema,
								  BaseSchema $schemaInstance)
	{
		switch (TypeManager::isAssocArray($schema)) {
			case true:
			{
				$schemaInstance->setType("object")
					->addProperty($attributeName)
					->setType("object")
					->setRef("#/components/schemas/$attributeName")
					->getProperty();
				$newSchema = new BaseSchema($attributeName);

				return $this->buildSchemas($schema, $newSchema);
			}
			case false:
			{
				return $schemaInstance->setType("array")
					->addProperty($attributeName)
					->setType("array")
					->setArrayExample($schema)
					->getProperty();
			}
		}
	}

	final public function getSchemas(): array
	{
		return $this->schemas;
	}
}