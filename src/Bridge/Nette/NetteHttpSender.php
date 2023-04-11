<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Bridge\Nette;

use Nette\Http\Url;
use WebChemistry\Eventer\Envelope\Envelope;
use WebChemistry\Eventer\Http\HttpEnvelopeSerializer;
use WebChemistry\Eventer\Http\HttpReceiver;
use WebChemistry\Eventer\Http\HttpSender;

final class NetteHttpSender implements HttpSender, HttpReceiver
{

	public function __construct(
		private HttpEnvelopeSerializer $serializer,
		private string $queryName = '_eventer',
	)
	{
	}

	/**
	 * @return mixed[]|null
	 */
	public function receive(string $link): ?array
	{
		$url = new Url($link);
		$query = $url->getQueryParameter($this->queryName);

		if (!is_string($query)) {
			return null;
		}

		return $this->serializer->unserialize($query);
	}

	public function send(string $link, Envelope ...$envelopes): string
	{
		$url = new Url($link);
		$url->setQueryParameter($this->queryName, $this->serializer->serialize(...$envelopes));

		return (string) $url;
	}

}
