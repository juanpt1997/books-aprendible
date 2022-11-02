<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    // ?  Importante notación test para que funcione, si no, tocaría llamarlo test_can_get_all_books
    /** @test */
    function can_get_all_books(){
        $books = Book::factory(4)->create();

        // $this->get(route('books.index'))->dump();
        // ? Con lo de arriba también podemos visualizar el listado, es importante usar nombres de rutas como siempre
        $response = $this->getJson(route('books.index')); // Enviamos header Accept->application/json 

        // ? Podemos verificar cada libro creado
        $response->assertJsonFragment([
            'title' => $books[0]->title
        ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);


    }

    /** @test */
    function can_get_one_book(){
        $book = Book::factory()->create();

        // ? Probando ruta show
        // dd(route('books.show', $book));
        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }
}
