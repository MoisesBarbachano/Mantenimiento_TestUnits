<?php
use PHPUnit\Framework\TestCase;
require_once("./core/php/DataBaseManager.php");
class DataBaseManagerTest extends TestCase
{
    private $dbManager;

    private function setupMockito(){
        $this->dbManager = Mockery::mock(DatabaseManager::class);
    }

    public function testRealizeQueryPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT COUNT(*) FROM usuario")
        ->once()
        ->andReturn(1);
  
    $response = $this->dbManager->realizeQuery("SELECT COUNT(*) FROM usuario");
    $this->assertEquals(  
        1, $response
    );
    }

    public function testRealizeQueryNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE nombre='' AND clave=''")
            ->once()
            ->andReturn(null);
        
            $response = $this->dbManager->realizeQuery("SELECT * FROM usuario WHERE nombre='' AND clave=''");
            $this->assertEquals(  
                null, $response
            );
    }


}
