<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class BookController extends Controller
{
    public function index(): Response
    {
        $books = [
            ['id' => 1, 'title' => 'Clean Code', 'author' => 'Robert C. Martin', 'summary' => 'A handbook of agile software craftsmanship.'],
            ['id' => 2, 'title' => 'Refactoring', 'author' => 'Martin Fowler', 'summary' => 'Improving the design of existing code.'],
            ['id' => 3, 'title' => 'The Pragmatic Programmer', 'author' => 'Andrew Hunt, David Thomas', 'summary' => 'Journey to mastery with pragmatic approaches.'],
        ];

        return Inertia::render('Books', [
            'books' => $books,
        ]);
    }
}
