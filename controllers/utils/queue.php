<?php

// Don't make changes to this file unless you know what you are doing.

namespace Controllers\Utils;

use ReflectionClass;
use Data\DB;
use Data\Config;
use Data\Queries;

class Task {
	private $classname, $data, $action_method;

	public function __construct ($classname, $data, $action_method) {
		$this->classname = $classname;
		$this->data = $data;
		$this->action_method = $action_method;
	}

	public function run () {
		$class = new ReflectionClass($this->classname);
		$instance = $class->newInstanceArgs($this->data);
		
		call_user_func_array(array($instance, $this->action_method), $this->data);
	}
}

/*
This class interfaces with rabbitmq to manage tasks on queue
You as a developer would not need to deal with this class directly
All the Enqueuable classes have enqueue / dequeue methods to interact with this class	
*/

class Queue {
	public static function enqueue ($classname, $data, $action_method) {
		
		// serialize data to json > push to queue
		$json = json_encode(array(
			"classname" => $classname,
			"data" => $data,
			"action" => $action_method
		));

		// store it on DB
		$db = new DB();
		$db->query(Queries::INSERT_TASK, $json);
		unset($db);
	}

	// this function is called by the swoole worker
	public static function work () {
		$db = new DB();
		$tasks = $db->query(Queries::PENDING_TASKS);
		
		foreach ($tasks as $task) {
			$task = (object) $task;
			self::run_task($task->task_json);
			$db->query(Queries::FINISH_TASK, $task->id);
		}
		unset($db);
	}

	public static function run_task ($task_json) {
		$task = json_decode($task_json);
		$task = new Task($task->classname, $task->data, $task->action);
		$task->run();
	}
}