<?php
declare(strict_types=1);

namespace Swaggier\Templates;

use Swaggier\Templates\Source\Template;

class SchemaTemplate extends Template
{
	public function __construct()
	{
		parent::__construct("schema.json");
	}
}