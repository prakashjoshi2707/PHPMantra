<?php
namespace libs;

use libs\Validate;
use libs\Response;

class Validation
{
    private static $validationPassed=false;
    private static $validationError=array();

    public static function check($request, $condition)
    {
        // echo "<hr>Validate Request::";
        // var_dump($request);
        // echo "<hr>Validate Conditions::";
        // var_dump($condition);
        // echo "<hr>";
        foreach ($condition as $field => $rules) {
            // echo "field =>".$field;
            if ($field<>"file") {
                //echo $request->input[$field];
            }
            //echo "<br>";
            foreach ($rules as $rule => $rule_value) {
                // echo "Rule ".$rule;
                // echo "Value ".$rule_value;
                if ($field<>"file") {
                    $value=trim($request->input[$field]);
                } else {
                    $value=trim($request->file[$field]["name"]);
                }
                //echo "<br>";
                if ($rule==='required' && empty($value)) {
                    self::addError($field, "{$field} is required");
                } elseif (!empty($value)) {
                    switch ($rule) {
                            case 'min':
                                if (strlen($value)<$rule_value) {
                                    self::addError($field, "{$field} must be a {$rule_value} minimum character");
                                }
                            break;
                            case 'max':
                                if (strlen($value)>$rule_value) {
                                    self::addError($field, "{$field} must be {$rule_value} maximum character");
                                }
                                break;
                            case 'phone':
                                if (!Validate::isValidPhone($value)) {
                                    self::addError($field, "{$field} must be valid phone number");
                                }
                                break;
                            case 'email':
                                    if (!Validate::isValidEmail($value)) {
                                        self::addError($field, "{$field} must be valid email");
                                    }
                                    break;
                            case 'date':
                                    if (!Validate::isValidDate($value)) {
                                        self::addError($field, "{$field} must be valid date");
                                    }
                                    break;
                            case 'text':
                                if (!Validate::isValidText($value)) {
                                    self::addError($field, "{$field} must be valid text");
                                }
                                break;
                            case 'filetype':
                                if ($rule_value<>$request->file[$field]["type"]) {
                                    self::addError($field, "{$field} please upload proper type");
                                }
                                break;
                            case 'filesize':
                                if ($request->file[$field]["size"]>$rule_value) {
                                    self::addError($field, "{$field} please upload proper size");
                                }
                                break;
                            default:
                                ;
                            break;
                        }
                }
            }
        }
        if (empty(self::$validationError)) {
            self::$validationPassed=true;
        }
    }
      
    public static function addError($item, $error)
    {
        self::$validationError[$item]=$error;
    }
    public static function getErrors()
    {
        $response=new Response();
        $response->error = true;
        $response->message = self::$validationError;
        $response->code=201;
        return $response;
    }
    public static function isPassed()
    {
        return self::$validationPassed;
    }
}
