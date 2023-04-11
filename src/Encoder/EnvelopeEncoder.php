<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Encoder;

use WebChemistry\Eventer\Envelope\Envelope;

interface EnvelopeEncoder
{

	public function encode(Envelope ... $envelopes): string;

	/**
	 * @return mixed[]|null
	 */
	public function decode(string $string): ?array;

}
