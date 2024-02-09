<?php

namespace App\ContohBootcamp\Services;

use App\ContohBootcamp\Repositories\TaskRepository;

class TaskService {
	private TaskRepository $taskRepository;

	public function __construct() {
		$this->taskRepository = new TaskRepository();
	}

	/**
	 * NOTE: untuk mengambil semua tasks di collection task
	 */
	public function getTasks()
	{
		$tasks = $this->taskRepository->getAll();
		return $tasks;
	}

	/**
	 * NOTE: menambahkan task
	 */
	public function addTask(array $data)
	{
		$taskId = $this->taskRepository->create($data);
		return $taskId;
	}

	/**
	 * NOTE: UNTUK mengambil data task
	 */
	public function getById(string $taskId)
	{
		$task = $this->taskRepository->getById($taskId);
		return $task;
	}

	/**
	 * NOTE: untuk update task
	 */
	public function updateTask(array $editTask, array $formData)
	{
		if(isset($formData['title']))
		{
			$editTask['title'] = $formData['title'];
		}

		if(isset($formData['description']))
		{
			$editTask['description'] = $formData['description'];
		}

		$id = $this->taskRepository->save( $editTask);
		return $id;
	}

	/**
	 * Untuk menghapus task
	 */
	public function deleteTask(string $taskId)
	{
		$this->taskRepository->deleteTask($taskId);
		return['message' => 'Tugas berhasil dihapus'];
	}

	/**
	 * Menugaskan task ke pengguna
	 */
	public function assignTask(string $taskId, string $assigned)
	{
		$this->taskRepository->assignTask($taskId, $assigned);
		return $this->taskRepository->getById($taskId);
	}

	/**
	 * Unassign task
	 */
	public function unassignTask(string $taskId)
	{
		$this->taskRepository->unassignTask($taskId);
		return $this->taskRepository->getById($taskId);
	}

	/**
	 * Membuat subtask didalam task
	 */
	public function createSubtask(string $taskId, array $subTaskData)
	{
		$this->taskRepository->createSubtask($taskId, $subTaskData);
		return $this->taskRepository->getById($taskId);
	}

	/**
	 * Menghapus subtask
	 */
	public function deleteSubtask(string $taskId, string $subTaskId)
	{
		$this->taskRepository->deleteSubtask($taskId, $subTaskId);
		return $this->taskRepository->getById($taskId);
	}
}