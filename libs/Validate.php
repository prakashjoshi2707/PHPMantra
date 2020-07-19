<?php
namespace libs;
class Validate {
	function __construct() {
	}
	public static function isEmpty($field) {
		if (isset ( $field ) && empty ( $field ))
			return true;
		else
			return false;
	}
	public static function isNotEmpty($field) {
		if (isset ( $field ) && ! empty ( $field ))
			return true;
		else
			return false;
	}
	public static function isValidPhone($field) {
		if (preg_match ( "/^[0-9]{10}$/", $field ))
			return true;
		else
			return false;
	}
	public static function isValidDate($field) {
		if (preg_match ( "/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/", $field ))
			return true;
		else
			return false;
	}
	public static function isValidText($field) {
		if (preg_match ( "/^[a-zA-Z ]+$/", $field ))
			return true;
		else
			return false;
	}
	public static function isValidRollNo($field) {
		if (preg_match ( "/^ct-[0-9]{2}-[a-zA-Z]{1,2}-[0-9]{2}$/i", $field ))
			return true;
		else
			return false;
	}
	public static function isValidEmail($field) {
		if (filter_var ( $field, FILTER_VALIDATE_EMAIL ))
			return true;
		else
			return false;
	}
	public static function isValidEmailReg($field) {
		if (preg_match ( "/^([a-zA-Z0-9.]+)@([a-zA-Z]+)\.([a-zA-Z]+)$/", $field ))
			return true;
		else
			return false;
	}
	public static function isValidOTP($field) {
		if (preg_match ( "/^[0-9]{5}$/", $field ))
			return true;
		else
			return false;
	}
	public static function isValidReceiptNo($field) {
		if (preg_match ( "/^[0-9]{5}$/", $field ))
			return true;
			else
				return false;
	}
	/*public static function isValidPasswd($field) {
		if (preg_match ( "/^.{6,10}$/", $field ))
			return true;
		else
			return false;
	}*/
	 public static function isValidPassword($field) {
		if (preg_match ( "/^[0-9]{6,10}$/", $field ))
			return true;
		else
			return false;
	}
	public static function isValidSelectOption($field) {
		if ($field == "hide")
			return false;
		else
			return true;
	}
}
