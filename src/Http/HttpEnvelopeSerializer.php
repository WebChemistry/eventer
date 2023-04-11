<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Http;

use Nette\Utils\Json;
use Nette\Utils\JsonException;
use WebChemistry\Eventer\Encoder\EnvelopeEncoder;
use WebChemistry\Eventer\Envelope\Envelope;
use WebChemistry\Eventer\Message\Message;

final class HttpEnvelopeSerializer
{

	public function __construct(
		private EnvelopeEncoder $encoder,
		private ?string $secret = null,
	)
	{
	}

	public function serialize(Envelope ...$envelopes): string
	{
		$encoded = $this->encoder->encode(...$envelopes);
		$secret = $this->secret;

		return $this->encodeBase64(Json::encode([
			$this->encoder->encode(...$envelopes),
			$secret ? md5($encoded . $secret) : null,
		]));
	}

	/**
	 * @return mixed[]|null
	 */
	public function unserialize(?string $query): ?array
	{
		if (!$query) {
			return null;
		}

		$string = $this->decodeBase64($query);

		if ($string === null) {
			return null;
		}

		try {
			$collection = Json::decode($string, Json::FORCE_ARRAY);
		} catch (JsonException) {
			return null;
		}

		if (!is_array($collection)) {
			return null;
		}

		if (!isset($collection[0]) && !is_string($collection[0]) && !array_key_exists(1, $collection)) {
			return null;
		}

		if ($this->secret && md5($collection[0] . $this->secret) !== $collection[1]) {
			return null;
		}

		$envelopes = $this->encoder->decode($collection[0]);

		if (!is_array($envelopes)) {
			return null;
		}

		return $envelopes;
	}

	private function encodeBase64(string $input): string
	{
		return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
	}

	private function decodeBase64(string $input): ?string
	{
		$decode = base64_decode(strtr($input, '-_', '+/'), true);

		if ($decode === false) {
			return null;
		}

		return $decode;
	}

}
