<?php

// @TODO Remove once JOINDIN-512 is complete
require_once(__DIR__ . '/SpamCheckService/User.php');

/**
 * A class that lets you check against an external service (Akismet)
 * for spam in your content
 */
class SpamCheckService
{
    protected $akismetUrl;

    public function __construct($apiKey) {
        $this->akismetUrl = 'http://' . $apiKey . '.rest.akismet.com';
    }

    /**
     * Check your comment against the spam check service
     *
     * @param $data
     * @param $userIp
     * @param $userAgent
     *
     * @return Boolean true if the comment is okay, false if it got rated as spam
     */
    public function isCommentAcceptable($data, SpamCheckService_User $user) {
        $comment = array();

        // set some required fields
        $comment['blog'] = 'http://joind.in';

        // TODO what are better values to use for these required fields?
        $comment['user_ip']    = $user->getUserIp();
        $comment['user_agent'] = $user->getUserAgent();

        // now use the incoming data
        $comment['comment_content'] = $this->getField("comment", $data);

        // actually do the check
        $ch = curl_init($this->akismetUrl . '/1.1/comment-check');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $comment);

        $result = curl_exec($ch);
        curl_close($ch);

        // if the result is false, it wasn't spam and we can return true
        // to indicate that the comment is acceptable
        if($result == "false") {
            return true;
        }

        // otherwise, anything could have happened and we don't know if it's acceptable
        // TODO log what did happen
        return false;
    }

    protected function getField($key, $data) {
        if (isset($data[$key])) {
            return $data[$key];
        }
        return false;
    }
}
