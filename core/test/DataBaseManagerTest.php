<?php
use PHPUnit\Framework\TestCase;
require_once("./core/php/userManager.php");
final class DataBaseManagerTest extends TestCase
{
private $dbManager;

    private function setupMockito(){
        $this->dbManager = Mockery::mock(DatabaseManager::class);
        $this->dbManager->shouldReceive('close')->andReturn(null);
        $this->dbManager->shouldReceive('insertQuery')->once()->with("")->andReturn(false);
    }  
    public function testClose(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('close')->andReturn('true');
        $this->assertEquals(null, $this->dbManager->close());
    }

}
?>