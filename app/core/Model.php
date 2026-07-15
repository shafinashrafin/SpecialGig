<?php
class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $hidden = ['password', 'remember_token', 'two_factor_secret'];

    public function __construct()
    {
        if (empty($this->table)) {
            $this->table = strtolower((new ReflectionClass($this))->getShortName()) . 's';
        }
    }

    public function all(): array
    {
        return Database::fetchAll("SELECT * FROM {$this->table}");
    }

    public function find(int $id)
    {
        return Database::fetch("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id", ['id' => $id]);
    }

    public function findBy(string $column, $value)
    {
        return Database::fetch("SELECT * FROM {$this->table} WHERE {$column} = :value", ['value' => $value]);
    }

    public function findAllBy(string $column, $value): array
    {
        return Database::fetchAll("SELECT * FROM {$this->table} WHERE {$column} = :value", ['value' => $value]);
    }

    public function where(string $where, array $params = []): array
    {
        return Database::fetchAll("SELECT * FROM {$this->table} WHERE {$where}", $params);
    }

    public function create(array $data): int
    {
        $data = $this->filterFillable($data);
        return Database::insert($this->table, $data);
    }

    public function update(int $id, array $data): int
    {
        $data = $this->filterFillable($data);
        $data[$this->primaryKey] = $id;
        return Database::update($this->table, $data, "{$this->primaryKey} = :{$this->primaryKey}", []);
    }

    public function updateWhere(array $data, string $where, array $params = []): int
    {
        $data = $this->filterFillable($data);
        return Database::update($this->table, $data, $where, $params);
    }

    public function delete(int $id): int
    {
        return Database::delete($this->table, "{$this->primaryKey} = :id", ['id' => $id]);
    }

    public function deleteWhere(string $where, array $params = []): int
    {
        return Database::delete($this->table, $where, $params);
    }

    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $result = Database::fetch($sql, $params);
        return $result ? (int) $result->count : 0;
    }

    public function paginate(int $page = 1, int $perPage = 20, string $where = '', array $params = [], string $orderBy = 'id DESC'): array
    {
        $offset = ($page - 1) * $perPage;
        $whereClause = $where ? "WHERE {$where}" : '';
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
        $total = $this->count($where, $params);
        $data = Database::fetchAll($sql, $params);
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => ceil($total / $perPage),
        ];
    }

    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    public function toArray($obj): array
    {
        $data = (array) $obj;
        foreach ($this->hidden as $field) {
            unset($data[$field]);
        }
        return $data;
    }
}
