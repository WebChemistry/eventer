<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Envelope;

use WebChemistry\Eventer\Message\Message;

final class CallableEnvelope implements Envelope
{

	/** @var callable(): iterable<Message> */
	private $callback;

	/**
	 * @param callable(): iterable<Message> $callback
	 */
	public function __construct(callable $callback)
	{
		$this->callback = $callback;
	}

	public function getMessages(): iterable
	{
		return ($this->callback)();
	}

}
