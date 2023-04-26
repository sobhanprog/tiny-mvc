<?php


namespace App\Http\Controllers;

class HomeController
{
    public function index()
    {
        echo "this is page index";
    }

    public function show($id)
    {
        echo "this is page show with prameter" . " " . $id;
    }

    public function create()
    {
        echo "this is page create";
    }

    public function store($req)
    {
        echo "store method in HomeController";
    }

    public function edit($id)
    {
        echo "edit method in HomeController";
    }

    public function update($id)
    {
        echo "update method in HomeController";
    }

    public function destroy($id)
    {
        echo "destroy method in HomeController";
    }

}