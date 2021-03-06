<?php declare(strict_types=1);

namespace XoopsModules\Publisher;

/**
 * Class TrelloDBController
 * @package XoopsModules\Publisher
 */
class TrelloDBController
{
    /** @var \XoopsMySQLDatabase $db */
    private $db;

    public function __construct()
    {
        $this->db =  \XoopsDatabaseFactory::getDatabaseConnection();
    }

    /**
     * @param $query
     * @return mixed
     */
    public function runBaseQuery($query)
    {
        $result = $this->db->conn->query($query);
        if ($result->num_rows > 0) {
            while (null !== ($row = $result->fetch_assoc())) {
                $resultset[] = $row;
            }
        }

        return $resultset;
    }

    /**
     * @param $query
     * @param $paramType
     * @param $paramValueArray
     * @return mixed
     */
    public function runQuery($query, $paramType, $paramValueArray)
    {
        $sql = $this->db->conn->prepare($query);
        $this->bindQueryParams($sql, $paramType, $paramValueArray);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            while (null !== ($row = $result->fetch_assoc())) {
                $resultset[] = $row;
            }
        }

        if (!empty($resultset)) {
            return $resultset;
        }
    }

    /**
     * @param $sql
     * @param $paramType
     * @param $paramValueArray
     */
    public function bindQueryParams($sql, $paramType, $paramValueArray)
    {
        $paramValueReference[] = &$paramType;
        for ($i = 0, $iMax = count($paramValueArray); $i < $iMax; ++$i) {
            $paramValueReference[] = &$paramValueArray[$i];
        }
        call_user_func_array([
            $sql,
            'bind_param',
        ], $paramValueReference);
    }

    /**
     * @param $query
     * @param $paramType
     * @param $paramValueArray
     */
    public function insert($query, $paramType, $paramValueArray)
    {
        $sql = $this->db->conn->prepare($query);
        $this->bindQueryParams($sql, $paramType, $paramValueArray);
        $sql->execute();
    }

    /**
     * @param $query
     * @param $paramType
     * @param $paramValueArray
     */
    public function update($query, $paramType, $paramValueArray)
    {
        $sql = $this->db->conn->prepare($query);
        $this->bindQueryParams($sql, $paramType, $paramValueArray);
        $sql->execute();
    }
}
