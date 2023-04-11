<?php declare(strict_types = 1);

use Tester\Assert;
use WebChemistry\Eventer\Encoder\JsonEnvelopeEncoder;
use WebChemistry\Eventer\Envelope\CallableEnvelope;
use WebChemistry\Eventer\Message\GoogleEventMessage;
use WebChemistry\Eventer\Message\GoogleSetMessage;

require __DIR__ . '/../bootstrap.php';

$envelope = new CallableEnvelope(function (): iterable {
	yield new GoogleSetMessage('foo', 'bar');
	yield new GoogleEventMessage('foo', ['key' => 'bar']);
});

$encoder = new JsonEnvelopeEncoder();

Assert::same('[["gtag",["set","foo","bar"]],["gtag",["event","foo",{"key":"bar"}]]]', $encoder->encode($envelope));
