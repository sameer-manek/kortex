<?php

namespace Data;

class Queries {
	const
		// queries
		SELECT_USER = "SELECT * FROM users WHERE email = ?",

		// queue operations
		INSERT_TASK 	= "INSERT INTO queue (task_json) VALUES (?)",
		PENDING_TASKS 	= "SELECT * FROM queue WHERE status = 'pending' ORDER BY created_at",
		UPDATE_TASK 	= "UPDATE queue SET status = ? WHERE id = ?",
		FINISH_TASK 	= "UPDATE queue SET status = 'finished' WHERE id = ?",
		DELETE_TASK 	= "DELETE FROM queue WHERE id = ?",

		// NULL VAR - just for semi-colon
		NULL = NULL;
}
