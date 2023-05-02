<?

namespace App\Services\MiniServiceBioSmartHttp;

use App\Models\Worker;

class CDBUser
{

    public $mysqliLink;

    function __construct($cMysqliLink = false)
    {
        $this->mysqliLink = $cMysqliLink;
    }

    public function getAppointmentByBsId($bsId)
    {
        $query = Worker::
        where('bs_id', $bsId)
            ->with('appointment')
            ->whereHas('appointment')
            ->first();
        if (!empty($query->appointment)) {
            return $query->appointment->name ?? '';
        }
        return '';
    }

    static function getAppointmentForAllWorkers()
    {
        global $mysqli_self_db;
        $query = "
            select
              worker.clock_num clock_num
              , appointments.name appointment
            from
              worker
              left join appointments
                on appointments.bs_id = worker.job_id
            ;
        ";
        $sqlResult = $mysqli_self_db->query($query);

        if ($sqlResult->num_rows > 0) {
            while ($row = $sqlResult->fetch_object())
                $result[$row->clock_num] = $row->appointment;

            return $result;
        } else
            return false;
    }


    public function getPerson($varUserId)
    {
        if (isset($varUserId) && !empty($varUserId)) {
            // Проверяем входные переменные
            $intUserId = (int)$varUserId;
            if ($intUserId != $varUserId) return false;
            // Формируем запрос
            $queryFields = "
                worker.name name, worker.surname surname, worker.middlename middlename,
                worker.id worker_id,
                appointments.name as appointment,
                user.login,
                user.id as id, user.r_adb as r_adb, user.r_are as r_are, user.r_aut as r_aut,
                user.r_bar as r_bar, user.r_buh as r_buh, user.r_buk as r_buk, user.r_den as r_den,
                user.r_dis as r_dis, user.r_kur as r_kur, user.r_nku as r_nku, user.r_nom as r_nom,
                user.r_otc as r_otc, user.r_prz as r_prz, user.r_shw as r_shw, user.r_sot as r_sot,
                user.r_tmc as r_tmc, user.r_toc as r_toc, user.r_vak as r_vak, admin
            ";
            $queryUser = "
                SELECT
                    $queryFields
                FROM
                    user
                        LEFT JOIN worker ON worker.id = user.worker_id
                        LEFT JOIN appointments ON worker.job_id = appointments.bs_id
                WHERE
                    user.id = " . $intUserId . " LIMIT 1
            ";
            // Выполняем запрос
            $sqlPerson = $this->mysqliLink->query($queryUser);

            // Обрабатываем данные
            $arPerson = $sqlPerson->fetch_assoc();
            $person = new CUser($arPerson, ['verifiedUserID' => true]);
            // добавим экземпляр в репозиторий сущностей.
            // TODO вообще отсюда надо бы вынести создание эксземпляра CUser. он всего лишь возвращается в ответ, а в самом CDBUser даже не хранится.
            \services\EntityManager::addEntityRepository('CUser');
            \services\EntityManager::addEntity('CUser', $arPerson['id'], $person);
            // Возвращаем результат
            return $person;
        } else {
            return null;
        }
    }

    public function getPersonByWorkerId($varWorkerId)
    {
        // Проверяем входные переменные
        $intWorkerId = (int)$varWorkerId;
        if ($intWorkerId != $varWorkerId) {
            return false;
        }
        // Формируем запрос
        $queryWorker = "SELECT name, surname, middlename FROM worker WHERE worker.id = " . $intWorkerId . " LIMIT 1";
        // Выполняем запрос
        $sqlPerson = $this->mysqliLink->query($queryWorker);
        $devAr = ['table' => 'worker', 'id' => $intWorkerId];

        // Обрабатываем данные
        $arPerson = $sqlPerson->fetch_assoc();
        $arPerson['devInfo'] = $devAr;
        $person = new CUser($arPerson);
        // Возвращаем результат
        return $person;
    }

    public function getPersonsByWorkersIDs($arr_ids)
    {
        if (!empty($arr_ids)) {
            $queryWorker = "SELECT worker.id as worker_id, worker.name, worker.surname, worker.middlename
                            FROM worker
                            WHERE worker.id IN (" . implode(', ', $arr_ids) . ")";
            $sqlPerson = $this->mysqliLink->query($queryWorker);
            while ($arPerson = $sqlPerson->fetch_assoc()) {
                $persons[$arPerson['worker_id']] = new CUser($arPerson);
            }
            return $persons;
        }
    }

