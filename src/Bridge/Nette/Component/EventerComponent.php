<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Bridge\Nette\Component;

use Nette\Application\UI\Control;
use Nette\Application\UI\Template;
use Nette\Http\IRequest;
use stdClass;
use WebChemistry\Eventer\Collector;
use WebChemistry\Eventer\Http\HttpReceiver;
use WebChemistry\Eventer\Message\Message;

final class EventerComponent extends Control
{

	public function __construct(
		private Collector $collector,
		private IRequest $request,
		private ?string $queryName = '_eventer',
		private ?HttpReceiver $receiver = null,
	)
	{
	}

	public function render(): void
	{
		/** @var stdClass&Template $template */
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/templates/eventer.latte');

		$template->messages = $this->getMessages();
		$template->http = $this->getHttpMessages();
		$template->queryName = $this->queryName;

		$template->render();
	}

	public function renderInitialize(): void
	{
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/templates/initialize.latte');

		$template->render();
	}

	/**
	 * @return Message[]
	 */
	private function getMessages(): iterable
	{
		foreach ($this->collector->getEnvelopes() as $envelope) {
			foreach ($envelope->getMessages() as $message) {
				yield $message;
			}
		}
	}

	/**
	 * @return mixed[]
	 */
	private function getHttpMessages(): iterable
	{
		foreach ((array) $this->receiver?->receive($this->request->getUrl()->getAbsoluteUrl()) as $message) {
			if (!is_array($message)) {
				continue;
			}

			if (is_string($message[0] ?? null) && is_array($message[1] ?? null)) {
				yield $message;
			}
		}
	}

}
