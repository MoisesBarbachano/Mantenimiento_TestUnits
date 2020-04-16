<?php
use PHPUnit\Framework\TestCase;
require_once("./core/php/userManager.php");
final class UserManagerTest extends TestCase
{

    private $UserManager;
    private $dbManager;

    private function setupMockito(){
        $this->UserManager = UserManager::getInstance();
        $this->dbManager = Mockery::mock(DatabaseManager::class);
        $this->dbManager->shouldReceive('close')->andReturn(null);
        $this->dbManager->shouldReceive('insertQuery')->once()->with("")->andReturn(false);
        $this->UserManager->setDBManager($this->dbManager);
    }

    public function testSetUserPositive(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO usuario (nombre, clave, tipo) VALUES('Moises','Password','0')")->andReturn('true');
        $name = 'Moises';
        $password = 'Password';
        $tipo = 0;

        $this->assertIsString($name);
        $this->assertIsString($password);
        $this->assertIsNumeric($tipo);

        $this->assertEquals( 
            'true', $this->UserManager->setUser($name,$password,$tipo)
        );
    }
    public function testSetUserNegative(){
        $this->setupMockito();
        
        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO usuario (nombre, clave, tipo) VALUES('','','0')")->andReturn('');  
        $name = null;
        $password = null;
        $tipo = 0;

        $this->assertNull($name);
        $this->assertNull($password);
        $this->assertIsNumeric($tipo);

        $this->assertEquals(  
            '', $this->UserManager->setUser($name, $password, 0)
        );
    }
    public function testUpdateUserPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("UPDATE usuario set nombre = 'Moises' , clave = 'cambioPassword' , tipo = '0' WHERE id=1")->andReturn('true');
        $name = 'Moises';
        $password = 'cambioPassword';
        $tipo = 0;
        $id = 1;

        $this->assertIsString($name);
        $this->assertIsString($password);
        $this->assertIsNumeric($tipo);
        $this->assertIsNumeric($id);

        $this->assertEquals(  
            'true',$this->UserManager->updateUser($id,$name,$password,$tipo)
        );
    }
    public function testUpdateUserNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("UPDATE usuario set nombre = '' , clave = '' , tipo = '0' WHERE id=1")->andReturn('');
        $name = null;
        $password = null;
        $tipo = 0;
        $id = 1;

        $this->assertNull($name);
        $this->assertNull($password);
        $this->assertIsNumeric($tipo);
        $this->assertIsNumeric($id);

        $this->assertEquals(  
            '',$this->UserManager->updateUser($id,$name,$password,$tipo)
        );
    }
    public function testGetUserPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE nombre='Moises' AND clave='password'")
            ->once()
            ->andReturn(
            array(
                json_encode('
                    {
                        id: "1",
                        name: "Moises"
                    }
                ')
            )
        );
        $name = 'Moises';
        $password = 'password';
        $this->assertIsString($name);
        $this->assertIsString($password);
        $response = $this->UserManager->getUser($name,$password);
        $this->assertJson(  
            $response
         );
        $expectedResponse = json_encode(
            array(
                json_encode('
                    {
                        id: "1",
                        name: "Moises"
                    }
                ')
            )
        );
        $this->assertEquals(  
            $expectedResponse, $response
        );
        
        $resultado = new stdClass();
        $resultado->num_rows = 2;
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE nombre='Arturo' AND clave='password123'")
            ->once()
            ->andReturn($resultado);
        $response = $this->UserManager->getUser("Arturo","password123");
        $this->assertIsNumeric(  
            $response
         );
        $expectedResponse1 = 2;
        $this->assertEquals(  
            $expectedResponse1, $response
        );
    }
    public function testGetUserNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE nombre='' AND clave=''")
            ->once()
            ->andReturn(null);
        $name = null;
        $password = null;
        $this->assertNull($name);
        $this->assertNull($password);
        $this->assertEquals(  
            "Tabla usuario vacia", $this->UserManager->getUser($name,$password)
         );
    }
    public function testGetUserByIdPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE id='1' ")
            ->once()
            ->andReturn(
            array(
                json_encode('
                    {
                        id: "1",
                        name: "Moises"
                    }
                ')
            )
        );
        $id = '1';
        $this->assertIsNumeric($id);
        $response = $this->UserManager->getUserById($id);
        $this->assertJson(  
            $response
         );
        $expectedResponse = json_encode(
            array(
                json_encode('
                    {
                        id: "1",
                        name: "Moises"
                    }
                ')
            )
        );
        $this->assertEquals(  
            $expectedResponse, $response
        );
        
        $resultado = new stdClass();
        $resultado->num_rows = 2;
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE id='1' ")
            ->once()
            ->andReturn($resultado);
        $response = $this->UserManager->getUserById($id);
        $this->assertIsNumeric(  
            $response
         );
        $expectedResponse1 = 2;
        $this->assertEquals(  
            $expectedResponse1, $response
        );
    }
    public function testGetUserByIdNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE id='' ")
            ->once()
            ->andReturn(null);
        $id = null;
        $this->assertNull($id);
        $this->assertEquals(  
            "Tabla usuario vacia", $this->UserManager->getUserById($id)
         );
    }
    public function testDeleteUserPositive(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM usuario WHERE id = 1")->andReturn('true');
        $id = 1;

        $this->assertIsNumeric($id);

        $this->assertEquals( 
            'true', $this->UserManager->deleteUser($id)
        );
    }
    public function testDeleteUserNegative(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM usuario WHERE id = 0")->andReturn('');
        $id = 0;

        $this->assertIsNumeric($id);

        $this->assertEquals( 
            '', $this->UserManager->deleteUser($id)
        );
    }
    public function testGetAllUsersPositive(){
        $this->setupMockito();
        $user1 = array(
            "id" => 1,
            "nombre" => "Moises",
            "tipo" => 0,
            "clave" => "123"
        );
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario")
            ->once()
            ->andReturn(
                array(
                    $user1,
                    $user1
                )
        );
        $response = $this->UserManager->getAllUsers();
        $this->assertJson(  
            $response
         );
        $user1 = array(
            "id" => 1,
            "name" => "Moises",
            "type" => 0,
            "password" => "123"
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
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario")
            ->once()
            ->andReturn($resultado);
        $response = $this->UserManager->getAllUsers();
        $this->assertIsNumeric(  
            $response
         );
        $expectedResponse1 = 2;
        $this->assertEquals(  
            $expectedResponse1, $response
        );
    }
    public function testGetAllUsersNegative(){
        $this->setupMockito();
        $user1 = array(
            "id" => 1,
            "nombre" => "Moises",
            "tipo" => 0,
            "clave" => "123"
        );
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario")
            ->once()
            ->andReturn(
                null
        );
        $response = $this->UserManager->getAllUsers();
        $this->assertEquals(  
            'Tabla usuario vacia', $response
        );
    }
    
    protected function tearDown(): void {
        $this->dbManager->close();
    }
}
?>