<?php

	class Blog {

		private $blogId;
		private $title;
		private $content;
		private $date;
		
		function __construct($blogId, $title, $content, $date) {
			
			$this->blogId = $blogId;
			$this->title = $title;
			$this->content = $content;
			$this->date = $date;

		}

		function getId() {

			return $this->blogId;

		}

		function getTitle() {

			return $this->title;

		}

		function getContent() {

			return $this->content;

		}

		function getDate() {

			return $this->date;

		}

		function getComments($dbLink) {

			$commentList = array();
			$stmt = $dbLink->prepare("SELECT * FROM blog_comments WHERE blogId = (?)");

			$stmt->bind_param('i', $this->getId());
			$stmt->execute();
			$stmt->bind_result($resultId, $resultUserId, $resultBlogId, $resultContent);

			while ($stmt->fetch()) {

				array_push($commentList, new Comment($resultId, $resultUserId, $resultBlogId, $resultContent));

			}

			$stmt->close();
			return $commentList;

		}

		function postComment($comment, $user, $dbLink) {

			$stmt = $dbLink->prepare("INSERT INTO blog_comments (userId, blogId, content) VALUES (?, ?, ?)");

			$stmt->bind_param("iis", $user->getId(), $this->getId(), $comment);
			$stmt->execute();

		}

		function countComments($dbLink) {

			$blogCount = 0;
			$stmt = $dbLink->prepare("SELECT id FROM blog_comments WHERE blogId = (?)");

			$stmt->bind_param('s', $this->getId());
			$stmt->execute();

			while ($stmt->fetch()) {

				$blogCount++;

			}

			$stmt->close();
			return $blogCount;

		}

		static function getBlogById($id, $dbLink) {

			$stmt = $dbLink->prepare("SELECT * FROM blogs WHERE id = (?)");

			$stmt->bind_param('s', $id);
			$stmt->execute();
			$stmt->bind_result($resultId, $resultTitle, $resultContent, $resultDate);

			while ($stmt->fetch()) {

				$stmt->close();
				return new Blog($resultId, $resultTitle, $resultContent, $resultDate);

			}

			$stmt->close();
			return new Blog(NULL, NULL, NULL, NULL);

		}

		static function getAllBlogs($dbLink) {

			$blogList = array();
			$stmt = $dbLink->prepare("SELECT * FROM blogs");

			$stmt->execute();
			$stmt->bind_result($resultId, $resultTitle, $resultContent, $resultDate);

			while ($stmt->fetch()) {

				array_push($blogList, new Blog($resultId, $resultTitle, $resultContent, $resultDate));

			}

			$stmt->close();
			return $blogList;

		}
	}

?>