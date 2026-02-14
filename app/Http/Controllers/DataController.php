<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DataController extends Controller{
    public function updateRele(Request $request){
        $user = Auth::user();
        $releName = $request->rele_name;
        $dataRecord = Data::where('user_id', $user->id)->latest()->first();
        if ($dataRecord) {
            $releData = $dataRecord->rele_data;
            $releData[$releName] = $releData[$releName] == 1 ? 0 : 1;
            $dataRecord->update(['rele_data' => $releData]);
            return response()->json(['success' => true, 'new_status' => $releData[$releName]]);
        }
        return response()->json(['success' => false], 404);
    }
    public function getHistory(Request $request){
        $user = Auth::user();
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);
        $history = Data::where('user_id', $user->id)
                       ->whereBetween('created_at', [$start, $end])
                       ->orderBy('created_at', 'asc') 
                       ->get();
        $labels = []; 
        $datasets = [];

        foreach ($history as $record) {
            $labels[] = $record->created_at->format('d.m H:i'); 
            foreach ($record->sensors_data as $key => $value) {
                $datasets[$key][] = $value;
            }
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets
        ]);
    }

    public function storeHardwareData(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'sensors_data' => 'required|array',
            'rele_data' => 'nullable|array'
        ]);
        $data = \App\Models\Data::create([
            'user_id' => $request->user_id,
            'sensors_data' => $request->sensors_data,
            'rele_data' => $request->rele_data ?? [],
        ]);
        return response()->json(['status' => 'success', 'id' => $data->id], 201);
    }
}