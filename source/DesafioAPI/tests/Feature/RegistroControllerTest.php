<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Registro;

class RegistroControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetById()
    {
        $registro = Registro::factory()->create();

        $response = $this->get("/registros/{$registro->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $registro->id,
                 ]);
    }

    public function testCreateRegistro()
    {
        $data = [
            'type' => 'sugestao',
            'message' => 'Esta é uma sugestão interessante.',
            'is_identified' => true,
            'whistleblower_name' => 'João',
            'whistleblower_birth' => '1990-05-15',
        ];

        $response = $this->post('/registros', $data);

        $response->assertStatus(201); 

        $this->assertDatabaseHas('registros', [
            'type' => 'sugestao',
            'message' => 'Esta é uma sugestão interessante.',
            'is_identified' => 1,
            'whistleblower_name' => 'João',
            'whistleblower_birth' => '1990-05-15',
        ]);
    }

    public function testFailCreateRegistro()
    {
        $data = [
            'type' => 'invalid_type', 
            'message' => '',
            'is_identified' => true,
            'whistleblower_name' => 'Maria',
            'whistleblower_birth' => '2000-01-01',
        ];

        $response = $this->post('/registros', $data);

        $response->assertStatus(400);
        
        $this->assertDatabaseMissing('registros', [
            'whistleblower_name' => 'Maria',
            'whistleblower_birth' => '2000-01-01',
        ]);
    }

    public function testSuccessUpdateRegistro()
    {
        $registro = Registro::factory()->create();

        $data = [
            'type' => 'sugestao',
        ];

        $response = $this->put('/registros/' . $registro->id, $data);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('registros', [
            'id' => $registro->id,
            'type' => 'sugestao',
        ]);
    }

    public function testFailUpdateRegistro()
    {
        $registro = Registro::factory()->create();

        $data = [
            'type' => 'invalid_type',
        ];

        $response = $this->put('/registros/' . $registro->id, $data);

        $response->assertStatus(400);
        
        $this->assertDatabaseHas('registros', [
            'id' => $registro->id,
            'type' => $registro->type,
        ]);
    }

    public function testSoftDeleteRegistro()
    {
        $registro = Registro::factory()->create();

        $response = $this->delete('/registros/' . $registro->id);

        $response->assertStatus(200);

        $this->assertDatabaseHas('registros', [
            'id' => $registro->id,
            'deleted' => 1,
        ]);
    }
}
