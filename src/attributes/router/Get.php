<?php
namespace nx\attributes\router;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS| Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Get{
	public function __construct($Uri, $Action=null, $Params=[]){

	}
}