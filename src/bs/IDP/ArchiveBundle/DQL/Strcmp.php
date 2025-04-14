<?php
/**
 * Strcmp Mysql Function
 *
 * under MIT LICENSE
 *
 */
namespace bs\IDP\ArchiveBundle\DQL;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

class Strcmp extends FunctionNode
{
	public $string1 = null;
	public $string2 = null;
	public function parse(\Doctrine\ORM\Query\Parser $parser)
	{
		$parser->match(Lexer::T_IDENTIFIER);
		$parser->match(Lexer::T_OPEN_PARENTHESIS);
		$this->string1 = $parser->ArithmeticPrimary();
		$parser->match(Lexer::T_COMMA);
		$this->string2 = $parser->ArithmeticPrimary();
		$parser->match(Lexer::T_CLOSE_PARENTHESIS);
	}
	public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
	{
		return 'STRCMP(' .
		    $this->string1->dispatch($sqlWalker) . ', ' .
		    $this->string2->dispatch($sqlWalker) .
		')';
	}
}