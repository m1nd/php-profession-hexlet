<?php
 --------------

public function toSql()
{
        $sqlParts = [];
        $sqlParts[] = "SELECT * FROM {$this->table}";
        if ($this->where) {
            $where = implode(' AND ', array_map(function ($key, $value) {
                $quotedValue = $this->pdo->quote($value);
                return "$key = $quotedValue";
            }, array_keys($this->where), $this->where));
            $sqlParts[] = "WHERE $where";
        }

        return implode(' ', $sqlParts);
}
----------
public function count()
{
    $query = $this->select('COUNT(*)');
    $stmt = $this->pdo->query($query->toSql());
    return $stmt->fetchColumn();
}
	
public function each($func)
{
    $stmt = $this->pdo->query($this->toSql());
    array_map($func, $stmt->fetchAll());
}
-----------
public function save(User $user)
{
    $stmtUser = $this->pdo->prepare("INSERT INTO users (name) VALUES (?)");
    $stmtUser->execute([$user->getName()]);
    $user->setId($this->pdo->lastInsertId());

    $stmt = $this->pdo->prepare("INSERT INTO user_photos (user_id, name, filepath) VALUES (?, ?, ?)");

   foreach ($user->getPhotos() as $photo) {
        $stmt->execute([$user->getId(), $photo->getName(), $photo->getFilepath()]);
    }
}
--------
function like($pdo, array $params)
{
    $likeParts = array_reduce(array_keys($params), function ($acc, $item) {
        $acc[] = "$item LIKE ?";
        return $acc;
    }, []);
    $sqlParts = [];
    $sqlParts[] = "select id from users";
    if (!empty($likeParts)) {
        $sqlParts[] = "where";
        $sqlParts[] = implode(" OR ", $likeParts);
    }
    $sql = implode(" ", $sqlParts);
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($params));

    return $stmt->fetchAll(\PDO::FETCH_COLUMN);
}
---------
function in($pdo, array $params) {
 
 $whereParts = array_reduce(
        array_keys($params),
        function ($acc, $key) use ($pdo, $params) {
            $value = $params[$key];
            if (is_array($value) && !empty($value)) {
                $in = array_map(function ($item) use ($pdo) {
                    return $pdo->quote($item);
                }, $value);
                $joinedIn = implode(", ", $in);
                $acc[] = "$key IN ($joinedIn)";
            } else if (!is_array($value)) {
                $quotedValue = $pdo->quote($value);
                $acc[] = "$key = $quotedValue";
            }
            return $acc;
        },
        []
    );

    $sqlParts = [];
    $sqlParts[] = "select id from users";
    if (!empty($whereParts)) {
        $sqlParts[] = "where";
        $sqlParts[] = implode(" OR ", $whereParts);
    }
    $sqlParts[] = "order by id";
    $sql = implode(" ", $sqlParts);
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_COLUMN);
}
---------





















