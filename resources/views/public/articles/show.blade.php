@extends('layouts.app')

@section('title', $article->title . ' - Yayasan Natural Kapital Indonesia')

@section('content')
    <div style="max-width: 880px; margin: 50px auto 90px; padding: 0 24px;">
        <div style="margin-bottom: 20px;">
            <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #117710; text-decoration: none; margin-bottom: 16px;">
                &larr; Kembali ke Halaman Sebelumnya
            </a>
            <div>
                <span style="display: inline-block; background: #e8f5ed; color: #0F5132; font-size: 12px; font-weight: 700; padding: 4px 14px; border-radius: 50px; text-transform: uppercase;">
                    {{ $article->category->category_name ?? 'Publikasi' }}
                </span>
            </div>
        </div>

        <h1 style="font-family: 'Montserrat', sans-serif; font-size: clamp(24px, 4vw, 36px); font-weight: 800; color: #0e241b; line-height: 1.3; margin-bottom: 20px;">
            {{ $article->title }}
        </h1>

        <div style="display: flex; flex-wrap: wrap; gap: 20px; font-size: 13px; color: #627b6d; border-bottom: 1px solid #e1ebe5; padding-bottom: 18px; margin-bottom: 30px;">
            <span>Penulis: <strong>{{ $article->author->name ?? 'Tim YNKI' }}</strong></span>
            <span>Tanggal: <strong>{{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : '-' }}</strong></span>
            <span>Dibaca: <strong>{{ $article->views_count }} kali</strong></span>
        </div>

        @if ($article->featured_image_path)
            <div style="margin-bottom: 32px; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.06); border: 1px solid #d2e8d1;">
                <img src="/storage/{{ $article->featured_image_path }}" alt="{{ $article->title }}" style="width: 100%; max-height: 480px; object-fit: cover;">
            </div>
        @endif

        @if ($article->attachment_pdf_path)
            <div style="background: #f4faf5; border: 1.5px solid #c2e2ce; border-radius: 14px; padding: 22px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 35px;">
                <div>
                    <h3 style="font-family: 'Montserrat', sans-serif; font-size: 15.5px; font-weight: 700; color: #0F5132; margin: 0 0 4px;">
                        Dokumen Publikasi Resmi (PDF)
                    </h3>
                    <p style="font-size: 13px; color: #5a7364; margin: 0;">Unduh dokumen lengkap untuk referensi riset, akademis, dan advokasi kebijakan.</p>
                </div>
                <a href="/storage/{{ $article->attachment_pdf_path }}" download target="_blank" style="background: #117710; color: #ffffff; padding: 10px 22px; border-radius: 8px; font-weight: 700; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Unduh Dokumen PDF
                </a>
            </div>
        @endif

        <div style="font-size: 16px; line-height: 1.85; color: #2d4236; margin-bottom: 50px;">
            {!! nl2br(e($article->content)) !!}
        </div>

        @if ($relatedArticles->count() > 0)
            <div style="margin-top: 60px; border-top: 1.5px solid #e1ebe5; padding-top: 40px;">
                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 800; color: #0F5132; margin-bottom: 24px;">
                    Publikasi Terkait Lainnya
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    @foreach ($relatedArticles as $rel)
                        <div style="background: #ffffff; border: 1px solid #d2e8d1; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <span style="background: #eaf6ea; color: #117710; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 50px; text-transform: uppercase;">
                                    {{ $rel->category->category_name ?? 'Publikasi' }}
                                </span>
                                <h4 style="font-family: 'Montserrat', sans-serif; font-size: 15px; font-weight: 700; margin: 10px 0 8px; line-height: 1.4;">
                                    <a href="{{ route('public.article.show', $rel->slug) }}" style="color: #0e241b; text-decoration: none;">
                                        {{ $rel->title }}
                                    </a>
                                </h4>
                            </div>
                            <div style="font-size: 12px; color: #7a9485; border-top: 1px solid #f0f4f1; padding-top: 10px; margin-top: 12px;">
                                {{ $rel->published_at ? $rel->published_at->format('d M Y') : '-' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
