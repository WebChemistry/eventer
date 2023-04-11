<?php declare(strict_types = 1);

namespace WebChemistry\Eventer;

use WebChemistry\Eventer\Envelope\Envelope;

interface Collector
{

	/**
	 * @return Envelope[]
	 */
	public function getEnvelopes(): array;

}
