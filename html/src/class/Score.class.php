<?php

class Score
{
    private static $pdo;

    public static function initialize(): void
    {
        Dbh::startConnection();
        self::$pdo = Dbh::getConnection();
    }

    public static function create($groupId)
    {
        $groupId = DataProcessor::sanitizeData($groupId);

        // Prepare the SQL query
        $query = "
            INSERT INTO `score` (group_id)
            VALUES (:group_id);
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':group_id' => $groupId
            ]);
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function getScores($orderStyle = "scoreDESC")
    {
        $query = "
            SELECT `score`.*, `group_info`.*, COUNT(`group_member`.id) as 'member_count',
                RANK() OVER(ORDER BY `score`.score DESC) AS 'rank'
            FROM `score`
            INNER JOIN `group_info` ON `group_info`.id = `score`.group_id
            INNER JOIN `group_member` ON `group_member`.group_id = `group_info`.id
            GROUP BY `score`.id, `group_info`.id
            ORDER BY 
        ";

        switch ($orderStyle) {
            // Score
            case "scoreDESC":
                $query .= "`score`.score DESC";
                break;
            case "scoreASC":
                $query .= "`score`.score ASC";
                break;
                
            // Name
            case "nameAZ":
                $query .= "`group_info`.name ASC";
                break;
            case "nameZA":
                $query .= "`group_info`.name DESC";
                break;
            
            // Description
            case "descAZ":
                $query .= "`group_info`.description ASC";
                break;
            case "descZA":
                $query .= "`group_info`.description DESC";
                break;

            // Default
            default:
                $query .= "`score`.score DESC";
                break;
        }
        
        $query .= ";";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute();

        $results = $sto->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
}

Score::initialize();
