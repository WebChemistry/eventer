<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Bridge\Nette\DI;

use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\MissingServiceException;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;
use WebChemistry\Eventer\Bridge\Latte\EventerExtension as EventerLatteExtension;
use WebChemistry\Eventer\Bridge\Latte\EventerLatte;
use WebChemistry\Eventer\Bridge\Nette\Component\EventerComponentFactory;
use WebChemistry\Eventer\Bridge\Nette\NetteHttpSender;
use WebChemistry\Eventer\CollectionSender;
use WebChemistry\Eventer\Encoder\JsonEnvelopeEncoder;
use WebChemistry\Eventer\Html\StimulusHtmlRenderer;
use WebChemistry\Eventer\Http\HttpEnvelopeSerializer;

final class EventerExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'secret' => Expect::string()->nullable(),
			'queryName' => Expect::string('_eventer'),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		/** @var stdClass $config */
		$config = $this->getConfig();

		$builder->addDefinition($this->prefix('sender'))
			->setFactory(CollectionSender::class);

		$builder->addDefinition($this->prefix('httpSender'))
			->setFactory(NetteHttpSender::class, ['queryName' => $config->queryName]);

		$builder->addDefinition($this->prefix('encoder'))
			->setFactory(JsonEnvelopeEncoder::class);

		$builder->addDefinition($this->prefix('httpSerializer'))
			->setFactory(HttpEnvelopeSerializer::class, ['secret' => $config->secret]);

		$builder->addDefinition($this->prefix('htmlRenderer'))
			->setFactory(StimulusHtmlRenderer::class);

		$builder->addFactoryDefinition($this->prefix('enverComponent'))
			->setImplement(EventerComponentFactory::class)
				->getResultDefinition()
				->setArgument('queryName', $config->queryName);

		$builder->addDefinition($this->prefix('latte.extension'))
			->setFactory(EventerLatteExtension::class);

		$builder->addDefinition($this->prefix('latte'))
			->setFactory(EventerLatte::class);
	}

	public function beforeCompile(): void
	{
		$this->registerLatte();
	}

	private function registerLatte(): void
	{
		$builder = $this->getContainerBuilder();

		try {
			/** @var FactoryDefinition $latteFactory */
			$latteFactory = $builder->getDefinitionByType(LatteFactory::class);
		} catch (MissingServiceException) {
			return;
		}

		$latteFactory->getResultDefinition()->addSetup('addExtension', [$builder->getDefinition($this->prefix('latte.extension'))]);
	}

}
