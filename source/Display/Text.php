<?php
declare(strict_types=1);

namespace Swaggier\Display;

class Text
{
	final public static function colorate(string $color, string $text): string
	{
		return $color . $text . Color::default;
	}

	final public static function use(string $effect, string $text): string
	{
		return $effect . $text . Effect::default;
	}
}