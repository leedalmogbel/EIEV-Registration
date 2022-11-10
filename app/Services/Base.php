<?php
/**
 * Service Trait 
 * -------------------------------------------------------------------------------------------------
 *
 * This trait contains the base functionality of every service model. This trait also requires
 * the following properties and constants to the classes that uses it:
 * 
 * - CONSTANTS:
 * -- MODEL_VALIDATOR = (class) validator class for the service model that uses this trait
 * -- PAGINATION_ENTRY = (integer) used in listing method for pagination
 *
 * - PROPERTIES: 
 * -- 
 * ---------------------------------------------------------------------------------------------------
 *
 */

namespace App\Services;

use Illuminate\Support\Facades\Schema;

trait Base {

    /**
     * status update
     *
     * @param char status
     * @param integer object id
     *
     * @return Model object
     */
    public function statusUpdate($status, $id) {
        $object = call_user_func([self::MODEL_VALIDATOR, 'statusUpdate'], $status, $id);
        $object->status = $status;
        
        // call before actual status update
        if (method_exists($this, 'statusUpdatePre')) {
            $this->statusUpdatePre($object);
        }
        
        $object = $object->save();
        
        // call after actual status update
        if (method_exists($this, 'statusUpdatePost')) {
            $this->statusUpdatePost($object);
        }

        return $object;
    }
    

    /**
     * paginated listing
     *
     * @return collection
     */
    public function listing($request) {
        $object = self::orderBy('updated_at', 'DESC');

        // searching
        if (
            property_exists($this, 'searchables')
            && isset($request['q'])
            && trim($request['q'])
        ) {
            $searchables = $this->searchables;
            $object->where(function($query) use ($searchables, $request) {
                foreach ($searchables as $field) {
                    $query->orWhere($field, 'like', "%{$request['q']}%");
                }
            });
        }

        if (
            isset(session()->get('role')->role)
            && session()->get('role')->role == 'user'
            && Schema::hasColumn($this->getTable(), 'user_id')
        ) {
            $object = $object->where('user_id', session()->get('user')->user_id);
        }

        // custom logic for horse
        if (
            $this->getTable() == 'horses'
            && isset(session()->get('role')->role)
            && session()->get('role')->role == 'user'
        ) {
            $object = $object
                    ->join('owners', 'horses.owner_id', '=', 'owners.owner_id')
                    ->where('user_id',  session()->get('user')->user_id)
                    ->select('horses.*');
        }

        return $object->paginate(self::PAGINATION_ENTRY);
    }

    /**
     * get model by id with validation
     *
     * @param integer primary key
     * @return model
     */
    public function getById($id) {
        return call_user_func([self::MODEL_VALIDATOR, 'getById'], [$id]);
    }

    /**
     * create new object
     *
     * @return model object
     */
    public function createNew() {
        // validate
        call_user_func([self::MODEL_VALIDATOR, 'createNew'], $this);

        // call method before actual create
        if (method_exists($this, 'createPre')) {
            $this->createPre();
        }

        $this->save();

        // call method after actual create
        if (method_exists($this, 'createPost')) {
            $this->createPost();
        }

        return $this;
    }
    

    /**
     * update object
     *
     * @param integer primary key
     *
     * @return model object
     */
    public function updateObject($primaryKey) {
        // validate
        $object = call_user_func([self::MODEL_VALIDATOR, 'updateObject'], $this, $primaryKey);

        foreach ($this->toArray() as $field => $value) {
            $object->$field = $value;
        }

        // call method before actual update
        if (method_exists($this, 'updatePre')) {
            $this->updatePre($object);
        }
        
        $object->save();
        
        // call method after actual update
        if (method_exists($this, 'updatePost')) {
            $this->updatePost($object);
        }

        return $object;
    }
    
    /**
     * get all object model
     *
     * @param bool
     * @return collection
     */
    public function getAll($activeOnly = false) {
        $object = $this;
        
        if (
            isset(session()->get('role')->role)
            && session()->get('role')->role == 'user'
            && Schema::hasColumn($object->getTable(), 'user_id')
        ) {
            $object = $object->where('user_id', session()->get('user')->user_id);
        }
        
		if (!$activeOnly) {
			return $object->get();
		}
        
		return $object->where('status', 'A')
			->get();
    }

    /**
     * check if this service model is searchable
     *
     * @return bool
     */
    public function isSearchable() {
        if (
            property_exists($this, 'searchables')
            && is_array($this->searchables)
            && !empty($this->searchables)
        ) {
            return true;
        }

        return false;
    }
}
