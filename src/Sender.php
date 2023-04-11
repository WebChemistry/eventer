<?php declare(strict_types = 1);

namespace WebChemistry\Eventer;

use WebChemistry\Eventer\Envelope\Envelope;

interface Sender
{

	public function send(Envelope ...$envelopes): void;

}
