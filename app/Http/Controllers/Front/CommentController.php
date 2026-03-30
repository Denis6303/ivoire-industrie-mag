<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Article $article): RedirectResponse
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'min:3'],
            'guest_name' => ['nullable', 'string', 'max:255'],
            'guest_email' => ['nullable', 'email', 'max:255'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        $data['article_id'] = $article->id;
        $data['user_id'] = auth()->id();
        $data['is_approved'] = auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin', 'editor'], true);

        Comment::create($data);

        return back()->with('success', 'Commentaire envoyé.');
    }
}
