<?php

try
{
	try { 
		throw new Exception("Error Processing Request");
	} catch(Exception $e) {
		var_dump($e->getMessage());
	};
	throw new Exception("root Error Processing Request");
} catch(Exception $e) {
	var_dump($e->getMessage());
}