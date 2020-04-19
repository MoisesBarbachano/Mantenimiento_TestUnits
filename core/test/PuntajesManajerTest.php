<?php
use PHPUnit\Framework\TestCase;
require_once("./core/php/PuntajesManajer.php");
class PuntajesManajerTest extends TestCase
{
    private $PuntajesManajer;
    private $dbManager;

    private function setupMockito(){
        $this->PuntajesManajer = PuntajesManajer::getInstance();
        $this->dbManager = Mockery::mock(DatabaseManager::class);
        $this->dbManager->shouldReceive('close')->andReturn(null);
        $this->dbManager->shouldReceive('insertQuery')->once()->with("")->andReturn(false);
        $this->PuntajesManajer->setDBManager($this->dbManager);
    }

    public function testGetAllPuntajeForUsuarioAndMateriaAndDificultadPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_usuario='1' AND id_materia='1' AND dificultad='dificil'")
            ->once()
            ->andReturn(
            array(
                json_encode('
                    {
                        id: "1",
                        id_materia: "1",
                        fecha: "2020-02-15 06:02:01",
                        dificultad: "dificil,
                        puntaje: "0",
                        parejas_encontradas: "0"
                    }
                ')
            )
        );

        $id = '1';
        $id_materia = '1';
        $dificultad= 'dificil';

        $this->assertIsString($id);
        $this->assertIsString($id_materia);
        $this->assertIsString($dificultad);

        $response = $this->PuntajesManajer->getAllPuntajeForUsuarioAndMateriaAndDificultad($id,$id_materia,$dificultad);
        $this->assertJson(  
            $response
         );
        $expectedResponse = json_encode(
            array(
                json_encode('
                    {
                        id: "1",
                        id_materia: "1",
                        fecha: "2020-02-15 06:02:01",
                        dificultad: "dificil,
                        puntaje: "0",
                        parejas_encontradas: "0"
                    }
                ')
            )
        );
        $this->assertEquals(  
            $expectedResponse, $response
        );

       
        $resultado = new stdClass();
        $resultado->num_rows = 2;
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_usuario='1' AND id_materia='2' AND dificultad='dificil'")
            ->once()
            ->andReturn($resultado);
        $response2 = $this->PuntajesManajer->getAllPuntajeForUsuarioAndMateriaAndDificultad("1","2","dificil");
        $this->assertIsNumeric(  
            $response2
         );
        $expectedResponse1 = 2;
        $this->assertEquals(  
            $expectedResponse1, $response2
        );
        
    }

    public function testGetAllPuntajeForUsuarioAndMateriaAndDificultadNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_usuario='' AND id_materia='' AND dificultad=''")
            ->once()
            ->andReturn(null);

        $id = null;
        $id_materia = null;
        $dificultad= null;
    
        $this->assertNull($id);
        $this->assertNull($id_materia);
        $this->assertNull($dificultad);
    
        $this->assertEquals(  
            "tabla materia varia",  $this->PuntajesManajer->getAllPuntajeForUsuarioAndMateriaAndDificultad($id,$id_materia,$dificultad)
         );
    }

    public function testGetAllPuntajeForMateriaAndDificultadPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_materia='1' AND dificultad='dificil'")
            ->once()
            ->andReturn(
            array(
                json_encode('
                    {
                        id: "1",
                        id_materia: "1",
                        fecha: "2020-02-15 06:02:01",
                        dificultad: "dificil,
                        puntaje: "0",
                        parejas_encontradas: "0"
                    }
                ')
            )
        );

    
        $id_materia = '1';
        $dificultad= 'dificil';

        $this->assertIsString($id_materia);
        $this->assertIsString($dificultad);

        $response = $this->PuntajesManajer->getAllPuntajeForMateriaAndDificultad($id_materia,$dificultad);
        $this->assertJson(  
            $response
         );
        $expectedResponse = json_encode(
            array(
                json_encode('
                    {
                        id: "1",
                        id_materia: "1",
                        fecha: "2020-02-15 06:02:01",
                        dificultad: "dificil,
                        puntaje: "0",
                        parejas_encontradas: "0"
                    }
                ')
            )
        );
        $this->assertEquals(  
            $expectedResponse, $response
        );

        $resultado = new stdClass();
        $resultado->num_rows = 2;
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_materia='1' AND dificultad='facil'")
            ->once()
            ->andReturn($resultado);
        $response = $this->PuntajesManajer->getAllPuntajeForMateriaAndDificultad("1","facil");
        $this->assertIsNumeric(  
            $response
         );
        $expectedResponse1 = 2;
        $this->assertEquals(  
            $expectedResponse1, $response
        );
        
    }

    public function testGetAllPuntajeForMateriaAndDificultadNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_materia='' AND dificultad=''")
            ->once()
            ->andReturn(null);

        
        $id_materia = null;
        $dificultad= null;
    
        $this->assertNull($id_materia);
        $this->assertNull($dificultad);
    
        $this->assertEquals(  
            "tabla materia varia",  $this->PuntajesManajer->getAllPuntajeForMateriaAndDificultad($id_materia,$dificultad)
         );
    }

    public function testGetAllPuntajeForUsuarioAndMateriaPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_usuario='1' AND id_materia='2'")
            ->once()
            ->andReturn(
            array(
                json_encode('
                    {
                        id: "1",
                        id_materia: "2",
                        fecha: "2020-02-15 06:02:13",
                        dificultad: "dificil,
                        puntaje: "0",
                        parejas_encontradas: "0"
                    }
                ')
            )
        );

        $id = '1';
        $id_materia = '2';
        

        $this->assertIsString($id_materia);
        $this->assertIsString($id);

        $response = $this->PuntajesManajer->getAllPuntajeForUsuarioAndMateria($id,$id_materia);
        $this->assertJson(  
            $response
         );
        $expectedResponse = json_encode(
            array(
                json_encode('
                    {
                        id: "1",
                        id_materia: "2",
                        fecha: "2020-02-15 06:02:13",
                        dificultad: "dificil,
                        puntaje: "0",
                        parejas_encontradas: "0"
                    }
                ')
            )
        );
        $this->assertEquals(  
            $expectedResponse, $response
        );

        $resultado = new stdClass();
        $resultado->num_rows = 2;
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_usuario='1' AND id_materia='1'")
            ->once()
            ->andReturn($resultado);
        $response = $this->PuntajesManajer->getAllPuntajeForUsuarioAndMateria("1","1");
        $this->assertIsNumeric(  
            $response
         );
        $expectedResponse1 = 2;
        $this->assertEquals(  
            $expectedResponse1, $response
        );
    }

    public function testGetAllPuntajeForUsuarioAndMateriaNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_usuario='' AND id_materia=''")
            ->once()
            ->andReturn(null);

        
        $id= null;
        $id_materia = null;

        $this->assertNull($id_materia);
        $this->assertNull($id);
    
        $this->assertEquals(  
            "tabla materia varia",  $this->PuntajesManajer->getAllPuntajeForUsuarioAndMateria($id,$id_materia)
         );
    }

}
