<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransparencyReport;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;

class TransparencyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = TransparencyReport::orderBy('report_year', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('report_year', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $reports = $query->paginate(15)->withQueryString();
        return view('admin.transparency.index', compact('reports', 'search'));
    }

    public function create()
    {
        return view('admin.transparency.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'report_year' => 'required|integer|min:2015|max:2035',
            'category' => 'required|string|max:100',
            'summary' => 'nullable|string',
            'file_pdf' => 'required|file|mimes:pdf|max:30720',
            'is_active' => 'nullable|boolean',
        ]);

        $file = $request->file('file_pdf');
        $fileSize = round($file->getSize() / 1048576, 1) . ' MB';
        $pdfPath = $file->store('transparency_reports', 'public');

        $report = TransparencyReport::create([
            'title' => $validated['title'],
            'report_year' => $validated['report_year'],
            'category' => $validated['category'],
            'summary' => $validated['summary'] ?? null,
            'file_pdf_path' => $pdfPath,
            'file_size' => $fileSize,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_TRANSPARENCY_REPORT',
            'module' => 'Transparansi',
            'details' => 'Mengunggah laporan transparansi: ' . $report->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.transparency.index')->with('success', 'Laporan transparansi berhasil diunggah!');
    }

    public function edit($id)
    {
        $report = TransparencyReport::findOrFail($id);
        return view('admin.transparency.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $report = TransparencyReport::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'report_year' => 'required|integer|min:2015|max:2035',
            'category' => 'required|string|max:100',
            'summary' => 'nullable|string',
            'file_pdf' => 'nullable|file|mimes:pdf|max:30720',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('file_pdf')) {
            if ($report->file_pdf_path) {
                Storage::disk('public')->delete($report->file_pdf_path);
            }
            $file = $request->file('file_pdf');
            $report->file_size = round($file->getSize() / 1048576, 1) . ' MB';
            $report->file_pdf_path = $file->store('transparency_reports', 'public');
        }

        $report->update([
            'title' => $validated['title'],
            'report_year' => $validated['report_year'],
            'category' => $validated['category'],
            'summary' => $validated['summary'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_TRANSPARENCY_REPORT',
            'module' => 'Transparansi',
            'details' => 'Memperbarui dokumen transparansi: ' . $report->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.transparency.index')->with('success', 'Dokumen transparansi berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $report = TransparencyReport::findOrFail($id);

        if ($report->file_pdf_path) {
            Storage::disk('public')->delete($report->file_pdf_path);
        }

        $title = $report->title;
        $report->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_TRANSPARENCY_REPORT',
            'module' => 'Transparansi',
            'details' => 'Menghapus dokumen transparansi: ' . $title,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.transparency.index')->with('success', 'Dokumen transparansi berhasil dihapus!');
    }
}
