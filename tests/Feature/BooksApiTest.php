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
        $this->getJson(route('books.index')) // Enviamos header Accept->application/json 
            ->assertJsonFragment([
            'title' => $books[0]->title
            ])->assertJsonFragment([
                'title' => $books[1]->title
            ]);
        // ? Podemos verificar cada libro creado con assertJsonFragment


    }

    /** @test */
    function can_get_one_book(){
        $book = Book::factory()->create();

        // ? Probando ruta show
        // dd(route('books.show', $book));
        $this->getJson(route('books.show', $book))->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test */
    function can_create_books(){
        // ? Debemos crear un test de regresión para probar la validación
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        // Envíamos los datos
        $this->postJson(route('books.store'), [
            'title' => 'My new book'
        ])->assertJsonFragment([
            'title' => 'My new book'
        ]);

        // Podemos luego revisar en la base de datos
        $this->assertDatabaseHas('books', [
            'title' => 'My new book'
        ]);
    }

    /** @test */
    function can_update_books(){
        // Debemos tener creado un libro
        $book = Book::factory()->create();

        // ? De la misma forma hago test de regresión para la validación
        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        // Envíamos los datos
        $this->patchJson(route('books.update', $book), 
            ['title' => 'Edited book'])
            ->assertJsonFragment([
                'title' => 'Edited book'
            ]);

        // Podemos luego revisar en la base de datos
        $this->assertDatabaseHas('books', [
            'title' => 'Edited book'
        ]);
    }

    // ? Volvimo a colocar este método al final después de encontrar como hacer Refresh database en https://laracasts.com/discuss/channels/testing/refreshdatabase-trait-doesnt-refresh-database
    /** @test */
    function can_delete_books(){
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }

}
