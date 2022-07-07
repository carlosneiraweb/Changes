ALTER DATABASE portal CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
describe portal;

ALTER TABLE usuarios_expulsados CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
SHOW FULL COLUMNS FROM post;