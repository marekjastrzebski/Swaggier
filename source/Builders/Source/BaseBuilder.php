<?php
declare(strict_types=1);

namespace Swaggier\Builders\Source;

use Swaggier\Builders\RequestBodyBuilder;
use Swaggier\Builders\RequestBuilder;
use Swaggier\Builders\SchemaBuilder;
use Swaggier\Templates\Source\Template;

class BaseBuilder
{
	protected array $templateLoader;
	protected array $template;

	public function __construct(Template $loader)
	{
		$this->template = $loader->asArray();
	}

	public function __toString(): string
	{
		return json_encode($this->template);
	}
}