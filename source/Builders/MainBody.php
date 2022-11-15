<?php
declare(strict_types=1);

namespace Swaggier\Builders;

use Swaggier\Builders\Source\BaseBuilder;
use Swaggier\Templates\MainBodyTemplate;

class MainBody extends BaseBuilder
{
	public function __construct()
	{
		parent::__construct(new MainBodyTemplate());
	}

	final public function addPath(string $path, array $requestBody): self
	{
		if (isset($this->template['paths'][$path])) {
			$this->template['paths'][$path] += $requestBody;

			return $this;
		}
		$this->template['paths'][$path] = $requestBody;

		return $this;
	}

	final public function addSchema(array $schema): self
	{
		$this->template['components']['schemas'] += $schema;

		return $this;
	}

	final public function getMainBody(): array
	{
		return $this->template;
	}


}