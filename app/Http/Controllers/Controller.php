<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use App\Services\ServiceProvider;
use Session;
use View;
use Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const FORM_TPL = 'partials.form';
    const LIST_TPL = 'partials.listing';

    protected $model = null;
    
    public function __construct() {
        View::share('modelName', $this->model);
    }
    
    /**
     * flash message
     *
     * @param string $msg
     * @param string $type
     * @param array $data
     * @return void
     */
    public function flashMsg($msg, $type = 'info', $fields = []) {
        // set message
        Session::flash('message', __($msg));
        Session::flash('message_type', $type);
    }
    

    /**
     * Triggers GET /[model]/statusUpdate/{status}/{id}
     *
     * @param Request $request
     */
    public function statusUpdate(Request $request, $status, $id) {
        ServiceProvider::{$this->model}()->statusUpdate($status, $id);
        $this->flashMsg(sprintf("%s status successfully updated", ucwords($this->model)), 'success');
        return redirect(sprintf('/%s', $this->model));
    }
    
    
    
    /**
     * Triggers GET /[model]
     *
     * @param Request $request
     */
    public function listing(Request $request) {
        $tpl_vars = [];

        $service = ServiceProvider::{$this->model}();
        $tpl_vars[Str::plural($this->model)] = $service->listing($request->except('_token'));
        $tpl_vars['isSearchable'] = $service->isSearchable();
        
        return view(sprintf(self::LIST_TPL, $this->model), $tpl_vars);
    }

    
    /**
     * Triggers GET /[model]/detail/{id}
     *
     * @param Request $request
     */
    public function detail(Request $request, $id) {
        $tpl_vars = [];
        if (method_exists($this, 'prepTPLVars')) {
            $tpl_vars = $this->prepTPLVars();
        }
        
        $tpl_vars[$this->model] = ServiceProvider::{$this->model}()->getById($id);
        $tpl_vars['page'] = 'detail';

        return view(sprintf(self::FORM_TPL, $this->model), $tpl_vars);
    }
    
    /**
     * Triggers GET /[model]/create
     *
     * @param Request $request
     */
    public function createForm(Request $request) {
        $tpl_vars = [];
        
        if (method_exists($this, 'prepTPLVars')) {
            $tpl_vars = $this->prepTPLVars();
        }

        $tpl_vars[$this->model] = ServiceProvider::{$this->model}();
        $tpl_vars['page'] = 'create';
        
        return view(self::FORM_TPL, $tpl_vars);
    }
    

    /**
     * Triggers POST /[model]/create
     *
     * @param Request $request
     */
    public function create(Request $request) {
        ServiceProvider::{$this->model}($request->except('_token'))->createNew();
        
        $this->flashMsg(sprintf('%s created successfully', ucwords($this->model)), 'success');
        return redirect(sprintf('/%s', $this->model));
    }
    
    /**
     * Triggers GET /[model]/update
     *
     * @param Request $request
     * @param Integer $id
     */
    public function updateForm(Request $request, $id) {
        $tpl_vars = [];
        
        if (method_exists($this, 'prepTPLVars')) {
            $tpl_vars = $this->prepTPLVars();
        }

        // get model by id
        $tpl_vars[$this->model] = ServiceProvider::{$this->model}()->getById($id);
        $tpl_vars['page'] = 'update';
        
        return view(sprintf(self::FORM_TPL, $this->model), $tpl_vars);
    }
    
    /**
     * Triggers POST /[model]/update/{id}
     *
     * @param Request $request
     */
    public function update(Request $request, $id) {
        ServiceProvider::{$this->model}($request->except('_token'))->updateObject($id);

        $this->flashMsg(sprintf('%s updated successfully', ucwords($this->model)), 'success');
        return redirect(sprintf("/%s/update/$id", $this->model));
    }

}
