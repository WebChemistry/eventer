<?php declare(strict_types = 1);

use Tester\Assert;
use WebChemistry\Eventer\Envelope\Envelope;
use WebChemistry\Eventer\Envelope\IterableEnvelope;
use WebChemistry\Eventer\Html\HtmlEvent;
use WebChemistry\Eventer\Html\StimulusHtmlRenderer;
use WebChemistry\Eventer\Message\GoogleEventMessage;
use WebChemistry\Eventer\Message\GoogleSetMessage;
use WebChemistry\Eventer\Message\Message;

require __DIR__ . '/../bootstrap.php';

$envelope = new IterableEnvelope([
	new GoogleSetMessage('foo', 'bar'),
	new GoogleEventMessage('foo', ['key' => 'bar']),
]);

$renderer = new StimulusHtmlRenderer('eventer');

Assert::same([
	'data-controller' => 'eventer',
	'data-eventer-events-value' => '[["click",[["gtag",["set","foo","bar"]],["gtag",["event","foo",{"key":"bar"}]]]]]',
], $renderer->toAttributes(new HtmlEvent('click', $envelope)));
