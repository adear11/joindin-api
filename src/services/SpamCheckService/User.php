<?php

/**
 * An interface to represent SpamCheck clients
 *
 * @author    Christopher Hoult <chris@choult.com>
 */
interface SpamCheckService_User
{
    /**
     * Gets the IP address of the originator of the potential spam
     *
     * @return string
     */
    public function getUserIp();

    /**
     * Gets the user agent of the originator of the potential spam
     *
     * @return string
     */
    public function getUserAgent();
}