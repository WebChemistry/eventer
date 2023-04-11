<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Html;

use Nette\Utils\Json;
use WebChemistry\Eventer\Message\Message;

final class StimulusHtmlRenderer implements HtmlRenderer
{

	public function __construct(
		private string $controller = 'eventer',
	)
	{
	}

	public function render(HtmlEvent ...$events): string
	{
		$html = '';
		$attributes = $this->toAttributes(...$events);

		foreach ($attributes as $name => $value) {
			$html .= sprintf(' %s="%s"', $name, $this->escapeHtmlAttr($value));
		}

		return ltrim($html);
	}

	/**
	 * @param HtmlEvent ...$events
	 * @return array<string, string>
	 */
	public function toAttributes(HtmlEvent ...$events): array
	{
		$attributes = [];

		$attributes['data-controller'] = $this->controller;
		$attributes[sprintf('data-%s-events-value', $this->controller)] = $this->serialize($events);

		return $attributes;
	}

	/**
	 * @param HtmlEvent[] $events
	 */
	private function serialize(array $events): string
	{
		return Json::encode(array_map(
			fn (HtmlEvent $event) => [
				$event->getType(),
				array_map(
					fn (Message $message) => [
						$message->getRecipient(),
						$message->getContent(),
					],
					$this->iterableToArray($event->getEnvelope()->getMessages()),
				),
			],
			$events
		));
	}

	/**
	 * @template T
	 * @param iterable<T> $iterable
	 * @return array<array-key, T>
	 */
	private function iterableToArray(iterable $iterable): array
	{
		if (is_array($iterable)) {
			return $iterable;
		}

		$array = [];

		foreach ($iterable as $value) {
			$array[] = $value;
		}

		return $array;
	}

	private function escapeHtmlAttr(string $string): string
	{
		if (str_contains($string, '`') && strpbrk($string, ' <>"\'') === false) {
			$string .= ' ';
		}

		$string = htmlspecialchars($string, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8');
		$string = str_replace('{', '&#123;', $string);

		return $string;
	}

}
