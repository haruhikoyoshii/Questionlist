CREATE TABLE `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'お問い合わせID',
  `question_id` int(11) NOT NULL  COMMENT '質問ID',
  `answer_body` varchar(255) DEFAULT NULL COMMENT '解答',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4