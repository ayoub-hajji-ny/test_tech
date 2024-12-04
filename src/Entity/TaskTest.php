<?php
// tests/Entity/TaskTest.php
namespace App\Tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function testTaskValidations(): void
    {
        $task = new Task();
        $task->setTitle('Test Task')
             ->setDescription('Test Description')
             ->setStatus('todo')
             ->setCreatedAt(new \DateTime())
             ->setUpdatedAt(new \DateTime());

        // Assert that the task is valid
        $this->assertEquals('Test Task', $task->getTitle());
        $this->assertEquals('Test Description', $task->getDescription());
        $this->assertEquals('todo', $task->getStatus());
    }
}

