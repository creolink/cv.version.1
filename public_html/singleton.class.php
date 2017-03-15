<?php
/**
 *
 * Obsluga singleton w obiektach
 *
 * @author Jakub Luczynski jakub.luczynski@gmail.com
 *
 * @version 2.0
 * @copyright (c) 2012 - 2013 CreoLink, http://www.creolink.pl/
 *
 */
?>
<?php
abstract class Singleton
{
	/*
	// Deklaracje pol klasy
	*/
	
	
	
	
	
	/*
	// Konstruktor i destruktor
	*/
	
	
	protected function __construct() {}
	
	protected function __destruct() {}
	
	
	/*
	// Metody prywatne, protected
	*/
	
	
	final protected function __clone()
	{
		trigger_error( 'Clone is not allowed.', E_USER_ERROR);
	}
	
	
	/*
	// Metody publiczne
	*/
	
	
	final public static function singleton() //$p_mParametr = NULL
	{
		static $_oInstance = NULL;

		return $_oInstance ?: $_oInstance = new static(); //$p_mParametr
	}
}
?>