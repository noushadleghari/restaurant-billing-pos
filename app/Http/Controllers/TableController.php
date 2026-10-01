<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;
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
        $table = DiningTable::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'table' => $table, 'message' => 'Table added.']);
        }

        return back()->with('success', 'Table added.');
    }

    public function update(UpdateTableRequest $request, DiningTable $table)
    {
        $table->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'table' => $table, 'message' => 'Table updated.']);
        }

        return back()->with('success', 'Table updated.');
    }

    public function destroy(Request $request, DiningTable $table)
    {
        if ($table->status === 'occupied') {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot remove an occupied table.'], 422);
            }
            return back()->with('error', 'Cannot remove an occupied table.');
        }

        $table->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Table removed.']);
        }

        return back()->with('success', 'Table removed.');
    }
}