<?php

require_once("./core/php/DataBaseManager.php");
final class DataBaseManagerTest extends PHPUnit_Framework_TestCase
{
    private $dbManager;


    private $DataBaseManager;

    private function setupMockito(){
        $this->DataBaseManager = DataBaseManager::getInstance();
        $this->dbManager = Mockery::mock(DatabaseManager::class);
        $this->dbManager->shouldReceive('close')->andReturn(null);
        $this->dbManager->shouldReceive('insertQuery')->once()->with("")->andReturn(false);
        $this->DataBaseManager->setDBManager($this->dbManager);
    }

    //public function testClose(){
    //    $this->setupMockito();
    //    $this->dbManager->shouldReceive('close')->andReturn('true');
    //    $this->assertEquals(null, $this->dbManager->close());
    //}

    public function testInsertQueryPositive(){
        $this->setupMockito();

        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO parejas (concepto,descripcion,idmateria) VALUES('conceptotest','descripciontest','1')")->andReturn(1);

        $response = $this->dbManager->insertQuery("INSERT INTO parejas (concepto,descripcion,idmateria) VALUES('conceptotest','descripciontest','1')");

        $this->assertEquals(
          1,$response
        );
    }

    public function testInsertQueryNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO parejas (concepto,descripcion,idmateria) VALUES('','','')")->andReturn(null);

        $response = $this->dbManager->insertQuery("INSERT INTO parejas (concepto,descripcion,idmateria) VALUES('','','')");

        $this->assertEquals(
          null,$response
        );
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
?>
