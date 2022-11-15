<?php
declare(strict_types=1);

namespace Swaggier\display;

class Effect
{
	const Bold = "\e[1m";
	const Blink = "\e[5m";
	const Uline = "\e[4m";
	const Invert = "\e[7m";
	const BgGreen = "\e[102m";
	const default = "\e[0m";
}