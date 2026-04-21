<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, string $locale, string $article): RedirectResponse
    {
        $articleModel = Article::where('id', $article)
            ->orWhere('slug', $article)
            ->firstOrFail();

        $data = $request->validate([
            'content'    => ['required', 'string', 'min:3', 'max:2000'],
            'guest_name' => ['nullable', 'string', 'max:255'],
            'guest_email'=> ['nullable', 'email', 'max:255'],
            'parent_id'  => ['nullable', 'exists:comments,id'],
        ]);

        $data['article_id']  = $articleModel->id;
        $data['user_id']     = auth()->id();
        $data['is_approved'] = true;

        Comment::create($data);

        return back()->with('success', 'Votre commentaire a été publié.');
    }
}
