<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Bridge\Latte;

use Latte\Extension;

final class EventerExtension extends Extension
{

	public function __construct(
		private EventerLatte $eventerLatte,
	)
	{
	}

	public function getProviders(): array
	{
		return [
			'eventerLatte' => $this->eventerLatte,
		];
	}

	public function getTags(): array
	{
		return [
			'n:eventer' => [NEventerNode::class, 'create'],
		];
	}



}
