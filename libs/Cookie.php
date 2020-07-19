<?php
namespace libs;
class Cookie {
	public static function set($name, $value, $expire) {
		setcookie ( $name, $value, $expire,"/" );
	}
	public static function get($name) {
		if (isset ( $_COOKIE [$name] ))
			return $_COOKIE [$name];
	}
	public static function delete($name) {
		if (isset ( $_COOKIE [$name] )){
			setcookie ( $name, "" , time()-3600,"/" );
		}
	}
}