<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Bridge\Nette\Component;

trait EventerPresenter
{

	private EventerComponentFactory $eventerComponentFactory;

	final public function injectEventerComponentFactory(EventerComponentFactory $eventerComponentFactory): void
	{
		$this->eventerComponentFactory = $eventerComponentFactory;
	}

	protected function createComponentEventer(): EventerComponent
	{
		return $this->eventerComponentFactory->create();
	}

}
