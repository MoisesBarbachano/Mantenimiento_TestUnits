<?php
use PHPUnit\Framework\TestCase;
require_once("./core/php/ParejasManager.php");
final class ParejasManagerTest extends TestCase
{

    private $ParejasManager;
    private $dbManager;

    private function setupMockito(){
        $this->ParejasManager = ParejasManager::getInstance();
        $this->dbManager = Mockery::mock(DatabaseManager::class);
        $this->dbManager->shouldReceive('close')->andReturn(null);
        $this->dbManager->shouldReceive('insertQuery')->once()->with("")->andReturn(false);
        $this->ParejasManager->setDBManager($this->dbManager);
    }

    public function testSetParejaPositive(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO parejas (concepto,descripcion,idmateria) VALUES('conceptotest','descripciontest','1')")->andReturn('true');
        $idmateria = 1;
        $concepto = 'conceptotest';
        $descripcion ='descripciontest';

        $this->assertIsNumeric($idmateria);
        $this->assertIsString($concepto);
        $this->assertIsString($descripcion);

        $this->assertEquals( 
            'true', $this->ParejasManager->setPareja($idmateria,$concepto,$descripcion)
        );
    }
    public function testSetParejaNegative(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO parejas (concepto,descripcion,idmateria) VALUES('','','0')")->andReturn('');
        $idmateria = 0;
        $concepto = null;
        $descripcion = null;

        $this->assertIsNumeric($idmateria);
        $this->assertNull($concepto);
        $this->assertNull($descripcion);

        $this->assertEquals(
            '', $this->ParejasManager->setPareja($idmateria,$concepto,$descripcion)
        );
    }
    public function testUpdateParejaPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("UPDATE parejas set idmateria = '1' , concepto = 'testconcepto' , descripcion = 'testdefinicion' WHERE id=1")->andReturn('true');
        $idmateria = 1;
        $concepto = 'testconcepto';
        $descripcion ='testdefinicion';
        $id = 1;

        $this->assertIsNumeric($idmateria);
        $this->assertIsString($concepto);
        $this->assertIsString($descripcion);
        $this->assertIsNumeric($id);