    public function getDriverById($varWorkerId)
    {
        // Проверяем входные переменные
        $intWorkerId = (int)$varWorkerId;
        if ($intWorkerId != $varWorkerId) {
            return false;
        }
        // Формируем запрос
        $queryDriver = "
                SELECT worker.name, worker.surname, worker.middlename, drivers.nomer
                FROM worker
                JOIN drivers ON drivers.worker_id = worker.id
                WHERE drivers.id = " . $intWorkerId . " LIMIT 1
            ";
        // Выполняем запрос
        $sqlPerson = $this->mysqliLink->query($queryDriver);
        $devAr = ['table' => 'worker', 'id' => $intWorkerId];

        // Обрабатываем данные
        $arPerson = $sqlPerson->fetch_assoc();
        $arPerson['devInfo'] = $devAr;
        $person = new CUser($arPerson);
        // Возвращаем результат
        return $person;
    }

    public function getDriversByIDs($arr_ids = array())
    {
        if (!empty($arr_ids)) {
            $queryDrivers = "
                SELECT drivers.id as driver_id, worker.id as worker_id, `user`.id as user_id, worker.name, worker.surname, worker.middlename, drivers.nomer
                FROM worker
                LEFT JOIN drivers ON drivers.worker_id = worker.id
                LEFT JOIN `user` ON `user`.worker_id = worker.id
                WHERE drivers.id IN (" . implode(', ', $arr_ids) . ") OR worker.id IN (" . implode(', ', $arr_ids) . ")
            ";

            $sqlPerson = $this->mysqliLink->query($queryDrivers);

            while ($arPerson = $sqlPerson->fetch_assoc()) {
                $persons[$arPerson['worker_id']] = new CUser($arPerson);
                $persons[$arPerson['driver_id']] = new CUser($arPerson);
            }

            return $persons;
        }
    }

    public function getByName($partOfName)
    {
        // Проверяем входные переменные
        $partOfName = $this->mysqliLink->real_escape_string($partOfName);
        // Формируем запрос
        $query = "SELECT user.id id FROM user JOIN worker ON worker.id = user.worker_id WHERE worker.surname LIKE '%$partOfName%'";
        // Выполняем запрос
        $sqlResult = $this->mysqliLink->query($query);
        // Обрабатываем данные
        while ($row = $sqlResult->fetch_object())
            $ids[] = $row->id;
        // Возвращаем результат
        return $ids;
    }

    public function setOnline($varUserId)
    {
        // Проверяем входные переменные
        $intUserId = (int)$varUserId;
        if ($intUserId != $varUserId) {
            return false;
        }
        // Формируем запрос
        $curDate = date('Y-m-d H:i:s');
        $query = "UPDATE user SET online=$curDate WHERE id=$intUserId LIMIT 1;";
        // Выполняем запрос
        $sqlResult = $this->mysqliLink->query($query);
    }

    public function setOffline($varUserId)
    {
        // Проверяем входные переменные
        $intUserId = (int)$varUserId;
        if ($intUserId != $varUserId) {
            return false;
        }
        // Формируем запрос
        $query = "UPDATE user SET online='' WHERE id=$intUserId LIMIT 1;";
        // Выполняем запрос
        $sqlResult = $this->mysqliLink->query($query);
    }

    public function authorize($varLogin, $varPass)
    {
        // Проверяем входные переменные
        $login = $this->mysqliLink->real_escape_string($varLogin);
        $pass = $this->mysqliLink->real_escape_string($varPass);
        $passMd5 = md5($pass);
        // Формируем запрос
        $query = "
                SELECT * FROM user
                WHERE login='$login' AND pass='$passMd5' AND enabled_dis=1
                LIMIT 1;
            ;";
        $queryMd5 = "
                SELECT * FROM user
                WHERE login='$login' AND pass='$pass' AND enabled_dis=1
                LIMIT 1;
            ;";
        // Выполняем запрос
        $sqlResult = $this->mysqliLink->query($query);
        $sqlResultMd5 = $this->mysqliLink->query($queryMd5);
        // Обрабатываем данные Возвращаем результат
        if ($sqlResult->num_rows > 0) {
            $authUser = $sqlResult->fetch_assoc();
            $this->setOnline($authUser['id']);
            $_SESSION = array_merge($_SESSION, $authUser);
            $_SESSION[md5('CodeForDoingOperations@78$^&746')] = md5(rand(9999, 999999));
            unset($_SESSION['pass']);
            return true;
        } else {
            if ($sqlResultMd5->num_rows > 0) {
                $authUser = $sqlResultMd5->fetch_assoc();
                $this->setOnline($authUser['id']);
                $_SESSION = array_merge($_SESSION, $authUser);
                $_SESSION[md5('CodeForDoingOperations@78$^&746')] = md5(rand(9999, 999999));
                unset($_SESSION['pass']);
                return true;
            } else
                return false;
        }
    }

    public function loguot($varUserId)
    {
        $intUserId = (int)$varUserId;
        if ($intUserId != $varUserId) {
            return false;
        }
        $this->setOffline($intUserId);
        session_destroy();
    }


}

?>
