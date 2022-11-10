<?php

/**
 * Validator Trait 
 * ------------------------------------------------------------------------------------------------------
 *
 * This trait contains the base functionality of every validator model. This trait also requires and uses
 * the following properties and constants to the classes that uses it:
 * 
 * - CONSTANTS:
 * -- MODEL_NAME = (string) model name in singular form
 * -- SERVICE_MODEL = (class) model/service class for this specific validator
 * -- VALID_STATUS = (array) used to validate statuses on updateStatus validation
 *
 * ------------------------------------------------------------------------------------------------------
 *
 */

namespace App\Validators;

use App\Exceptions\MsgException;
use App\Exceptions\FieldException;

use Illuminate\Database\Eloquent\Model;

trait Base {
    /**
     * validates if model exists by it primary key
     *
     * @param integer primary key
     * @return object model
     */
    public static function getById($id) {
        $model = call_user_func([self::SERVICE_MODEL, 'find'], [$id]);
        $model = $model->first();

        if (empty($model)) {
            $msg = sprintf("Cannot find %s", self::MODEL_NAME);
            $path = sprintf("/%s", self::MODEL_NAME);
            
            throw new MsgException($msg, $path);
        }

        return $model;
    }
    
    /**
     * validate update model status
     *
     * @param char status
     * @param int primary key
     *
     * @return object model
     */
    public static function statusUpdate($status, $id) {
        if (!$status || !in_array($status, self::VALID_STATUS)) {
            $msg = sprintf("Invalid %s status", self::MODEL_NAME);
            $path = sprintf("/%s", self::MODEL_NAME);
            
            throw new MsgException($msg, $path);
        }

        if (method_exists(self::class, 'statusUpdatePost')) {
            /** update status validation hook
             *
             * @param char status
             * @param integer id
             */
            self::updateStatusPost($status, $id);
        }
        
        return self::getById($id);
                
    }

    /**
     * validates object before updating it to db
     *
     * @param model object
     * @param int primary key
     *
     * @return model object
     */
    public static function updateObject(Model $object, $primaryKey) {
        $current = self::getById($primaryKey);

        $class = new Static();
        if (method_exists(self::class, 'updatePre')) {
            /** before update validation hook
             *
             * @param model object 
             */
            self::updatePre($object, $class);
        }
        
        // call common validatioon
        self::commonValidation($object, $class);
        

        if (method_exists(self::class, 'updatePost')) {
            /** after update validation hook
             *
             * @param model object 
             */
            self::updatePost($object, $class);
        }
        
        return $current;
    }

    /**
     * validates object before create
     *
     * @param model object
     * @return object
     */
    public static function createNew(Model $object) {
        $errors = [];
        
        // parent class
        $class = new static();

        if (method_exists(self::class, 'createPre')) {
            /** before create validation hook
             *
             * @param model object 
             */
            self::createPre($object, $class);
        }

        // call common validatioon
        self::commonValidation($object, $class);
        
        if (method_exists(self::class, 'createPost')) {
            /** after create validation hook
             *
             * @param model object 
             */
            self::createPost($object, $class);
        }

        return $object;
    }

    /**
     * validates required fields
     *
     * @param array required fields
     * @param Model object
     * @return void
     */
    public static function validateRequiredFields(array $requiredFields = [], Model $object) {
        $errors = [];
        // validate required fields
        foreach ($requiredFields as $field => $label) {
            // if field is array
            // PS: Only God can explain this code
            if (preg_match('/\[/', $field)) {
                // explode it
                $arrFields = explode('[', $field);

                // not set
                if (!$object->{$arrFields[0]}) {
                    $errors[$field] = "$label is required";
                    continue;
                }
                
                // get the base
                $base = $object->{$arrFields[0]};
                
                unset($arrFields[0]);
                // loop
                foreach ($arrFields as $k => $f) {
                    // clean field
                    $f = preg_replace('/\]$/', '', $f);
                    
                    if (!isset($base[$f]) || empty($base[$f])) {
                        $errors[$field] = "$label is required";
                        continue 2;
                    }

                    $base = $base[$f];
                }
                
                continue;
            }
            
            if (!$object->$field || !trim($object->$field)) {
                $errors[$field] = "$label is required";
            }
        }

        // throw FieldException
        if (!empty($errors)) {
            throw new FieldException(json_encode($errors));
        }
    }

    /**
     * validates enum fields
     *
     * @param array enum fields
     * @param Model object
     * @return void
     */
    public static function validateEnumFields(array $enums = [], Model $object) {
        $errors = [];
        
        foreach ($enums as $field => $value) {
            if (!isset($value[0])
                || !trim($value[0])
                || !isset($value[1])
                || !is_array($value[1])
            ) {
                // improperly configured validator class
                throw new \Exception("An error occured");
            }

            list($label, $possibleValue) = $value;
            
            // if field is array
            // PS: Only God can explain this code
            if (preg_match('/\[/', $field)) {
                // explode it
                $arrFields = explode('[', $field);
                
                // get the base
                $base = $object->{$arrFields[0]};
                unset($arrFields[0]);
                foreach ($arrFields as $k => $f) {
                    // clean field
                    $f = preg_replace('/\]$/', '', $f);
                    $base = $base[$f];
                }
                
                if (!isset($possibleValue[$base])) {
                    $errors[$field] = "{$label} is invalid";
                }
                
                continue;
            }
            
            if (!isset($possibleValue[$object->$field])) {
                $errors[$field] = "{$label} is invalid";
            }
        }

        if (!empty($errors)) {
            throw new FieldException(json_encode($errors));
        }

    }
    
    /**
     * validates should exists
     *
     * @param array fields
     * @param Model object
     * @return void
     */
    public static function validateShouldExist(array $fields = [], Model $object) {
        
        foreach ($fields as $field => $value) {
            if (!isset($value[0])
                || !isset($value[1])
                || !class_exists($value[1])
            ) {
                // improperly configured validator class
                throw new \Exception("An error occured");
            }


            list($label, $modelClass) = $value;
            
            // try to get model by id
            try {

                // if field is array
                // PS: Only God can explain this code
                if (preg_match('/\[/', $field)) {
                    // explode it
                    $arrFields = explode('[', $field);
                
                    // get the base
                    $base = $object->{$arrFields[0]};
                    unset($arrFields[0]);
                    foreach ($arrFields as $k => $f) {
                        // clean field
                        $f = preg_replace('/\]$/', '', $f);
                        $base = $base[$f];
                    }

                    $idValue = $base;
                } else {
                    $idValue = $object->$field;
                }
                
                $modelClass::getById($idValue);
            } catch (MsgException $e) {
                throw new FieldException(json_encode([$field => "$label is invalid"]));
            }
        }
    }

    private static function commonValidation (&$object, $class) {
        // check if requiredFields property is set
        $required = [];
        if (property_exists(static::class, 'requiredFields')
            && is_array($class->requiredFields)
        ) {
            $required = $class->requiredFields;
        }

        // validate required fields
        self::validateRequiredFields($required, $object);
        
        $enums = [];
        if (property_exists($class, 'enums') && is_array($class->enums)) {
            $enums = $class->enums;
        }

        // enums validation
        self::validateEnumFields($enums, $object);

        $shouldExist = [];
        if (property_exists(static::class, 'modelShouldExist') && is_array($class->modelShouldExist)) {
            $shouldExist = $class->modelShouldExist;
        }
        
        // should exist model validation
        self::validateShouldExist($shouldExist, $object);
    }
}
