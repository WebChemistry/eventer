<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Encoder;

use Nette\Utils\Json;
use Nette\Utils\JsonException;
use WebChemistry\Eventer\Envelope\Envelope;

final class JsonEnvelopeEncoder implements EnvelopeEncoder
{

	public function encode(Envelope ...$envelopes): string
	{
		$toEncode = [];

		foreach ($envelopes as $envelope) {
			foreach ($envelope->getMessages() as $message) {
				$toEncode[] = [
					$message->getRecipient(),
					$message->getContent(),
				];
			}
		}
		
		return Json::encode($toEncode);
	}

	/**
	 * @return mixed[]|null
	 */
	public function decode(string $string): ?array
	{
		try {
			$decode = Json::decode($string, Json::FORCE_ARRAY);
			if (!is_array($decode)) {
				return null;
			}

			return $decode;
		} catch (JsonException) {
			return null;
		}
	}

}
