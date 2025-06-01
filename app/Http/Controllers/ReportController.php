<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reportable_type' => 'required|string',
            'reportable_id' => 'required|integer',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        $report = Report::create([
            'user_id' => auth()->id(),
            'reportable_type' => $request->reportable_type,
            'reportable_id' => $request->reportable_id,
            'reason' => $request->reason,
            'description' => $request->description
        ]);

        return back()->with('success', 'Жалоба успешно отправлена');
    }

    public function index()
    {
        $reports = Report::with(['user', 'reportable'])
            ->latest()
            ->paginate(20);

        return view('reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

    public function resolve(Report $report)
    {
        $report->resolve(request('resolution_notes'));
        return back()->with('success', 'Жалоба разрешена');
    }

    public function reject(Report $report)
    {
        $report->reject(request('resolution_notes'));
        return back()->with('success', 'Жалоба отклонена');
    }
} 