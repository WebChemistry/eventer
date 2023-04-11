<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Envelope;

use WebChemistry\Eventer\Message\Message;

final class IterableEnvelope implements Envelope
{

	/**
	 * @param iterable<Message> $messages
	 */
	public function __construct(
		private iterable $messages,
	)
	{
	}

	/**
	 * @return iterable<Message>
	 */
	public function getMessages(): iterable
	{
		return $this->messages;
	}

}
