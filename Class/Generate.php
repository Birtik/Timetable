<?php 


class Generate
{
    private static $days_in_month = [31,28,31,30,31,30,31,31,30,31,30,31];

    public static function getMonth(){
        return (getdate())['mon'];
    }

    /**
     * Insert new position into DB
     * 
     * @param Object $conn PDO_Connection
     * 
     * @return void
     * 
     */
    public static function generateDays($conn){

        if(!static::checkExistData($conn))
        {
            $month = static::getMonth();

            $days = static::checkMonth($month);

            $values = '';

            for($i=1;$i<=$days;$i++){
                $values .=  $i == $days ? "('2020-{$month}-{$i}',1);" : "('2021-{$month}-{$i}',1),";
            }

            $sql = "INSERT INTO `trainings` (`date`,`add`) VALUES {$values}";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
    }

    /**
     * Check data in actual month
     * 
     * @param Object $conn PDO
     * 
     * @return Bool  
     */
    public static function checkExistData($conn){

        $month = static::getMonth();
        $sql = "SELECT * FROM trainings WHERE MONTH(date) = :month LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":month",$month,PDO::PARAM_STR);

        if($stmt->execute())
        {
            $m = $stmt->fetch(PDO::FETCH_ASSOC);

            if(empty($m)) return false;
            else return true;
        }
    }


    /**
     * Checking a month
     * 
     * @param Integer $number numberOfMonth
     * 
     * @return void
     * 
     */
    public static function checkMonth($number){

        return static::$days_in_month[$number-1];
    }



    /**
     * Return data from 
     * 
     * @param Object $conn PDO 
     * 
     * 
     * @return Array $data 
     */
    public static function getDays($conn){

        $month = static::getMonth();
        $sql = "SELECT * FROM trainings WHERE MONTH(date) = :month ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":month",$month,PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);;
    }


    /**
     * Get a summary complete days in month
     * 
     * @param Object $conn PDO_Connection
     * 
     * @param Integer $att AttributeFlag
     * 
     * @return Integer
     * 
     */
    public static function getCountOfDoneDays($conn,$att){

        $month = static::getMonth();
        $sql = '';

        if($att==1)
        {
            $sql = "SELECT COUNT(*) as c FROM `trainings` WHERE MONTH(`date`) = :month AND `dev` = 1";
        }
        else if($att==2)
        {
            $sql = "SELECT COUNT(*) as c FROM `trainings` WHERE MONTH(`date`) = :month AND `train` = 1";
        }
        else if($att==3)
        {
            $sql = "SELECT COUNT(*) as c FROM `trainings` WHERE MONTH(`date`) = :month AND `add` = 0";
        }

        $stmt = $conn->prepare($sql);

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":month",$month,PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetch(PDO::FETCH_ASSOC);

        return $count['c'];
    }

    /**
     * Calculate progress for attribute
     * 
     * @param Object $conn PDO_Connection
     * 
     * @param Integer $att AttributeFlag
     * 
     * @return Integer ProgressInPercent
     * 
     */
    public static function getProgress($conn,$att){

        $month = static::getMonth();

        $days = static::checkMonth($month);

        $count =  static::getCountOfDoneDays($conn,$att);

        $percent = round(($count / $days),4);

        return $percent*100;
    }
}