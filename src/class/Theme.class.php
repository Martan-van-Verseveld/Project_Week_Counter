<?php

class Theme
{
    private static $pdo;

    public static function initialize(): void
    {
        Dbh::startConnection();
        self::$pdo = Dbh::getConnection();
    }

    public static function getThemes($teacher = false)
    {
        $WHERE = (!$teacher) ? "WHERE `theme`.hidden = 0" : "";
        // Prepare the SQL query
        $query = "
            SELECT `theme`.*, 
                COUNT(`group_theme`.id) AS register_count
            FROM `theme`
            LEFT JOIN `group_theme` ON `group_theme`.theme_id = `theme`.id
            $WHERE
            GROUP BY `theme`.id;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute();
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $fetch = $sto->fetchAll(PDO::FETCH_ASSOC);
        return $fetch;
    }

    public static function create($title, $description): int
    {
        // Prepare the SQL query
        $query = "
            INSERT INTO `theme` (title, description)
            VALUE (:title, :description);
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':title' => $title,
                ':description' => $description
            ]);
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $insertedId = self::$pdo->lastInsertId();
        return $insertedId;
    }

    public static function getTheme($themeId)
    {
        $themeId = DataProcessor::sanitizeData($themeId);

        // Prepare the SQL query
        $query = "
            SELECT `theme`.*, 
                COUNT(`group_theme`.id) AS register_count
            FROM `theme`
            LEFT JOIN `group_theme` ON `group_theme`.theme_id = `theme`.id
            WHERE `theme`.id = :themeId
            GROUP BY `theme`.id;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':themeId' => $themeId
            ]);
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $fetch = $sto->fetch(PDO::FETCH_ASSOC);
        return $fetch;
    }

    public static function deleteGroup($groupId) 
    {
        $query = "
            DELETE FROM `group_theme`
            WHERE group_id = :group_id
            LIMIT 1;
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

    public static function update($themeId, $data)
    {
        $themeId = DataProcessor::sanitizeData($themeId);
        $data = DataProcessor::sanitizeData($data);

        foreach ($data as $key => $value) {
            // Prepare the SQL query
            $query = "
                UPDATE `theme`
                SET $key = :value
                WHERE id = :theme_id;
            ";

            try {
                // Execute statement
                $sto = self::$pdo->prepare($query);
                $sto->execute([
                    ':theme_id' => $themeId,
                    ':value' => $value
                ]);
            } catch (PDOException $e) {
                Session::pdoDebug($e);
            }
        }

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function isRegistered($themeTitle, $themeId) 
    {
        $themeTitle = DataProcessor::sanitizeData($themeTitle);
        $themeId = DataProcessor::sanitizeData($themeId);

        $query = "
            SELECT * 
            FROM `theme`
            WHERE title = :theme_title AND NOT id = :theme_id ;
        ";

        // Fetch the result
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            'theme_title' => $themeTitle,
            'theme_id' => $themeId
        ]);
        
        $results = $sto->rowCount();
        return ($results > 0);
    }

    public static function registerGroup($themeId, $groupId) 
    {
        $themeId = DataProcessor::sanitizeData($themeId);
        $groupId = DataProcessor::sanitizeData($groupId);

        // Prepare the SQL query
        $query = "
            INSERT INTO `group_theme` (group_id, theme_id)
            VALUE (:group_id, :theme_id);
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':group_id' => $groupId,
                ':theme_id' => $themeId
            ]);
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $insertedId = self::$pdo->lastInsertId();
        return $insertedId;
    }

    public static function isActive($themeId)
    {
        $themeId = DataProcessor::sanitizeData($themeId);

        $query = "
            SELECT * 
            FROM `theme`
            WHERE id = :theme_id AND active = 1;
        ";

        // Fetch the result
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            'theme_id' => $themeId
        ]);
        
        $results = $sto->rowCount();
        return ($results > 0);
    }

    public static function isHidden($themeId)
    {
        $themeId = DataProcessor::sanitizeData($themeId);

        $query = "
            SELECT * 
            FROM `theme`
            WHERE id = :theme_id AND hidden = 1;
        ";

        // Fetch the result
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            'theme_id' => $themeId
        ]);
        
        $results = $sto->rowCount();
        return ($results > 0);
    }
}

Theme::initialize();
