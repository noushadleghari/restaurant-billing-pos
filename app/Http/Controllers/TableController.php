<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTableRequest;
use App\Models\DiningTable;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * The tile/grid view used on the main billing screen: shows every table
     * as a tile (available / occupied) so staff can tap one and start billing.
     */
    public function index(Request $request)
    {
        $tables = DiningTable::where('is_active', true)
            ->search($request->get('q'))
            ->with('activeOrder.items')
            ->orderBy('name')
            ->get();

        if ($request->wantsJson()) {
            return response()->json(['html' => view('tables.partials._grid', compact('tables'))->render()]);
        }

        return view('tables.index', compact('tables'));
    }

    public function manage()
    {
        $tables = DiningTable::orderBy('name')->paginate(15);
        return view('tables.manage', compact('tables'));
    }

    public function store(StoreTableRequest $request)
    {
        DiningTable::create($request->validated());
        return back()->with('success', 'Table added.');
    }

    public function update(StoreTableRequest $request, DiningTable $table)
    {
        $table->update($request->validated());
        return back()->with('success', 'Table updated.');
    }

    public function destroy(DiningTable $table)
    {
        if ($table->status === 'occupied') {
            return back()->with('error', 'Cannot remove an occupied table.');
        }

        $table->delete();
        return back()->with('success', 'Table removed.');
    }
}
