<?php

use Luracast\Restler\RestException;

class Demo 
{
	/**
	 * Method for adding two numbers
	 *
	 * This method takes two parameters and add them to obtain the result
	 *
	 * @param int $a First number
	 * @param int $b Second number
	 *
	 * @return int
	 *
	 */
	function getAdd($a, $b)
	{
		return $a + $b;
	}

	/**
	 * Example for Exceptions
	 *
	 * This method throws a REST Exception
	 *
	 * @return array
	 *
	 */
	function getNotFound()
	{
		if(true)
		{
			throw new RestException(404, 'I did not found what you were looking for');
		}
		else
		{
			return array("message" => "ok");
		}
	}

}