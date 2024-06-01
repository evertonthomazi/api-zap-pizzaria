<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index(){
        return view('admin.customer.index');
    }

    public function getCustomers(){
        $customer = Customer::orderBy('id');
        return DataTables::of($customer)->make(true);
    }

    public function create(){
        return view('admin.customer.create');
    }

    
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'jid' => 'required|string|max:255',
            'zipcode' => 'required|string|max:255',
            'public_place' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'number' => 'required|string|max:255',
        ]);

        Customer::create($data);

        return redirect()->route('admin.customer.index')->with('success', 'Cliente criado com sucesso!');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'jid' => 'required|string|max:255',
        'zipcode' => 'required|string|max:255',
        'public_place' => 'required|string|max:255',
        'complement' => 'nullable|string|max:255',
        'neighborhood' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'number' => 'required|string|max:255',
    ]);

    try {
        $customer = Customer::findOrFail($id);
        $customer->update($validatedData);

        return redirect()->route('admin.customer.index')->with('success', 'Cliente atualizado com sucesso!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors($e->getMessage())->withInput();
    }
}

    public function destroy()
    {
        
        $customer = Customer::findOrFail($_POST['id_survey_deleta']);
        $customer->delete();

        return redirect()->route('admin.customer.index')->with('success', 'Cliente excluído com sucesso!');
    }
    
}
