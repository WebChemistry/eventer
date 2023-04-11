<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Http;

interface HttpReceiver
{

	/**
	 * @return mixed[]|null
	 */
	public function receive(string $link): ?array;

}
