<?php
class RolesController extends AppController {


    public function index() {
        $this->set('roles', $this->paginate());
    }


    public function add() {

    }

    public function test(){

    }

    public function show(){

    }

    public function edit(){

    }
}