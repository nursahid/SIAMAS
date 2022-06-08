<?php

// format size for humans ;)
function format_file_size($size)
{
    // bytes
    if ($size < 1024) {
        return $size . " bytes";
    } // kilobytes
    elseif ($size < (1024 * 1024)) {
        return round(($size / 1024), 1) . " KB";
    } // megabytes
    elseif ($size < (1024 * 1024 * 1024)) {
        return round(($size / (1024 * 1024)), 1) . " MB";
    } // gigabytes
    else {
        return round(($size / (1024 * 1024 * 1024)), 1) . " GB";
    }
}

/**
 *
 * @param string $param1
 * @param string $param2
 * @param string $result1
 * @param string $result2
 */
function short_if($param1 = '', $param2 = '', $result1 = '', $result2 = '')
{
    return ($param1 == $param2) ? $result1 : $result2;
}

/**
 * @param $ptime time stamp format
 * @return string THE ELAPSED TIME STRING
 */
function time_elapsed_string($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1) {
        return '0 seconds';
    }

    $a = array(
        365 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
        );
    $a_plural = array(
        'year' => 'years',
        'month' => 'months',
        'day' => 'days',
        'hour' => 'hours',
        'minute' => 'minutes',
        'second' => 'seconds'
        );

    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);

            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}

/**
 * @throws current day in this format (0000-00-00)
 * @return bool|string
 */
function current_date()
{
    return date("Y-m-d", time());
}

function uri_segment($number = 0)
{
    $CI =& get_instance();
    return $CI->uri->segment($number);
}


/**
 * Redirect to previous url.
 */

if (! function_exists('last_url')) {
    function last_url($action = 'get', $url = null)
    {
        $CI =& get_instance();
        if (!$CI->input->is_ajax_request()) {
            switch ($action) {
                case 'get':
                    $result = $CI->session->userdata('last_url');
                    break;

                case 'set':
                    if ($url == null) {
                        $CI->session->set_userdata('last_url', current_url());
                    } else {
                        $CI->session->set_userdata('last_url', $url);
                    }
                    break;
                default:
                    $result = $CI->session->userdata('last_url');
                    break;
            }
            return $result;
        } else {
            return false;
        }
    }
}

/**
 * advanced session destroy function.
 */
if (! function_exists('ci_session_destroy')) {
    function ci_session_destroy($exception_list = [])
    {
        $CI =& get_instance();
        $user_data = $CI->session->all_userdata();

        // dump($user_data);
        foreach ($user_data as $key => $value) {
            if (!in_array($key, $exception_list)) {
                // dump($key);
                $CI->session->unset_userdata($key);
            }
        }
        // exit;
    }
}
