<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Book;

class BookTest extends TestCase
{
    use DatabaseTransactions;

    private $books = [];
    private $accessToken;

    protected function setUp(): void {
        parent::setUp();

        $this->addBooks();
        $this->authenticate();
    }

    private function addBooks() {
        $this->books[0] = Book::create([
            'isbn' => '293842983648273',
            'title' => 'Iliad',
            'author' => 'Homer',
            'stock' => 12,
            'price' => 7.40
        ]);
        $this->books[0]->save();
        // Recuperamos el id de la base de datos
        $this->books[0] = $this->books[0]->fresh();

        $this->books[1] = Book::create([
            'isbn' => '9879287342342',
            'title' => 'Odyssey',
            'author' => 'Homer',
            'stock' => 8,
            'price' => 10.60
        ]);
        $this->books[1]->save();
        $this->books[1] = $this->books[1]->fresh();

        $this->books[2] = Book::create([
            'isbn' => '312312314235324',
            'title' => 'The Illuminati',
            'author' => 'Larry Burkett',
            'stock' => 22,
            'price' => 5.10
        ]);
        $this->books[2]->save();
        $this->books[2] = $this->books[2]->fresh();
    }

    private function authenticate() {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->postJson('/api/login', [
            'email' => 'joel@email.com',
            'password' => 'password'
        ]);

        //Testear la respuesta
        // $response->dumpHeaders();

        // $response->dumpSession();

        // $response->dump();

        // Obtener el body de la respuesta
        $this->accessToken = $response->getContent();
    }

    /*
    * @test
    */
    public function testGetBook() {
        $expectedResponse = [
            'book' => json_decode($this->books[1], true)
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken
        ])->json('GET', '/api/books/' . $this->books[1]->id);

        $response
            ->assertStatus(200)
            ->assertJson($expectedResponse);
    }

    /*
    * @test
    */
    public function testGetBooksByTitle() {
        $expectedResponse = [
            'books' => [
                json_decode($this->books[0], true),
                json_decode($this->books[2], true)
            ]
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken
        ])->json('GET', '/api/books/', ['title' => 'Il']);

        $response
            ->assertStatus(200)
            ->assertJson($expectedResponse);
    }

}
