<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Html;

interface HtmlRenderer
{

	public function render(HtmlEvent ... $events): string;

}
