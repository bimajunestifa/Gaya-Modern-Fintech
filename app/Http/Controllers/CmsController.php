<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsArticle;
use App\Models\AuditLog;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CmsController extends Controller
{
    public function index()
    {
        $articles = CmsArticle::orderBy('published_at', 'desc')->paginate(10);
        $totalArticles = CmsArticle::count();
        $publishedCount = CmsArticle::where('is_published', true)->count();

        return view('cms.index', compact('articles', 'totalArticles', 'publishedCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'author' => 'nullable|string',
        ]);

        $slug = Str::slug($validated['title']) . '-' . rand(100, 999);

        $article = CmsArticle::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'content' => $validated['content'],
            'author' => $validated['author'] ?: 'Bank Communications',
            'is_published' => true,
            'published_at' => Carbon::now(),
        ]);

        AuditLog::create([
            'action' => 'PUBLISH_CMS',
            'user_name' => 'Bank Editor',
            'ip_address' => $request->ip(),
            'details' => "Menerbitkan pengumuman CMS baru: {$article->title}",
        ]);

        return back()->with('success', 'Pengumuman / Berita CMS berhasil diterbitkan!');
    }

    public function destroy($id)
    {
        $article = CmsArticle::findOrFail($id);
        $article->delete();

        return back()->with('success', 'Artikel berhasil dihapus.');
    }
}
