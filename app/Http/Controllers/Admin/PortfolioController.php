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
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = PortfolioProject::orderBy('sort_order', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('project_title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('partner_donor', 'like', "%{$search}%")
                  ->orWhere('period', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $projects = $query->paginate(15)->withQueryString();
        return view('admin.portfolios.index', compact('projects', 'search'));
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
            'image_cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'document_pdf' => 'nullable|file|mimes:pdf|max:25600',
            'status' => 'required|in:ongoing,completed',
            'sort_order' => 'nullable|integer',
        ]);

        $coverPath = null;
        if ($request->hasFile('image_cover')) {
            $coverPath = $this->compressAndSaveCover($request->file('image_cover'));
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
            'location' => $validated['location'] ?? null,
            'partner_donor' => $validated['partner_donor'] ?? null,
            'period' => $validated['period'] ?? null,
            'summary' => $validated['summary'],
            'description' => $validated['description'] ?? null,
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
            'image_cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'document_pdf' => 'nullable|file|mimes:pdf|max:25600',
            'status' => 'required|in:ongoing,completed',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image_cover')) {
            if ($project->image_cover_path) {
                Storage::disk('public')->delete($project->image_cover_path);
            }
            $project->image_cover_path = $this->compressAndSaveCover($request->file('image_cover'));
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
            'location' => $validated['location'] ?? null,
            'partner_donor' => $validated['partner_donor'] ?? null,
            'period' => $validated['period'] ?? null,
            'summary' => $validated['summary'],
            'description' => $validated['description'] ?? null,
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

    /**
     * Kompres dan simpan gambar sampul proyek portfolio
     * Resize proporsional (maks lebar 1200px) & konversi WebP/JPEG ringan.
     */
    protected function compressAndSaveCover($file, $maxWidth = 1200, $maxHeight = 800, $quality = 82): string
    {
        $destinationDir = storage_path('app/public/portfolios/covers');
        if (!file_exists($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        if (!extension_loaded('gd') || !function_exists('imagecreatetruecolor')) {
            return $file->store('portfolios/covers', 'public');
        }

        $sourcePath = $file->getRealPath();
        $mime = $file->getMimeType();

        $sourceImage = null;
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $sourceImage = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = @imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    $sourceImage = @imagecreatefromwebp($sourcePath);
                }
                break;
        }

        if (!$sourceImage) {
            return $file->store('portfolios/covers', 'public');
        }

        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight, 1.0);
        $targetWidth = (int) round($origWidth * $ratio);
        $targetHeight = (int) round($origHeight * $ratio);

        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        imagealphablending($targetImage, false);
        imagesavealpha($targetImage, true);
        $transparent = imagecolorallocatealpha($targetImage, 255, 255, 255, 127);
        imagefilledrectangle($targetImage, 0, 0, $targetWidth, $targetHeight, $transparent);

        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0, 0, 0, 0,
            $targetWidth,
            $targetHeight,
            $origWidth,
            $origHeight
        );

        if (function_exists('imagewebp')) {
            $filename = uniqid('cover_', true) . '.webp';
            $targetFile = $destinationDir . '/' . $filename;
            imagewebp($targetImage, $targetFile, $quality);
        } else {
            $filename = uniqid('cover_', true) . '.jpg';
            $targetFile = $destinationDir . '/' . $filename;
            imagejpeg($targetImage, $targetFile, $quality);
        }

        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        return 'portfolios/covers/' . $filename;
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
