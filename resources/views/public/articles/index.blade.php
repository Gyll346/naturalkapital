@extends('layouts.app')

@section('title', 'Pustaka & Publikasi Riset - Yayasan Natural Kapital Indonesia')

@section('content')
    <div style="background: linear-gradient(150deg, #092b1a 0%, #0F5132 100%); color: #ffffff; padding: 60px 24px; text-align: center;">
        <h1 style="font-family: 'Montserrat', 'Inter', Arial, sans-serif !important; font-size: 34px !important; font-weight: 400 !important; margin-bottom: 12px !important; letter-spacing: normal !important;">
            Pustaka & Publikasi Riset
        </h1>
        <p style="font-size: 16px; color: rgba(255,255,255,0.85); max-width: 680px; margin: 0 auto;">
            Kumpulan artikel, dokumen penelitian lanskap, panduan teknis, dan cerita perubahan dari lapangan.
        </p>
    </div>

    <div style="max-width: 1200px; margin: 40px auto 80px; padding: 0 24px;">
        <!-- Kategori Filter -->
        <div style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 20px; margin-bottom: 30px;">
            <a href="{{ route('public.article.index') }}" style="padding: 8px 18px; border-radius: 50px; font-size: 13px; font-weight: 700; text-decoration: none; {{ !$selectedCategory ? 'background: #0F5132; color: #ffffff;' : 'background: #e8f5ed; color: #0F5132;' }}">
                Semua Topik
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('public.article.index') }}?kategori={{ $cat->slug }}" style="padding: 8px 18px; border-radius: 50px; font-size: 13px; font-weight: 700; text-decoration: none; white-space: nowrap; {{ $selectedCategory == $cat->slug ? 'background: #0F5132; color: #ffffff;' : 'background: #e8f5ed; color: #0F5132;' }}">
                    {{ $cat->category_name }}
                </a>
            @endforeach
        </div>

        <!-- Grid Artikel -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
            @forelse ($articles as $article)
                <article style="background: #ffffff; border: 1px solid #e1ebe5; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                    @if ($article->featured_image_path)
                        <img src="/storage/{{ $article->featured_image_path }}" alt="{{ $article->title }}" style="width: 100%; height: 210px; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 210px; background: linear-gradient(135deg, #092b1a, #0F5132); display: flex; align-items: center; justify-content: center; color: #20c997; font-size: 32px;">
                            🌿
                        </div>
                    @endif
                    <div style="padding: 24px;">
                        <span style="font-size: 11px; font-weight: 700; color: #0F5132; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $article->category->category_name ?? 'Publikasi' }}
                        </span>
                        <h2 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; margin: 10px 0 12px; line-height: 1.4;">
                            <a href="{{ route('public.article.show', $article->slug) }}" style="color: #1b2e23; text-decoration: none;">
                                {{ $article->title }}
                            </a>
                        </h2>
                        <p style="font-size: 13.5px; color: #5a7364; line-height: 1.6; margin-bottom: 20px;">
                            {{ Str::limit($article->excerpt ?? strip_tags($article->content), 120) }}
                        </p>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #8fa699; border-top: 1px solid #edf2ef; padding-top: 14px;">
                            <span>📅 {{ $article->published_at ? $article->published_at->format('d M Y') : '-' }}</span>
                            <a href="{{ route('public.article.show', $article->slug) }}" style="color: #0F5132; font-weight: 700; text-decoration: none;">
                                Baca Selengkapnya &rarr;
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 60px; color: #627b6d;">
                    Belum ada artikel pada kategori ini.
                </div>
            @endforelse
        </div>

        <div style="margin-top: 40px;">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
