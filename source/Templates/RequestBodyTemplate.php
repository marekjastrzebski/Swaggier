<?php
declare(strict_types=1);

namespace Swaggier\Templates;

use Swaggier\Templates\Source\Template;

class RequestBodyTemplate extends Template
{
	public function __construct()
	{
		parent::__construct("requestBody.json");
	}
}