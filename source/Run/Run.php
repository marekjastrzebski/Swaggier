<?php
declare(strict_types=1);

namespace Swaggier\Run;

use Swaggier\Director;
use Swaggier\messages\Message;

class Run
{
	private array $scenarious;

	public function __construct(private readonly array $arguments)
	{
		$this->validateArguments();
		$this->validateScenarious();
	}

	private function validateArguments(): void
	{
		isset($this->arguments['r']) ?: die(Message::criticalError('Please pass request scenario JSON with flag -r=request.json'));
		file_exists($this->arguments['r']) ?: die(Message::criticalError('Please make sure that file ' . $this->arguments['r'] . 'exists'));
		$this->scenarious = json_decode(file_get_contents($this->arguments['r']), true) ?? die(Message::criticalError('JSON syntax error'));
	}

	private function validateScenarious(): void
	{
		foreach ($this->scenarious as $task) {
			isset($task['url']) ?: die(Message::criticalError('Couldn\'t find url'));
			isset($task['endpoint']) ?: die(Message::criticalError('Couldn\'t find endpoint'));
			isset($task['headers']) ?: die(Message::criticalError('Couldn\'t find headers'));
			isset($task['method']) ?: die(Message::criticalError('Couldn\'t find method'));
		}
	}

	public function run(): void
	{
		$director = match (true) {
			isset($this->arguments['b']) => new Director($this->scenarious, RunMode::BRUTAL),
			default => new Director(($this->scenarious))
		};

		$this->saveResults($director->run());
	}

	private function saveResults(array $results)
	{
		$index = 0;
		$name = 'swaggier';
		$fileName[] = $name;
		while (true) {
			if (!file_exists($fileName[$index] . '.json')) {
				$file = fopen($fileName[$index] . '.json', 'wb');
				file_put_contents($fileName[$index] . '.json', json_encode($results, JSON_UNESCAPED_SLASHES));
				$save = true;
				echo Message::success('Enjoy your OpenApi 3.0.0 description in ' . $fileName[$index] . '.json');
				break;
			}
			$fileName[] = $name . $index;
			$index++;
		}
	}


}