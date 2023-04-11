<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Bridge\Nette\Component;

interface EventerComponentFactory
{

	public function create(): EventerComponent;

}
