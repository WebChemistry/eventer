<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Envelope;

use WebChemistry\Eventer\Message\Message;

interface Envelope
{

	/**
	 * @return iterable<Message>
	 */
	public function getMessages(): iterable;

}
