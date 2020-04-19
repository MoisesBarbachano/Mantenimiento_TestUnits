<?php
use PHPUnit\Framework\TestCase;
require_once("./core/php/DataBaseManager.php");
final class DataBaseManagerTest extends TestCase
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

}
?>
