<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PortfolioProject;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $projects = PortfolioProject::orderBy('sort_order', 'asc')->paginate(15);
        return view('admin.portfolios.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.portfolios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'location' => 'nullable|string|max:150',
            'partner_donor' => 'nullable|string|max:150',
            'period' => 'nullable|string|max:50',
            'summary' => 'required|string',
            'description' => 'nullable|string',
            'image_cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'document_pdf' => 'nullable|file|mimes:pdf|max:25600',
            'status' => 'required|in:ongoing,completed',
            'sort_order' => 'nullable|integer',
        ]);

        $coverPath = null;
        if ($request->hasFile('image_cover')) {
            $coverPath = $request->file('image_cover')->store('portfolios/covers', 'public');
        }

        $pdfPath = null;
        if ($request->hasFile('document_pdf')) {
            $pdfPath = $request->file('document_pdf')->store('portfolios/docs', 'public');
        }

        $slug = Str::slug($validated['project_title']) . '-' . Str::random(5);

        $project = PortfolioProject::create([
            'project_title' => $validated['project_title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'location' => $validated['location'],
            'partner_donor' => $validated['partner_donor'],
            'period' => $validated['period'],
            'summary' => $validated['summary'],
            'description' => $validated['description'],
            'image_cover_path' => $coverPath,
            'document_pdf_path' => $pdfPath,
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_PORTFOLIO_PROJECT',
            'module' => 'Portfolio',
            'details' => 'Menambahkan proyek portfolio: ' . $project->project_title,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.portfolios.index')->with('success', 'Proyek portfolio berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $project = PortfolioProject::findOrFail($id);
        return view('admin.portfolios.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $project = PortfolioProject::findOrFail($id);

        $validated = $request->validate([
            'project_title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'location' => 'nullable|string|max:150',
            'partner_donor' => 'nullable|string|max:150',
            'period' => 'nullable|string|max:50',
            'summary' => 'required|string',
            'description' => 'nullable|string',
            'image_cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'document_pdf' => 'nullable|file|mimes:pdf|max:25600',
            'status' => 'required|in:ongoing,completed',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image_cover')) {
            if ($project->image_cover_path) {
                Storage::disk('public')->delete($project->image_cover_path);
            }
            $project->image_cover_path = $request->file('image_cover')->store('portfolios/covers', 'public');
        }

        if ($request->hasFile('document_pdf')) {
            if ($project->document_pdf_path) {
                Storage::disk('public')->delete($project->document_pdf_path);
            }
            $project->document_pdf_path = $request->file('document_pdf')->store('portfolios/docs', 'public');
        }

        $project->update([
            'project_title' => $validated['project_title'],
            'category' => $validated['category'],
            'location' => $validated['location'],
            'partner_donor' => $validated['partner_donor'],
            'period' => $validated['period'],
            'summary' => $validated['summary'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_PORTFOLIO_PROJECT',
            'module' => 'Portfolio',
            'details' => 'Memperbarui proyek portfolio: ' . $project->project_title,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.portfolios.index')->with('success', 'Proyek portfolio berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $project = PortfolioProject::findOrFail($id);

        if ($project->image_cover_path) {
            Storage::disk('public')->delete($project->image_cover_path);
        }
        if ($project->document_pdf_path) {
            Storage::disk('public')->delete($project->document_pdf_path);
        }

        $title = $project->project_title;
        $project->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_PORTFOLIO_PROJECT',
            'module' => 'Portfolio',
            'details' => 'Menghapus proyek portfolio: ' . $title,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.portfolios.index')->with('success', 'Proyek portfolio berhasil dihapus!');
    }
}
