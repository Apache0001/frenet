<?php

/**
 * ####################
 * ###   PASSWORD   ###
 * ####################
 */

/* function passwd(string $password): string
{
    if(!empty(password_get_info($password)['algo'])){
        return $password;
    }
    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}
 */

/**
 * @param string $password
 * @return string
 */

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */

function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}


/**
 * @param string $date
 * @param string $format
 * @return string
 */
function date_fmt(string $date = "now", string $format = "Y/m/d H\hi"): string
{
    return (new DateTime($date))->format($format);
}


