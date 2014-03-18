<?php

namespace Spol\FSM;

use PDO;
use PDOException;
use Exception;

class Database
{
    protected $connection;

    public function __construct($connectionConfig)
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$connectionConfig['host']};dbname={$connectionConfig['db']}",
                $connectionConfig["user"],
                $connectionConfig["pass"]
            );
        } catch (PDOException $exp) {
            throw new Exception("Unable to connect to database.");
        }
    }

    public function getRootFolder()
    {
        $stmt = $this->connection->query("SELECT * FROM filesystemEntries WHERE path = '/'");
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        } else {
            return null;
        }
    }

    public function insertRootFolder($created)
    {
        $stmt = $this->connection->prepare(
<<<SQL
INSERT INTO
    filesystemEntries
SET
    type = 'D',
    name = '',
    parent_id = null,
    path = '/',
    created_time = :created,
    modified_time = :modified,
    size = 0
SQL
        );
        $stmt->execute(
            array(
                ':created' => $created->format('Y-m-d H:i:s'),
                ':modified' => $created->format('Y-m-d H:i:s')
            )
        );

        return (int)$this->connection->lastInsertId();
    }

    public function insertFolder($name, $path, $parentId, $created)
    {
        $stmt = $this->connection->prepare(
<<<SQL
INSERT INTO
    filesystemEntries
SET
    type = 'D',
    name = :name,
    parent_id = :parent,
    path = :path,
    created_time = :created,
    modified_time = :created,
    size = 0
SQL
        );
        $stmt->execute(array(
                ":name" => $name,
                ":parent" => $parentId,
                ":path" => $path,
                ":created" => $created->format('Y-m-d H:i:s'),
            ));
        return (int)$this->connection->lastInsertId();
    }

    public function updateFolder($id, $name, $path)
    {
        $stmt = $this->connection->prepare("UPDATE filesystemEntries SET name = :name, path = :path WHERE id = :id");
        $stmt->execute(array(":name" => $name, ":path" => $path, ":id" => $id));
    }

    public function insertFile($name, $size, $parentId, $created)
    {
        $stmt = $this->connection->prepare(
<<<SQL
INSERT INTO
    filesystemEntries
SET
    type = 'F',
    name = :name,
    parent_id = :parent,
    created_time = :created,
    modified_time = :created,
    size = :size
SQL
        );
        $stmt->execute(array(
                ":name" => $name,
                ":parent" => $parentId,
                ":size" => $size,
                ":created" => $created->format('Y-m-d H:i:s'),
            ));
        return (int)$this->connection->lastInsertId();
    }

    public function updateFile($id, $name, $size, $modified)
    {
        $stmt = $this->connection->prepare(
<<<SQL
UPDATE
    filesystemEntries
SET
    modified_time = :modified,
    name = :name,
    size = :size
WHERE
    id = :id
SQL
        );
        $stmt->execute(array(
                ":id" => $id,
                ":name" => $name,
                ":size" => $size,
                ":modified" => $modified->format('Y-m-d H:i:s'),
            ));
    }

    public function deleteItem($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM filesystemEntries WHERE id = :id");
        $stmt->execute(array(":id" => $id));
    }

    public function childExists($parentId, $name)
    {
        $stmt = $this->connection->prepare(
<<<SQL
SELECT
    *
FROM
    filesystemEntries
WHERE
    parent_id = :parent
    AND
    name = :name
SQL
        );
        $stmt->execute(array(":parent" => $parentId, ":name" => $name));
        return $stmt->rowCount() > 0;
    }

    public function getChildren($id, $type)
    {
        $stmt = $this->connection->prepare("SELECT * FROM filesystemEntries WHERE parent_id = :id AND type = :type");
        $stmt->execute(array(":id" => $id, ":type" => $type));
        $data = $stmt->fetchAll();
        return $data;
    }
}
