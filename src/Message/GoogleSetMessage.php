<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Message;

final class GoogleSetMessage implements Message
{

	public function __construct(
		private string $name,
		private string|int|float|null $value,
	)
	{
	}

	public function getRecipient(): string
	{
		return 'gtag';
	}

	/**
	 * @return mixed[]
	 */
	public function getContent(): array
	{
		return ['set', $this->name, $this->value];
	}

}
