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

    public function testSetPuntajeNegative(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO puntajes (id_usuario,id_materia,fecha,dificultad,puntaje,parejas_encontradas) VALUES('0','0','','',0,0)")->andReturn('');
        $idUsuario = 0;
        $idMateria = 0;
        $fecha = null;
        $dificultad = null;
        $puntaje = 0;
        $foundPeers = 0;

        $this->assertIsNumeric($idUsuario);
        $this->assertIsNumeric($idMateria);
        $this->assertNull($fecha);
        $this->assertNull($dificultad);
        $this->assertIsNumeric($puntaje);
        $this->assertIsNumeric($foundPeers);

        $this->assertEquals(
            '', $this->PuntajesManajer->setPuntaje($idUsuario,$idMateria,$fecha,$dificultad,$puntaje,$foundPeers)
        );
    }

    public function testSetPuntajePositive(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO puntajes (id_usuario,id_materia,fecha,dificultad,puntaje,parejas_encontradas) VALUES('1','1','2020-04-17 07:38:45','Fácil',1,1)")->andReturn('true');
        $idUsuario = 1;
        $idMateria = 1;
        $fecha = '2020-04-17 07:38:45';
        $dificultad = 'Fácil';
        $puntaje = 1;
        $foundPeers = 1;

        $this->assertIsNumeric($idUsuario);
        $this->assertIsNumeric($idMateria);
        $this->assertIsString($fecha);
        $this->assertIsString($dificultad);
        $this->assertIsNumeric($puntaje);
        $this->assertIsNumeric($foundPeers);

        $this->assertEquals(
            'true', $this->PuntajesManajer->setPuntaje($idUsuario,$idMateria,$fecha,$dificultad,$puntaje,$foundPeers)
        );
    }

    public function testDeletePuntajeNegative(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM puntajes WHERE id_usuario = '0' AND id_materia = '0' AND fecha='' AND ''")->andReturn('');
        $idUsuario = 0;
        $idMateria = 0;
        $fecha = null;
        $dificultad = null;

        $this->assertIsNumeric($idUsuario);
        $this->assertIsNumeric($idMateria);
        $this->assertNull($fecha);
        $this->assertNull($dificultad);

        $this->assertEquals(
            '', $this->PuntajesManajer->deletePuntaje($idUsuario,$idMateria,$fecha,$dificultad)
        );
    }

    public function testDeletePuntajePositive(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM puntajes WHERE id_usuario = '1' AND id_materia = '1' AND fecha='2020-04-17 07:38:45' AND 'Fácil'")->andReturn('true');
        $idUsuario = 1;
        $idMateria = 1;
        $fecha = '2020-04-17 07:38:45';
        $dificultad = 'Fácil';

        $this->assertIsNumeric($idUsuario);
        $this->assertIsNumeric($idMateria);
        $this->assertIsString($fecha);
        $this->assertIsString($dificultad);

        $this->assertEquals(
            'true', $this->PuntajesManajer->deletePuntaje($idUsuario,$idMateria,$fecha,$dificultad)
        );
    }

    public function testGetAllPuntajeForUsuarioNegative(){
        $this->setupMockito();
        $id = 0;
        $user1 = array(
            "id" => 0,
            "nombre" => "Jose",
            "tipo" => 0,
            "clave" => "619"
        );
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_usuario='0'")
            ->once()
            ->andReturn(
                null
        );
        $response = $this->PuntajesManajer->getAllPuntajeForUsuario($id);
        $this->assertEquals(
            'tabla materia vacia', $response
        );
    }

    public function testGetAllPuntajeForUsuarioPositive(){
        $this->setupMockito();
        $id = 1;
        $user1 = array(
            "id" => 1,
            "nombre" => "Jose",
            "tipo" => 0,
            "clave" => "619"
        );
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_usuario='1'")
            ->once()
            ->andReturn(
                array(
                    $user1,
                    $user1
                )
        );
        $response = $this->PuntajesManajer->getAllPuntajeForUsuario($id);
        $this->assertJson(
            $response
         );
        $user1 = array(
            "id" => 1,
            "nombre" => "Jose",
            "tipo" => 0,
            "clave" => "619"
        );
        $expectedResponse = json_encode(
                array(
                    $user1,
                    $user1
                )
        );
        $this->assertEquals(
            $expectedResponse, $response
        );

        $resultado = new stdClass();
        $resultado->num_rows = 2;
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_usuario='1'")
            ->once()
            ->andReturn($resultado);
        $response = $this->PuntajesManajer->getAllPuntajeForUsuario($id);
        $this->assertIsNumeric(
            $response
         );
        $expectedResponse1 = 2;
        $this->assertEquals(
            $expectedResponse1, $response
        );
    }

    public function testGetAllPuntajeForMateriaNegative(){
      $this->setupMockito();
      $id = 0;
      $user1 = array(
          "id" => 0,
          "nombre" => "Jose",
          "tipo" => 0,
          "clave" => "619"
      );
      $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_materia='0'")
          ->once()
          ->andReturn(
              null
      );
      $response = $this->PuntajesManajer->getAllPuntajeForMateria($id);
      $this->assertEquals(
          'tabla materia varia', $response
      );
    }

    public function testGetAllPuntajeForMateriaPositive(){
      $this->setupMockito();
      $id = 1;
      $user1 = array(
          "id" => 1,
          "nombre" => "Jose",
          "tipo" => 0,
          "clave" => "619"
      );
      $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_materia='1'")
          ->once()
          ->andReturn(
              array(
                  $user1,
                  $user1
              )
      );
      $response = $this->PuntajesManajer->getAllPuntajeForMateria($id);
      $this->assertJson(
          $response
       );
      $user1 = array(
          "id" => 1,
          "nombre" => "Jose",
          "tipo" => 0,
          "clave" => "619"
      );
      $expectedResponse = json_encode(
              array(
                  $user1,
                  $user1
              )
      );
      $this->assertEquals(
          $expectedResponse, $response
      );

      $resultado = new stdClass();
      $resultado->num_rows = 2;
      $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM puntajes WHERE id_materia='1'")
          ->once()
          ->andReturn($resultado);
      $response = $this->PuntajesManajer->getAllPuntajeForMateria($id);
      $this->assertIsNumeric(
          $response
       );
      $expectedResponse1 = 2;
      $this->assertEquals(
          $expectedResponse1, $response
      );
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


      protected function tearDown(): void {
        $this->dbManager->close();
    }


}

?>
