<?php declare(strict_types = 1);

use Tester\Assert;
use WebChemistry\Eventer\Encoder\JsonEnvelopeEncoder;
use WebChemistry\Eventer\Envelope\CallableEnvelope;
use WebChemistry\Eventer\Http\HttpEnvelopeSerializer;
use WebChemistry\Eventer\Message\GoogleEventMessage;
use WebChemistry\Eventer\Message\GoogleSetMessage;

require __DIR__ . '/../bootstrap.php';

$envelope = new CallableEnvelope(function (): iterable {
	yield new GoogleSetMessage('foo', 'bar');
	yield new GoogleEventMessage('foo', ['key' => 'bar']);
});

$serializer = new HttpEnvelopeSerializer(new JsonEnvelopeEncoder());

Assert::same($expected = 'WyJbW1wiZ3RhZ1wiLFtcInNldFwiLFwiZm9vXCIsXCJiYXJcIl1dLFtcImd0YWdcIixbXCJldmVudFwiLFwiZm9vXCIse1wia2V5XCI6XCJiYXJcIn1dXV0iLG51bGxd', $serializer->serialize($envelope));
Assert::same([
	['gtag', ['set', 'foo', 'bar']],
	['gtag', ['event', 'foo', ['key' => 'bar']]],
], $serializer->unserialize($expected));

$serializerAnotherSecret = new HttpEnvelopeSerializer(new JsonEnvelopeEncoder(), 'secret2');
$serializer = new HttpEnvelopeSerializer(new JsonEnvelopeEncoder(), 'secret');

Assert::same($expected = 'WyJbW1wiZ3RhZ1wiLFtcInNldFwiLFwiZm9vXCIsXCJiYXJcIl1dLFtcImd0YWdcIixbXCJldmVudFwiLFwiZm9vXCIse1wia2V5XCI6XCJiYXJcIn1dXV0iLCJkMGE2MTA5MmRhOGE4NjNiNDEyMjQyZjA4MGQzZGRlZiJd', $serializer->serialize($envelope));
Assert::same([
	['gtag', ['set', 'foo', 'bar']],
	['gtag', ['event', 'foo', ['key' => 'bar']]],
], $serializer->unserialize($expected));
Assert::null($serializerAnotherSecret->unserialize($expected));

