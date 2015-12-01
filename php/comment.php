<?php

	class Comment {

		private $id;
		private $userId;
		private $blogId;
		private $content;
		
		function __construct($id, $userId, $blogId, $content) {
			
			$this->id = $id;
			$this->userId = $userId;
			$this->blogId = $blogId;
			$this->content = $content;

		}

		function getUserId() {
			return $this->userId;
		}

		function getContent() {
			return $this->content;
		}
	}

?>