        $this->assertEquals(
            'true',$this->ParejasManager->updatePareja($id, $idmateria, $concepto, $descripcion)
        );
    }
    public function testUpdateParejaNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("UPDATE parejas set idmateria = '0' , concepto = '' , descripcion = '' WHERE id=0")->andReturn('');
        $idmateria = 0;
        $concepto = null;
        $descripcion = null;
        $id = 0;

        $this->assertIsNumeric($idmateria);
        $this->assertNull($concepto);
        $this->assertNull($descripcion);
        $this->assertIsNumeric($id);

        $this->assertEquals(
            '',$this->ParejasManager->updatePareja($id, $idmateria, $concepto, $descripcion)
        );
    }
    public function testGetParejaPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT concepto,descripcion, FROM parejas WHERE id='1' AND idmateria= '1'")
            ->once()
            ->andReturn(
                array(
                    json_encode('
                    {
                        concepto: "Quantum decoherence",
                        descripcion: "The loss of quantum coherence."
                    }
                ')
                )
            );
        $id = 1;
        $idmateria = '1';
        $this->assertIsNumeric($id);
        $this->assertIsNumeric($idmateria);
        $response = $this->ParejasManager->getPareja($idmateria,$id);
        $this->assertJson(
            $response
        );
        $expectedResponse = json_encode(
            array(
                json_encode('
                    {
                        concepto: "Quantum decoherence",
                        descripcion: "The loss of quantum coherence."
                    }
                ')
            )
        );
        $this->assertEquals(
            $expectedResponse, $response
        );

        $resultado = new stdClass();
        $resultado->num_rows = 1;
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT concepto,descripcion, FROM parejas WHERE id='2' AND idmateria= '1'")
            ->once()
            ->andReturn($resultado);
        $response = $this->ParejasManager->getPareja(1,2);
        $this->assertIsNumeric(
            $response
        );
        $expectedResponse1 = 1;
        $this->assertEquals(
            $expectedResponse1, $response
        );
    }
    public function testGetParejaNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT concepto,descripcion, FROM parejas WHERE id='' AND idmateria= ''")
            ->once()
            ->andReturn(null);
        $id = null;
        $id_materia = null;
        $this->assertNull($id);
        $this->assertNull($id_materia);
        $this->assertEquals(
            "tabla de parejas vacia", $this->ParejasManager->getPareja($id_materia,$id)
        );
    }
    public function testDeleteParejaPositive(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM parejas WHERE id='1' AND idmateria='1'")->andReturn('true');
        $id = 1;
        $idmateria = 1;
        $this->assertIsNumeric($id);
        $this->assertIsNumeric($idmateria);

        $this->assertEquals(
            'true', $this->ParejasManager->deletePareja($id, $idmateria)
        );
    }
    public function testDeleteParejaNegative(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM parejas WHERE id='0' AND idmateria='0'")->andReturn('');
        $id = 0;
        $idmateria = 0;
        $this->assertIsNumeric($id);
        $this->assertIsNumeric($idmateria);

        $this->assertEquals(
            '', $this->ParejasManager->deletePareja($id, $idmateria)
        );
    }
    public function testGetAllParejasPositive(){
        $this->setupMockito();
        $user1 = array(
            'id' => 1,
            'idmateria' => 1,
            'concepto' => "testconcepto",
            'descripcion' => "testdescripcion"
        );
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM parejas")
            ->once()
            ->andReturn(
                    array(
                        $user1,
                        $user1
                    )
            );
        $response = $this->ParejasManager->getAllParejas();
        $this->assertJson(
            $response
        );
        $user1 = array(
            "id" => 1,
            "idMatter" => 1,
            "concept" => "testconcepto",
            "definition" => "testdescripcion"
        );
        $expectedResponse = json_encode(
            array(
                array(
                    $user1,
                    $user1
                )
            )
        );
        $this->assertEquals(
            $expectedResponse, $response
        );

        $resultado = new stdClass();
        $resultado->num_rows = 2;
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM parejas")
            ->once()
            ->andReturn($resultado);
        $response = $this->ParejasManager->getAllParejas();
        $this->assertIsNumeric(
            $response
        );
        $expectedResponse1 = 2;
        $this->assertEquals(
            $expectedResponse1, $response
        );
    }
    public function testGetAllParejasNegative(){
        $this->setupMockito();
        $user1 = array(
            "id" => 1,
            "idMatter" => 1,
            "concept" => "testconcepto",
            "definition" => "testdescripcion"
        );
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM parejas")
            ->once()
            ->andReturn(
                null
            );
        $response = $this->ParejasManager->getAllParejas();
        $this->assertEquals(
            'tabla materia vacia', $response
        );
    }
    public function testGetParejasTheMateriaPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT concepto,descripcion FROM parejas WHERE idmateria = 1")
            ->once()
            ->andReturn(
                array(
                    json_encode('
                    {
                        id: "1",
                        idmateria: "1",
                        concepto: "conceptotest",
                        descripcion: "descripciontest"
                    }
                ')
                )
            );
        $idmateria = '1';
        $this->assertIsNumeric($idmateria);
        $response = $this->ParejasManager->getAllParejasTheMateria($idmateria);
        $this->assertJson(
            $response
        );
        $expectedResponse = json_encode(
            array(
                json_encode('
                    {
                        id: "1",
                        idmateria: "1",
                        concepto: "conceptotest",
                        descripcion: "descripciontest"
                    }
                ')
            )
        );
        $this->assertEquals(
            $expectedResponse, $response
        );

        $resultado = new stdClass();
        $resultado->num_rows = 2;
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT concepto,descripcion FROM parejas WHERE idmateria = 1")
            ->once()
            ->andReturn($resultado);
        $response = $this->ParejasManager->getAllParejasTheMateria($idmateria);
        $this->assertIsNumeric(
            $response
        );
        $expectedResponse1 = 2;
        $this->assertEquals(
            $expectedResponse1, $response
        );
    }
    public function testGetParejasTheMateriaNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT concepto,descripcion FROM parejas WHERE idmateria = 0")
            ->once()
            ->andReturn(null);
        $idmateria = 0;
        $this->assertIsNumeric($idmateria);
        $this->assertEquals(
            "", $this->ParejasManager->getAllParejasTheMateria($idmateria)
        );
    }
}
