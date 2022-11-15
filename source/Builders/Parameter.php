<?php
declare(strict_types=1);

namespace Swaggier\Builders;

use Swaggier\Builders\Source\BaseBuilder;
use Swaggier\Templates\ParametersTemplate;

class Parameter extends BaseBuilder
{
	public function __construct()
	{
		parent::__construct(new ParametersTemplate);
	}

	final public function setName(string $name): self
	{
		$this->template['name'] = $name;

		return $this;
	}

	final public function setType(string $type): self
	{
		$this->template['schema']['type'] = $type;

		return $this;
	}

	final public function getParameter(): array
	{
		return $this->template;
	}
}