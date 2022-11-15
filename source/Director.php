<?php
declare(strict_types=1);

namespace Swaggier;

use Swaggier\Builders\MainBody;
use Swaggier\Builders\RequestBody;
use Swaggier\Communication\Foundation\HttpMethods;
use Swaggier\Communication\SendRequest;
use Swaggier\Display\Screen;
use Swaggier\messages\Message;
use Swaggier\Run\RunMode;

class Director
{
	private string $method;
	private array $request;
	private string $endpoint;
	private string $url;
	private SendRequest $sender;
	private MainBody $mainBody;
	private array $schemas = [];
	private $requestBody = [];
	private array $results = [];
	private Screen $screen;

	use AlternativeScenarios;

	/**
	 * @throws \JsonException
	 */
	public function __construct(private readonly array   $mainScenario,
								private readonly RunMode $runMode = RunMode::STANDARD)
	{
		$this->mainBody = new MainBody();

	}


	final public function run(): array
	{
		try {
			return $this->runMainScenario();

		} catch (\Exception $exception) {
			die($exception);
		}
	}

	/**
	 * @throws \JsonException
	 */
	private function runMainScenario(): array
	{
		$index = 0;
		echo Message::popup("Working...");

		$this->screen = new Screen(Message::neutral('Start:'));

		foreach ($this->mainScenario as $scenario) {
			$endpoint = $this->extractEndpoint($scenario);
			$bindEndpoint = $this->bindParameters($this->extractEndpoint($scenario), $scenario);
			$url = $this->extractUrl($scenario);
			$request = $this->extractRequest($scenario);
			$this->screen->row('mainTaskHeader' . $index, $url . $endpoint);
			$this->screen->row('mainTask' . $index, Message::taskStart($scenario['method']));
			$requestBody = new RequestBody($endpoint);
			$this->detectParameters($requestBody, $scenario);
			$response = (new SendRequest($url . $bindEndpoint, $request))
				->setMethod($this->getMethod($scenario))
				->setHeaders($this->getHeaders($scenario));

			$responseContent = $response->getResponse();
			$responseCode = $response->getResponseInfo()['http_code'];
			$requestBody->setMethod($this->getMethod($scenario))
				->setRequest($request)
				->addResponse()
				->setCode($responseCode)
				->setContent(json_decode($responseContent, true) ?? []);
			$this->runAlternativeScenarios($scenario, $requestBody);
			$this->mainBody->addPath($endpoint, $requestBody->getRequestBodyWithResponses())
				->addSchema($requestBody->getSchemas());
			$this->screen->row('mainTask' . $index, Message::taskEnd($scenario['method'], $url . $endpoint, $responseCode));
			$index++;
		}

		return $this->mainBody->getMainBody();
	}

	private function extractEndpoint(array $scenario): string
	{
		return match (isset($scenario['endpoint'])) {
			true => $scenario['endpoint'],
			false => ""
		};
	}

	private function bindParameters(string $endPoint, array $scenario): string
	{
		if (!isset($scenario['parameters'])) {
			return $endPoint;
		}
		$newEndPoint = '/';
		$endpointParts = explode('/', $endPoint);
		foreach ($endpointParts as $part) {
			$param = str_replace(['}', '{'], '', $part);
			if ($part === $param) {
				$newEndPoint .= $part;
				continue;
			}
			if (empty($scenario['parameters'])) {
				continue;
			}
			$newEndPoint .= '/' . $scenario['parameters'][$param] ?? null;
		}

		return $newEndPoint;
	}

	private function extractUrl(array $scenario): string
	{
		return match (isset($scenario['url'])) {
			true => $scenario['url'],
			false => "https://localhost"
		};
	}

	/**
	 * @throws \JsonException
	 */
	private function extractRequest(array $scenario): array
	{
		if (!isset($scenario['request'])) {
			return [];
		}

		return match (true) {
			is_array($scenario['request']) => $scenario['request'],
			is_string($scenario['request']) && file_exists($scenario['request'])
			=> json_decode(file_get_contents($scenario['request']), true, 512, JSON_THROW_ON_ERROR),
			default => []
		};
	}

	private function detectParameters(RequestBody $requestBody, array $scenario): void
	{
		if (isset($scenario['parameters'])) {
			$requestBody->setRequestParam($scenario['parameters']);
		}
	}

	private function getMethod(array $scenario): HttpMethods
	{
		if (!isset($scenario['method'])) {
			return HttpMethods::GET;
		}

		return match (strtolower($scenario['method'])) {
			'post' => HttpMethods::POST,
			'put' => HttpMethods::PUT,
			'patch' => HttpMethods::PATCH,
			'delete' => HttpMethods::DELETE,
			default => HttpMethods::GET
		};
	}

	private function getHeaders(array $scenario): array
	{
		if (!isset($scenario['headers'])) {
			return [];
		}

		return match (is_array($scenario['headers'])) {
			true => $scenario['headers'],
			false => []
		};
	}

	private function runAlternativeScenarios(array $originalScenario, RequestBody $requestBody): bool
	{
		if ($this->runMode !== RunMode::BRUTAL) {
			return false;
		}
		foreach ($this->getAlternetives() as $alternative) {
			$scenario = $this->$alternative($originalScenario);
			$endpoint = $this->extractEndpoint($scenario);
			$bindEndpoint = $this->bindParameters($this->extractEndpoint($scenario), $scenario);
			$url = $this->extractUrl($scenario);
			$request = $this->extractRequest($scenario);
			$this->screen->row($alternative . $endpoint . $scenario['method'], Message::taskStart($scenario['method']));

			$response = (new SendRequest($url . $bindEndpoint, $request))
				->setMethod($this->getMethod($scenario))
				->setHeaders($this->getHeaders($scenario));
			$responseContent = $response->getResponse();
			$responseCode = $response->getResponseInfo()['http_code'];
			$requestBody->addResponse()
				->setCode($responseCode)
				->setContent(json_decode($responseContent, true) ?? []);
			$this->screen->row($alternative . $endpoint . $scenario['method'], Message::taskEnd($scenario['method'], $url . $endpoint, $responseCode, $alternative));
		}

		return true;
	}

}