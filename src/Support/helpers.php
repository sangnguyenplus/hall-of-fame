<?php

use Whozidis\HallOfFame\Models\Researcher;

if (! function_exists('hof_researcher')) {
    /**
     * Get the currently authenticated Hall of Fame researcher
     *
     * @return \Whozidis\HallOfFame\Models\Researcher|null
     */
    function hof_researcher()
    {
        if (! session('hof_researcher')) {
            return null;
        }

        return Researcher::find(session('hof_researcher'));
    }
}

if (! function_exists('hof_researcher_authenticated')) {
    /**
     * Check if a Hall of Fame researcher is authenticated
     *
     * @return bool
     */
    function hof_researcher_authenticated()
    {
        return session('hof_researcher') !== null;
    }
}

if (! function_exists('get_gravatar')) {
    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param int $size Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $default Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
     * @param string $rating Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return string Gravatar URL or IMG tag
     */
    function get_gravatar($email, $size = 80, $default = 'mp', $rating = 'g', $img = false, $atts = [])
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$size&d=$default&r=$rating";

        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }

        return $url;
    }
}
