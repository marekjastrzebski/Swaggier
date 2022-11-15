<?php
declare(strict_types=1);

namespace Swaggier\messages;

use Swaggier\Display\Color;
use Swaggier\Display\Effect;
use Swaggier\Display\Text;

class Message
{
	public static function error(string $message): string
	{
		return Text::Colorate(Color::Red, 'ERROR') . ": $message";
	}

	public static function neutral(string $message): string
	{
		return Text::Colorate(Color::Blue, $message . "\n");
	}

	public static function popup(string $message): string
	{
		return Text::Colorate(Color::GreenLight, $message . "\n");
	}

	public static function taskStart(string $method): string
	{
		return Text::colorate(Color::Blue, $method) . ':' . 'Connecting...';
	}

	public static function taskEnd(string $method, string $url, int $code, string $alternative = ""): string
	{
		return Text::colorate(Color::Blue, $method) . ':' . Text::colorate(Color::Green, (string)$code) . ' ' . $alternative;
	}

	public static function criticalError(string $message): string
	{
		return Text::use(Effect::Invert,
			Text::use(Effect::Bold,
				Text::Colorate(Color::Red, $message . "\n"
				)));
	}

	public static function success(string $message): string
	{
		return Text::use(Effect::Bold,
			Text::use(Effect::BgGreen,
				Text::Colorate(Color::default, $message . "\n"
				)));
	}

	public static function logo(): string
	{
		return file_get_contents(__DIR__ . "/Presets/logo");
	}

	public static function swaggier(): string
	{
		return file_get_contents(__DIR__ . "/Presets/swaggier");
	}

}