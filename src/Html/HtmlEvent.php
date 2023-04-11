<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Html;

use WebChemistry\Eventer\Envelope\Envelope;

final class HtmlEvent
{

	public function __construct(
		private string $type,
		private Envelope $envelope,
	)
	{
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function getEnvelope(): Envelope
	{
		return $this->envelope;
	}

}
