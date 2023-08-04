<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Resources\V1\CustomerResource;
use App\Models\Customer;
use App\Filters\V1\CustomerFilter;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Melakukan filter terhadap input user
        // Contoh: http://127.0.0.1:8000/api/v1/customers?state[eq]=Kentucky&postalCode[gt]=4000
        // Maka hasilnya adalah
        // [[state, =, Kentucky], [postal_code, >, 4000]]

        $filter = new CustomerFilter();
        $queryItems = $filter->transform($request);

        // Jika user menambahkan query includeInvoices
        // maka, akan ditambahkan relationship invoices

        $includeInvoices = $request->query("includeInvoices");

        // Jika tidak ada filter maka tampilkan data tanpa filter
        // Selain itu, tampilkan data hasil filter

        $customers = Customer::where($queryItems);

        if ($includeInvoices) {
            $customers = $customers->with("invoices");
        }

        return new CustomerCollection($customers->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer, Request $request)
    {
        $includeInvoices = $request->query("includeInvoices");

        if ($includeInvoices) {
            return new CustomerResource($customer->loadMissing("invoices"));
        }

        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
