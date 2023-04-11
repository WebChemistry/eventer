<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Message;

final class GoogleEventMessage implements Message
{

	/** @var array<string, string|int|float> */
	private array $arguments;

	/**
	 * @param array<string, string|int|float|null> $arguments
	 */
	public function __construct(
		private string $name,
		array $arguments,
	)
	{
		$this->arguments = array_filter($arguments, fn($value) => $value !== null);
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
		return ['event', $this->name, $this->arguments];
	}

}
