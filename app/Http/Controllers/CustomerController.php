<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return view("customer.index",compact("customers"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("customer.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validateCustomer($request);

        if(Customer::where('email', $request->email)->exists()){
            throw ValidationException::withMessages([
                'email'=> 'El correo ya se encuentra en uso'
            ]);
        }

        if (($request->number_id[0] != "V" || $request->number_id[0] != "E" || $request->number_id[0] != "J" )) {
            $pre_value = preg_replace("/[^0-9-.]/", "", $request->number_id);
            $number_id = "V".$pre_value;
        } else {
            $legal = $request->number_id[0];
            $pre_value = preg_replace("/[^0-9-.]/", "", $request->number_id);
            $number_id = $legal."".$pre_value;
        }        
        
        $phone = ($request->user()->phone);
        $phone = (($phone[0]) != "+") ? "+58".$request->user()->phone : $request->user()->phone;

        $customer = Customer::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $phone,
            'number_id' => $number_id,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        if($customer){
            return redirect()->route('customer.index')->with('success','registro con Exitoso');
        }else{
            return redirect()->back()->with('error','Se ha producido un error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::findOrFail($id);

        return view('customer.view', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::find($id);

        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validateCustomer($request);
        
        if(Customer::where('email', $request->email)->where('id', "<>", $id)->exists()){
            throw ValidationException::withMessages([
                'email'=> 'El correo ya se encuentra en uso'
            ]);
        }

        $customer = Customer::findOrFail($id);
        
        if (($request->number_id[0] != "V" || $request->number_id[0] != "E" || $request->number_id[0] != "J" )) {
            $pre_value = preg_replace("/[^0-9-.]/", "", $request->number_id);
            $number_id = "V".$pre_value;
        } else {
            $legal = $request->number_id[0];
            $pre_value = preg_replace("/[^0-9-.]/", "", $request->number_id);
            $number_id = $legal."".$pre_value;
        }        
        
        $phone = (($request->phone[0]) != "+") ? "+58".preg_replace("/[^0-9-.]/", "",$request->phone) : $request->phone;

        $status = $customer->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $phone,
            'number_id' => $number_id,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        if($status){
            return redirect()->route('customer.index')->with('success','Editado con Exitoso');
        }else{
            return redirect()->back()->with('error','Se ha producido un error');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::find($id);
        $name = $customer->name;
        $customer->delete();
        return redirect()->route('customer.index')->with('error','Se ha eliminado a '.$name );
    }


    /**
     * Validation
     */
    public function validateCustomer($request){
        $request->validate([  
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'number_id'  => ['required', 'string', 'max:255'],
            'address'  => ['required', 'string'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
         ]);
    }
}
