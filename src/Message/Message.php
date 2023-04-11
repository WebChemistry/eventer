<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Message;

interface Message
{

	public function getRecipient(): string;

	/**
	 * @return mixed[]
	 */
	public function getContent(): array;

}
