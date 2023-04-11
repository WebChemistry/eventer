<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Http;

use WebChemistry\Eventer\Envelope\Envelope;

interface HttpSender
{

	public function send(string $link, Envelope ...$envelopes): string;

}
