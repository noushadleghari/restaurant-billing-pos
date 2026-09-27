<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::search($request->get('q'))
            ->withCount('orders')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json(['html' => view('customers.partials._table', compact('customers'))->render()]);
        }

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'customer' => $customer]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer added.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->route('customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Customer removed.');
    }

    /**
     * AJAX: search-as-you-type customer lookup used inside the POS billing screen.
     */
    public function search(Request $request)
    {
        $customers = Customer::search($request->get('q'))->limit(10)->get();
        return response()->json(['customers' => $customers]);
    }
}
