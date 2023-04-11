<?php declare(strict_types = 1);

use Tester\Assert;
use WebChemistry\Eventer\Envelope\CallableEnvelope;
use WebChemistry\Eventer\Message\GoogleEventMessage;
use WebChemistry\Eventer\Message\GoogleSetMessage;

require __DIR__ . '/../bootstrap.php';

$envelope = new CallableEnvelope(function (): iterable {
	yield new GoogleSetMessage('foo', 'bar');
	yield new GoogleEventMessage('foo', ['key' => 'bar']);
});

Assert::count(2, iterator_to_array($envelope->getMessages()));
