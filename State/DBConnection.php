<?php

namespace Monitoring\State;

class DBConnection extends StateAbstract
{
    const STATE_TYPE = 'DataBase';

    const SERVER   = 'server';
    const USERNAME = 'username';
    const PASSWORD = 'password';

    public function verifyError()
    {
        @$mysqlConnection = mysql_connect(
            $this->getParam(self::SERVER),
            $this->getParam(self::USERNAME),
            $this->getParam(self::PASSWORD)
        );

        if(!$mysqlConnection) {
            $this->getHandler()->addErrorHandle(
                "No Mysql Connection",
                '',
                $this->getStateType()
            );
        }
    }
}