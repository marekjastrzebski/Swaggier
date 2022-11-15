<?php
declare(strict_types=1);

namespace Swaggier\display;

class Screen
{
	private array $screen;

	public function __construct(private readonly string $startScreen)
	{
		ob_start();
	}

	public function row(string $name, string $text): void
	{
		$this->screen[$name] = $text;
		$this->reload();
	}


	private function reload(): void
	{
		$this->erase();
		echo $this->startScreen;
		foreach ($this->screen as $row) {
			echo $row . "\n";
		}
	}

	private function erase(): void
	{
		ob_clean();
	}
}