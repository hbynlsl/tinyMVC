<?php
namespace {%namespace%}\Controllers;

use App\Models\{%className%};
use hbynlsl\Controller as BaseController;
use hbynlsl\Request;

class {%className%}Controller extends BaseController
{
    protected $fields = {%fields%};

    public function index()
    {
        $this->assign('rows', json({%className%}::all()));
        $this->display();
    }

    public function create()
    {
        $this->display();
    }

    public function store()
    {
        $row = new {%className%};
        foreach ($this->fields as $field) {
            $row->$field = Request::post($field);
        }
        if ($row->save()) {
            echo 'ok';
        } else {
            echo 'error';
        }
    }

    public function show($id)
    {
        $this->assign('row', json({%className%}::find($id)));
        $this->display();
    }

    public function edit($id)
    {
        $this->assign('row', json({%className%}::find($id)));
        $this->display();
    }

    public function update($id)
    {
        $row = {%className%}::find($id);
        foreach ($this->fields as $field) {
            $row->$field = Request::put($field);
        }
        if ($row->save()) {
            echo 'ok';
        } else {
            echo 'error';
        }
    }

    public function destroy($id)
    {
        $row = {%className%}::find($id);
        // 删除之
        if ($r = $row->destroy()) {
            echo 'ok';
        } else {
            echo 'error';
        }
    }

    public function all()
    {
        echo json({%className%}::all());
    }
}