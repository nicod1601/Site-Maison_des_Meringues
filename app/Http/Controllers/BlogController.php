<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogReaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Affichage
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $posts = BlogPost::with(['author', 'reactions'])
            ->latest()
            ->get()
            ->map(fn($post) => $this->formatPost($post));

        return view('blog', [
            'posts'   => $posts,
            'isAdmin' => $this->isAdmin(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD (admin seulement)
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): JsonResponse
    {
        $this->requireAdmin();

        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'category'  => 'required|string|max:100',
            'content'   => 'required|string',
            'emoji'     => 'required|string|max:10',
            'image_url' => 'nullable|url|max:500',
        ]);

        $post = BlogPost::create([
            ...$data,
            'user_id' => Auth::id(),
        ]);

        $post->load(['author', 'reactions']);

        return response()->json([
            'success' => true,
            'post'    => $this->formatPost($post),
        ]);
    }

    public function update(Request $request, BlogPost $blogPost): JsonResponse
    {
        $this->requireAdmin();

        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'category'  => 'required|string|max:100',
            'content'   => 'required|string',
            'emoji'     => 'required|string|max:10',
            'image_url' => 'nullable|url|max:500',
        ]);

        $blogPost->update($data);
        $blogPost->load(['author', 'reactions']);

        return response()->json([
            'success' => true,
            'post'    => $this->formatPost($blogPost),
        ]);
    }

    public function destroy(BlogPost $blogPost): JsonResponse
    {
        $this->requireAdmin();

        $blogPost->delete();

        return response()->json(['success' => true]);
    }

    /*
    |--------------------------------------------------------------------------
    | Réactions (utilisateurs connectés)
    |--------------------------------------------------------------------------
    */

    public function react(Request $request, BlogPost $blogPost): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Connexion requise.'], 401);
        }

        $request->validate([
            'type' => 'required|in:like,dislike',
        ]);

        $type     = $request->type;
        $userId   = Auth::id();
        $existing = BlogReaction::where('blog_post_id', $blogPost->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            if ($existing->type === $type) {
                // Même vote → annuler
                $existing->delete();
                $userReaction = null;
            } else {
                // Vote différent → changer
                $existing->update(['type' => $type]);
                $userReaction = $type;
            }
        } else {
            BlogReaction::create([
                'blog_post_id' => $blogPost->id,
                'user_id'      => $userId,
                'type'         => $type,
            ]);
            $userReaction = $type;
        }

        $blogPost->load('reactions');

        return response()->json([
            'success'      => true,
            'likes'        => $blogPost->likes_count,
            'dislikes'     => $blogPost->dislikes_count,
            'userReaction' => $userReaction,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    private function requireAdmin(): void
    {
        abort_unless($this->isAdmin(), 403, 'Accès réservé à l\'administrateur.');
    }

    private function formatPost(BlogPost $post): array
    {
        return [
            'id'           => $post->id,
            'title'        => $post->title,
            'category'     => $post->category,
            'content'      => $post->content,
            'emoji'        => $post->emoji,
            'image_url'    => $post->image_url,
            'author'       => $post->author->name ?? 'Admin',
            'date'         => $post->created_at->translatedFormat('j M Y'),
            'likes'        => $post->likes_count,
            'dislikes'     => $post->dislikes_count,
            'userReaction' => $post->user_reaction,
        ];
    }
}
