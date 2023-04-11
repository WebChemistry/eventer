<?php declare(strict_types = 1);

namespace WebChemistry\Eventer\Bridge\Latte;

use Generator;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

final class NEventerNode extends StatementNode
{

	private ArrayNode $arguments;

	public static function create(Tag $tag): self
	{
		$tag->expectArguments();

		$node = new self();
		$node->arguments = $tag->parser->parseArguments();

		return $node;
	}

	public function print(PrintContext $context): string
	{
		return $context->format(
			'echo " " . $this->global->eventerLatte->render(%args);',
			$this->arguments,
		);
	}

	public function &getIterator(): Generator
	{
		yield $this->arguments;
	}

}
