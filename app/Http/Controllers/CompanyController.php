<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\CompanyRequest;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Company::latest()->paginate(15);
        //dd($result);
        return view('company.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $c = new Company();
        $c->name = $request->input('name');
        $c->address = $request->input('address');
        $c->phone = $request->input('phone');
        $c->email = $request->input('email');
        $c->status = 1;
        $c->activity = $request->input('activity');
        $c->activity = $request->input('activity');
        $c->slug = $request->input('slug');
        $c->save();

        flash(trans_choice('words.Company',1).' '.trans('words.HasAdded'));

        return redirect()->back();  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        
        $users = $company->users;
        //dd($users->count());
        return view('company.show', compact('company', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $company->update($request->all());

        flash()->success(trans_choice('words.Company',1).' '.trans('words.HasUpdated'));
        return redirect()->route('companies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        flash()->success(trans_choice('words.Company',1).' '.trans('words.HasEliminated'));
        return back();
    }
}
