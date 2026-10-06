<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;


use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use App\Http\Requests\BookEditRequest;
use App\Models\User;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;

class BookController extends Controller
{
    public function index(Request $request)
    {
       $genres = Genre::all();

       $books = Book::query()
        ->when($request->keyword, function ($query, $keyword) {
            $query->keywordSearch($keyword);
        })
        ->when($request->genre, function ($query, $genreId) {
            $query->genreSearch($genreId);
        })
        ->when($request->sort, function ($query, $sort) {
            if ($sort === 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'rating') {
                $query->withAvg('reviews', 'rating')
                      ->orderBy('reviews_avg_rating', 'desc');
            } elseif ($sort === 'title') {
                $query->orderBy('title', 'asc');
            }
        })
        ->with(['genres'])
        ->withAvg('reviews', 'rating')
        ->paginate(10)
        ->withQueryString();

        return view('books.index', compact('books', 'genres'));
    }

    public function create()
   {
        $genres = Genre::all();
       return view('books.create', compact('genres'));
   }


public function isbnSearch($isbn)
{
  // ハイフン・空白を削除
    $isbn = str_replace(['-', ' '], '', $isbn);

    // 13桁チェック
    if (!preg_match('/^\d{13}$/', $isbn)) {
        return response()->json([
            'error' => 'ISBNは13桁で入力してください。'
        ], 400);
    }

    try {
        $response = Http::retry(3, 2000)
            ->timeout(10)
            ->get(
                'https://www.googleapis.com/books/v1/volumes',
                [
                    // isbn: は使わない
                    'q' => $isbn,
                    'maxResults' => 10,
                    'key' => env('GOOGLE_BOOKS_API_KEY'),
                ]
            );

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Google Books APIへの接続に失敗しました。',
            ], 500);
        }

        $data = $response->json();

        if (empty($data['items'])) {
            return response()->json([
                'error' => '該当する書籍が見つかりませんでした。'
            ], 404);
        }

        // ISBNが一致する書籍を探す
        $volumeInfo = null;

        foreach ($data['items'] as $item) {
            $info = $item['volumeInfo'] ?? [];

            foreach ($info['industryIdentifiers'] ?? [] as $identifier) {
                if (
                    $identifier['type'] === 'ISBN_13' &&
                    $identifier['identifier'] === $isbn
                ) {
                    $volumeInfo = $info;
                    break 2;
                }
            }
        }

        if (!$volumeInfo) {
            return response()->json([
                'error' => 'ISBNが一致する書籍が見つかりませんでした。'
            ], 404);
        }

        // 著者
        $author = isset($volumeInfo['authors'])
            ? implode(', ', $volumeInfo['authors'])
            : '';

        // 発売日
        $publishedDate = $volumeInfo['publishedDate'] ?? '';

        if (preg_match('/^\d{4}$/', $publishedDate)) {
            $publishedDate .= '-01-01';
        } elseif (preg_match('/^\d{4}-\d{2}$/', $publishedDate)) {
            $publishedDate .= '-01';
        }

        return response()->json([
            'isbn' => $isbn,
            'title' => $volumeInfo['title'] ?? '',
            'author' => $author,
            'published_date' => $publishedDate,
            'description' => $volumeInfo['description'] ?? '',
            'image_url' => $volumeInfo['imageLinks']['thumbnail'] ?? '',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Google Books APIの取得中にエラーが発生しました。',
            'message' => $e->getMessage(),
        ], 500);
    }
}

    public function store(BookRequest $request)
   {
      $book = Book::create([
        'title' => $request->title,
        'author' => $request->author,
        'isbn' => $request->isbn,
        'published_date' => $request->published_date,
        'description' => $request->description,
        'user_id' => auth()->id(),
        'image_url' => $request->image_url,
    ]);

        $book->genres()->attach($request->genres);

        return redirect()->route('books.index');
   }

    public function show($id)
   {
        $book = Book::findOrFail($id);

        return view('books.show', compact('book'));
   }

    public function edit(Book $book){  
       $this->authorize('update', $book);
        $genres = Genre::all();
        
        return view('books.edit',compact('book','genres'));
    }

     public function update(BookEditRequest $request, Book $book)
    {
        $this->authorize('update', $book);

        $book->update($request->only([
          'title',
          'author',
          'isbn',
          'description',
          'image_url',
          'publish_date',
        ]));

         $book->genres()->sync($request->input('genres', []));
        return redirect()->route('books.index');

    }

    
    public function delete($id)
    {
        Book::findOrFail($id)->delete();
        return redirect()->route('books.index');
    }


}
