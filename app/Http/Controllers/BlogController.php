<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogReaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'title'      => 'required|string|max:255',
            'category'   => 'required|string|max:100',
            'content'    => 'required|string',
            'emoji'      => 'required|string|max:10',
            // Image : soit un lien, soit un fichier, mais pas les deux obligatoires
            'image_url'  => 'nullable|url|max:500',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ]);

        $imagePath = $this->handleImageUpload($request);

        $post = BlogPost::create([
            'title'      => $data['title'],
            'category'   => $data['category'],
            'content'    => $data['content'],
            'emoji'      => $data['emoji'],
            'image_url'  => $imagePath ? null : ($data['image_url'] ?? null),
            'image_path' => $imagePath,
            'user_id'    => Auth::id(),
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
            'title'      => 'required|string|max:255',
            'category'   => 'required|string|max:100',
            'content'    => 'required|string',
            'emoji'      => 'required|string|max:10',
            'image_url'  => 'nullable|url|max:500',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ]);

        // Si un nouveau fichier est uploadé, supprimer l'ancien
        $newImagePath = $this->handleImageUpload($request);

        if ($newImagePath && $blogPost->image_path) {
            $this->deleteImageFile($blogPost->image_path);
        }

        $blogPost->update([
            'title'      => $data['title'],
            'category'   => $data['category'],
            'content'    => $data['content'],
            'emoji'      => $data['emoji'],
            // Si nouveau fichier → priorité fichier, on vide l'URL
            // Sinon on garde ce qui était défini
            'image_url'  => $newImagePath ? null : ($data['image_url'] ?? null),
            'image_path' => $newImagePath ?? $blogPost->image_path,
        ]);

        $blogPost->load(['author', 'reactions']);

        return response()->json([
            'success' => true,
            'post'    => $this->formatPost($blogPost),
        ]);
    }

    public function destroy(BlogPost $blogPost): JsonResponse
    {
        $this->requireAdmin();

        // Supprimer le fichier physique si présent
        if ($blogPost->image_path) {
            $this->deleteImageFile($blogPost->image_path);
        }

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
                $existing->delete();
                $userReaction = null;
            } else {
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

    /**
     * Gère l'upload du fichier image.
     * Retourne le chemin relatif public (ex: fichier/image/publication/xxx.jpg)
     * ou null si aucun fichier n'a été envoyé.
     */
    private function handleImageUpload(Request $request): ?string
    {
        if (!$request->hasFile('image_file') || !$request->file('image_file')->isValid()) {
            return null;
        }

        $file     = $request->file('image_file');
        $filename = uniqid('pub_') . '.' . $file->getClientOriginalExtension();

        // Stockage dans public/fichier/image/publication/
        $file->move(public_path('fichier/image/publication'), $filename);

        return 'fichier/image/publication/' . $filename;
    }

    /**
     * Supprime un fichier image du dossier public.
     */
    private function deleteImageFile(string $relativePath): void
    {
        $fullPath = public_path($relativePath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
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
            'image_path'   => $post->image_path,
            // URL finale prête à l'emploi dans le Blade
            'image_display'=> $post->image_display_url,
            'author'       => $post->author->name ?? 'Admin',
            'date'         => $post->created_at->translatedFormat('j M Y'),
            'likes'        => $post->likes_count,
            'dislikes'     => $post->dislikes_count,
            'userReaction' => $post->user_reaction,
        ];
    }
}
