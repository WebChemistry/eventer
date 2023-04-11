<?php declare(strict_types = 1);

namespace WebChemistry\Eventer;

use WebChemistry\Eventer\Envelope\Envelope;

final class CollectionSender implements Sender, Collector
{

	/** @var Envelope[] */
	private array $envelopes = [];

	/**
	 * @return Envelope[]
	 */
	public function getEnvelopes(): array
	{
		return $this->envelopes;
	}

	public function send(Envelope ...$envelopes): void
	{
		foreach ($envelopes as $envelope) {
			$this->envelopes[] = $envelope;
		}
	}

}
