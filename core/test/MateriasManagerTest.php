<?php
use PHPUnit\Framework\TestCase;
require_once("./core/php/MateriasManager.php");
final class MateriasManagerTest extends TestCase
{

    private $MateriasManager;
    private $dbManager;

    private function setupMockito(){
        //$this->MateriasManager = MateriasManager::getInstance();
        $this->dbManager = Mockery::mock(DatabaseManager::class); 
        $this->dbManager->shouldReceive('close')->andReturn(null);
        $this->dbManager->shouldReceive('insertQuery')->once()->with("")->andReturn(false);
        $this->MateriasManager->setDBManager($this->dbManager);
    }

    /**********PRUEBAS GETMATERIA () *****************
    **********(POSITIVA Y NEGATIVA)*******************/
    public function testGetMateriaPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM materias WHERE id='1'")
            ->once()
            ->andReturn(
            array(
                json_encode('
                    {
                        id: "1",
                        nombre: "Espanol"
                    }
                ')
            )
        );
        $id = 1;
        $nombre = "Espanol";
        $this->assertIsNumeric($id);
        $this->assertIsString($nombre);
        $response = $this->MateriasManager->getMateria($id);
        $this->assertJson(  
            $response
         );
        $expectedResponse = json_encode(
            array(
                json_encode('
                    {
                        id: "1",
                        nombre: "Espanol"
                    }
                ')
            )
        );
        $this->assertEquals(  
            $expectedResponse, $response
        );
        
        $resultado = new stdClass();
        $resultado->num_rows = 2;
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM materias WHERE id='2'")
        ->once()
        ->andReturn($resultado);
        $id1 = 2;
        $response = $this->MateriasManager->getMateria($id1);
        $this->assertIsNumeric(  
            $response
         );
        $expectedResponse1 = 2;
        $this->assertEquals(  
            $expectedResponse1, $response
        );
    }
    public function testGetMateriaNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM materias WHERE id=''")
            ->once()
            ->andReturn(null);
        $id = null;
        $this->assertNull($id);
        $response = $this->MateriasManager->getMateria($id);
        $this->assertEquals(  
            "Tabla de materias esta vacia", $response
        );
    }

    /********PRUEBAS SETMATERIA()**************
    *********POSITIVA Y NEGATIVA***************/
    public function testSetMateriaPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO materias (nombre) VALUES('Matematicas')")->andReturn('true');
        $name = 'Matematicas';
        $this->assertEquals( 
            'true', $this->MateriasManager->setMateria($name)
        );
    }
    public function testSetMateriaNegative(){
        $this->setupMockito();     
        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO materias (nombre) VALUES('Matematicas')")->andReturn('');  
        $name = 'Matematicas';
        $this->assertIsString($name);
        $this->assertEquals(  
            '', $this->MateriasManager->setMateria($name)
        );
    }


    /*****************PRUEBA METODO UPDATEMATERIA()**********
    ******************POSITIVA Y NEGATIVA********************/
    public function testUpdateMateriaPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("UPDATE materias set nombre = 'nombre' WHERE id=1")->andReturn('true');
        $name = 'nombre';
        $id = 1;

        $this->assertIsString($name);
        $this->assertIsNumeric($id);
        $this->assertEquals(  
            'true',$this->MateriasManager->updateMateria($id,$name)
        );
    }

    public function testUpdateMateriaNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("UPDATE materias set nombre = '' WHERE id=1")->andReturn(null);
        $name = null;
        $id = 1;

        $this->assertNull($name);
        $this->assertIsNumeric($id);
        $this->assertEquals(  
            '',$this->MateriasManager->updateMateria($id,$name)
        );
    }

    /***************PRUEBA METODO DELETEMATERIA()***** 
    ****************POSITIVA Y NEGATIVA***************/
    public function testDeleteMateriaPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM materias WHERE id = '1'")->andReturn('true');
        $id = 1;
        $this->assertIsNumeric($id);
        $this->assertEquals( 
            'true', $this->MateriasManager->deleteMateria($id)
        );
    }
    public function testDeleteMateriaNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM materias WHERE id = '0'")->andReturn('');
        $id = 0;
        $this->assertIsNumeric($id);
        $this->assertEquals( 
            "", $this->MateriasManager->deleteMateria($id)
        );
    }


    /********************PRUEBA METODO GETALLMATERIA()********
    *********************POSITIVA Y NEGATIVA.*****************/
    public function testGetAllMateriaPositive(){
        $this->setupMockito();
        $materia1 = array(
            "id" => 1,
            "nombre" => "Matematicas"
        );
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM materias")
            ->once()
            ->andReturn(
                array(
                    $materia1,
                    $materia1
                )
        );
        $response = $this->MateriasManager->getAllMateria();
        $this->assertJson(  
            $response
         );
        $materia1 = array(
            "id" => 1,
            "name" => "Matematicas"
        );
        $expectedResponse = json_encode(
            array(
                array(
                    $materia1,
                    $materia1
                )
            )
        );
        $this->assertEquals(  
            $expectedResponse, $response
        );
        
        $resultado = new stdClass();
        $resultado->num_rows = 2;
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM materias")
            ->once()
            ->andReturn($resultado);
        $response = $this->MateriasManager->getAllMateria();
        $this->assertIsNumeric(  
            $response
         );
        $expectedResponse1 = 2;
        $this->assertEquals(  
            $expectedResponse1, $response
        );
    }
    public function testGetAllMateriaNegative(){
        $this->setupMockito();
        $user1 = array(
            "id" => 1,
            "nombre" => "Moises",
        );
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM materias")
            ->once()
            ->andReturn(
                null
        );
        $response = $this->MateriasManager->getAllMateria();
        $this->assertIsString($response);
        $this->assertEquals(  
            '', $response
        );
    }
    
    protected function tearDown(): void {
        $this->dbManager->close();
    }
}
?>
