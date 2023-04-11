<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Bridge\Latte;

use ArrayIterator;
use Exception;
use InvalidArgumentException;
use WebChemistry\Eventer\Envelope\Envelope;
use WebChemistry\Eventer\Html\HtmlEvent;
use WebChemistry\Eventer\Html\HtmlRenderer;
use WebChemistry\Eventer\Iterator\CountableArrayIterator;

final class EventerLatte
{

	public function __construct(
		private HtmlRenderer $htmlRenderer,
	)
	{
	}

	public function render(mixed ...$args): string
	{
		$it = new CountableArrayIterator($args);
		$events = [];

		while ($it->valid()) {
			$current = $it->current();

			if ($current instanceof HtmlEvent) {
				$events[] = $current;

			} else if (is_string($current)) {
				$it->next();

				if (!$it->valid()) {
					throw new Exception(sprintf('Expected Envelope argument at offset %d.', $it->getOffset()));
				}

				$next = $it->current();

				if (!$next instanceof Envelope) {
					throw new Exception(sprintf('Expected Envelope argument at offset %d.', $it->getOffset()));
				}

				$events[] = new HtmlEvent($current, $next);

			} else {
				throw new InvalidArgumentException(sprintf('Expected HtmlEvent or string at offset %d.', $it->getOffset()));
			}

			$it->next();
		}

		return $this->htmlRenderer->render(...$events);
	}

}
