<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testCreateTask(): void
    {
        $client = static::createClient();
        $client->request('POST', '/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'New Task',
            'description' => 'Task description',
            'status' => 'todo',
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201); // 201 Created

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Task created successfully!', $data['message']);
    }

    public function testCreateTaskWithInvalidData(): void
    {
        $client = static::createClient();
        $client->request('POST', '/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'T',
            'description' => '', // Invalid description
            'status' => 'todo',
        ]));

        $this->assertResponseStatusCodeSame(400); // 400 Bad Request
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $data);
    }

    public function testUpdateTask(): void
    {
        $client = static::createClient();
        
        // Crée d'abord une tâche
        $client->request('POST', '/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Task to Update',
            'description' => 'Old description',
            'status' => 'todo',
        ]));

        $data = json_decode($client->getResponse()->getContent(), true);
        $taskId = $data['id']; // Supposons que l'ID de la tâche soit renvoyé dans la réponse

        // Modifie la tâche
        $client->request('PUT', "/tasks/{$taskId}", [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Updated Task',
            'description' => 'New description',
            'status' => 'in_progress',
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200); // 200 OK

        $updatedData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Updated Task', $updatedData['title']);
    }

    public function testDeleteTask(): void
    {
        $client = static::createClient();
        
        // Crée une tâche à supprimer
        $client->request('POST', '/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Task to Delete',
            'description' => 'This task will be deleted',
            'status' => 'todo',
        ]));

        $data = json_decode($client->getResponse()->getContent(), true);
        $taskId = $data['id']; // Supposons que l'ID soit renvoyé

        // Supprime la tâche
        $client->request('DELETE', "/tasks/{$taskId}");
        $this->assertResponseStatusCodeSame(204); // 204 No Content

        // Vérifie que la tâche a été supprimée
        $client->request('GET', "/tasks/{$taskId}");
        $this->assertResponseStatusCodeSame(404); // 404 Not Found
    }

    public function testGetTasksPaginated(): void
    {
        $client = static::createClient();

        // Crée plusieurs tâches
        for ($i = 1; $i <= 15; $i++) {
            $client->request('POST', '/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
                'title' => "Task $i",
                'description' => "Description $i",
                'status' => 'todo',
            ]));
        }

        // Récupère la première page (10 tâches)
        $client->request('GET', '/tasks?page=1');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['data' => count($tasks) === 10]);
    }

    public function testSearchTasks(): void
    {
        $client = static::createClient();

        // Recherche par titre
        $client->request('GET', '/tasks?query=Task 1');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['title' => 'Task 1']);
    }
}

