<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LgosComponent;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;

class LgosController extends Controller
{
    public function index()
    {
        $components = LgosComponent::orderBy('sort_order', 'asc')->get();
        return view('admin.lgos.index', compact('components'));
    }

    public function create()
    {
        return view('admin.lgos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'component_name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:255',
            'description' => 'required|string',
            'document_pdf' => 'nullable|file|mimes:pdf|max:25600',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $pdfPath = null;
        if ($request->hasFile('document_pdf')) {
            $pdfPath = $request->file('document_pdf')->store('lgos_docs', 'public');
        }

        $component = LgosComponent::create([
            'component_name' => $validated['component_name'],
            'code' => $validated['code'] ?? null,
            'role' => $validated['role'] ?? null,
            'description' => $validated['description'],
            'document_pdf_path' => $pdfPath,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_LGOS_COMPONENT',
            'module' => 'LGOS',
            'details' => 'Menambahkan komponen LGOS: ' . $component->component_name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.lgos.index')->with('success', 'Komponen LGOS berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $component = LgosComponent::findOrFail($id);
        return view('admin.lgos.edit', compact('component'));
    }

    public function update(Request $request, $id)
    {
        $component = LgosComponent::findOrFail($id);

        $validated = $request->validate([
            'component_name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:255',
            'description' => 'required|string',
            'document_pdf' => 'nullable|file|mimes:pdf|max:25600',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('document_pdf')) {
            if ($component->document_pdf_path) {
                Storage::disk('public')->delete($component->document_pdf_path);
            }
            $component->document_pdf_path = $request->file('document_pdf')->store('lgos_docs', 'public');
        }

        $component->update([
            'component_name' => $validated['component_name'],
            'code' => $validated['code'] ?? null,
            'role' => $validated['role'] ?? null,
            'description' => $validated['description'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_LGOS_COMPONENT',
            'module' => 'LGOS',
            'details' => 'Memperbarui komponen LGOS: ' . $component->component_name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.lgos.index')->with('success', 'Komponen LGOS berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $component = LgosComponent::findOrFail($id);

        if ($component->document_pdf_path) {
            Storage::disk('public')->delete($component->document_pdf_path);
        }

        $name = $component->component_name;
        $component->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_LGOS_COMPONENT',
            'module' => 'LGOS',
            'details' => 'Menghapus komponen LGOS: ' . $name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.lgos.index')->with('success', 'Komponen LGOS berhasil dihapus!');
    }
}
