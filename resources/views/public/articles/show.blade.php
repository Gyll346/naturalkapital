@extends('layouts.app')

@section('title', $article->title . ' - Yayasan Natural Kapital Indonesia')

@section('content')
    <div style="max-width: 860px; margin: 40px auto 80px; padding: 0 24px;">
        <span style="display: inline-block; background: #e8f5ed; color: #0F5132; font-size: 12px; font-weight: 700; padding: 4px 14px; border-radius: 50px; text-transform: uppercase; margin-bottom: 16px;">
            {{ $article->category->category_name ?? 'Publikasi' }}
        </span>

        <h1 style="font-family: 'Montserrat', sans-serif; font-size: clamp(24px, 4vw, 36px); font-weight: 800; color: #1b2e23; line-height: 1.3; margin-bottom: 20px;">
            {{ $article->title }}
        </h1>

        <div style="display: flex; gap: 20px; font-size: 13px; color: #627b6d; border-bottom: 1px solid #e1ebe5; padding-bottom: 20px; margin-bottom: 30px;">
            <span>✍️ Ditulis oleh: <strong>{{ $article->author->name ?? 'Tim YNKI' }}</strong></span>
            <span>📅 {{ $article->published_at ? $article->published_at->format('d F Y') : '-' }}</span>
            <span>👁️ {{ $article->views_count }} Pembaca</span>
        </div>

        @if ($article->featured_image_path)
            <img src="/storage/{{ $article->featured_image_path }}" alt="{{ $article->title }}" style="width: 100%; max-height: 480px; object-fit: cover; border-radius: 16px; margin-bottom: 30px;">
        @endif

        @if ($article->attachment_pdf_path)
            <div style="background: #eef6f1; border: 1.5px solid #c2e2ce; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
                <div>
                    <h3 style="font-family: 'Montserrat', sans-serif; font-size: 15px; font-weight: 700; color: #0F5132; margin-bottom: 4px;">
                        📄 Dokumen Publikasi Riset Resmi (PDF)
                    </h3>
                    <p style="font-size: 12.5px; color: #5a7364; margin: 0;">Unduh dokumen penelitian lengkap untuk referensi akademik & kebijakan.</p>
                </div>
                <a href="/storage/{{ $article->attachment_pdf_path }}" download target="_blank" style="background: #0F5132; color: #ffffff; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 13px; text-decoration: none; white-space: nowrap;">
                    ⬇️ Unduh Dokumen
                </a>
            </div>
        @endif

        <div style="font-size: 16px; line-height: 1.85; color: #2d4236;">
            {!! nl2br(e($article->content)) !!}
        </div>

        @if ($relatedArticles->count() > 0)
            <div style="margin-top: 60px; border-top: 1px solid #e1ebe5; padding-top: 40px;">
                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 800; color: #0F5132; margin-bottom: 24px;">
                    Publikasi Terkait Lainnya
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
                    @foreach ($relatedArticles as $rel)
                        <div style="background: #ffffff; border: 1px solid #e1ebe5; border-radius: 12px; padding: 18px;">
                            <h4 style="font-family: 'Montserrat', sans-serif; font-size: 14.5px; font-weight: 700; margin-bottom: 8px;">
                                <a href="{{ route('public.article.show', $rel->slug) }}" style="color: #1b2e23; text-decoration: none;">
                                    {{ $rel->title }}
                                </a>
                            </h4>
                            <span style="font-size: 11.5px; color: #8fa699;">📅 {{ $rel->published_at ? $rel->published_at->format('d M Y') : '-' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
