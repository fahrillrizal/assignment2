<?php
namespace App\ContohBootcamp\Repositories;

use App\Helpers\MongoModel;

class TaskRepository
{
	private MongoModel $tasks;
	public function __construct()
	{
		$this->tasks = new MongoModel('tasks');
	}

	/**
	 * Untuk mengambil semua tasks
	 */
	public function getAll()
	{
		$tasks = $this->tasks->get([]);
		return $tasks;
	}

	/**
	 * Untuk mendapatkan task bedasarkan id
	 *  */
	public function getById(string $id)
	{
		$task = $this->tasks->find(['_id'=>$id]);
		return $task;
	}

	/**
	 * Untuk membuat task
	 */
	public function create(array $data)
	{
		$dataSaved = [
			'title'=>$data['title'],
			'description'=>$data['description'],
			'assigned'=>null,
			'subtasks'=> [],
			'created_at'=>time()
		];

		$id = $this->tasks->save($dataSaved);
		return $id;
	}

	/**
	 * Untuk menyimpan task baik untuk membuat baru atau menyimpan dengan struktur bson secara bebas
	 *  */
	public function save(array $editedData)
	{
		$id = $this->tasks->save($editedData);
		return $id;
	}

	/**
	 * Hapus task
	 */
	public function deleteTask(string $taskId)
	{
		$this->tasks->deleteQuery(['_id' => $taskId]);
	}

	/**
	 * Menugaskan task ke pengguna
	 */
	public function assignTask(string $taskId, string $assigned)
	{
		$existTask = $this->tasks->find(['_id' => $taskId]);

		if ($existTask){
			$existTask['assigned'] = $assigned;
			$this->tasks->save($existTask);
		}
	}

	/**
	 * Unassign task
	 */
	public function unassignTask(string $taskId)
	{
		$existTask = $this->tasks->find(['_id' => $taskId]);

		if ($existTask){
			$existTask['assigned'] = null;
			$this->tasks->save($existTask);
		}
	}

	/**
	 * Membuat subtask didalam task
	 */
	public function createSubTask(string $taskId, array $subTaskData)
	{
		$existTask = $this->tasks->find(['_id' => $taskId]);

		if ($existTask){
			$subtasks = isset($existTask['subtaks']) ? $existTask['subtasks'] : [];
			$subtasks[] = [
				'_id' => (string) new \MongoDB\BSON\ObjectId(),
				'title' => $subTaskData['title'],
				'description' => $subTaskData['description']
			];
			$existTask['subtaks'] = $subtasks;
			$this->tasks->save($existTask);
		}
	}

	/**
	 * Menghapus Subtask
	 */
	public function deleteSubTask(string $taskId, string $subTaskId)
	{
		$existTask = $this->tasks->find(['_id' => $taskId]);

		if ($existTask){
			$subtasks = isset($existTask['subtaks']) ? $existTask['subtaks'] : [];
			$subtasks = array_filter($subtasks, function ($subtask) use ($subTaskId){
				return $subtask['_id'] != $subTaskId;
			});
			$existTask['subtaks'] = array_values($subtasks);
			$this->tasks->save($existTask);
		}
	}
}