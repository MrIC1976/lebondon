<?php

namespace App\Service;

class MyService
{
	/**
	* Retourne "true" si le premier argument fait plus de 10 caractères
	*
	* @param string $argument
	* @return bool
	*/
	public function myFunction($argument) {
		return strlen($text) > 10;
	}
}
