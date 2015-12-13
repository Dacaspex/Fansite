<?php

	class Product {

		private $productId;
		private $title;
		private $type;
		private $price;

		function __construct($productId, $title, $type, $price) {

			$this->productId = $productId;
			$this->title = $title;
			$this->type = $type;
			$this->price = $price;

		}

		function getId() {
			return $this->productId;
		}

		function getTitle() {
			return $this->title;
		}

		function buy($user, $dbLink) {

			$stmt = $dbLink->prepare("INSERT INTO orders (userId, productId) VALUES (?, ?)");

			$stmt->bind_param("ii", $user->getId(), $this->getId());
			$stmt->execute();

			$stmt->close();

			// Remove 1 unit from the amount field in the database
			$stmt = $dbLink->prepare("UPDATE products SET amount = amount - 1 WHERE id = (?)");

			$stmt->bind_param('i', $this->getId());
			$stmt->execute();

			$stmt->close();

		}

		static function getProductById($id, $dbLink) {

			$stmt = $dbLink->prepare("SELECT id, title, type, price FROM products WHERE id = (?)");

			$stmt->bind_param('s', $id);
			$stmt->execute();
			$stmt->bind_result($resultId, $resultTitle, $resultType, $resultPrice);

			while ($stmt->fetch()) {

				$stmt->close();
				return new Product($resultId, $resultTitle, $resultType, $resultPrice);

			}

			$stmt->close();
			return NULL;

		}

	}

?>