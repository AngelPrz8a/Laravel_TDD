<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepositoryRequest;
use App\Models\Repository;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("repositories.index",[
            "repositories"=>auth()->user()->repositories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("repositories.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RepositoryRequest $request)
    {
        $request->user()->repositories()->create($request->all());
        return redirect()->route("repositories.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Repository $repository)
    {
        $this->authorize("pass",$repository);

        return view("repositories.show",[
            "repository"=>$repository
        ]);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Repository $repository)
    {
        $this->authorize("pass",$repository);

        return view("repositories.edit",[
            "repository"=>$repository
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RepositoryRequest $request, Repository $repository)
    {
        $this->authorize("pass",$repository);

        $repository->update($request->all());
        return redirect()->route("repositories.edit",$repository);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Repository $repository)
    {
        $this->authorize("pass",$repository);

        $repository->delete();
        return redirect()->route("repositories.index");
    }
}
