<?php
/**
 * Fungsi get_curl
 * Fungsi untuk meload halaman remote,
 * lalu mengembalikan info koneksi dan output halaman tersebut
 * Parameter:
 * @param string $url     : URL halaman remote
 * @param string $referer : string HTTP_REFERER yang dicek di halaman remote
 * @param array $post     : Array yang akan diterima halaman remote sebagai _POST
 * @return array yang terdiri dari output dari curl_exec dan info dari curl_getinfo
 */
if ( ! function_exists('get_curl')) {
    function get_curl ($url, $referer, $post, $timeout=0) {
        // create a new CURL resource
        $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($timeout != 0) curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // grab URL and pass it to the browser
        $output = curl_exec($ch);
        //get url info
        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // close CURL resource, and free up system resources
        curl_close($ch);
        //return CURL output dan CURL info
        return array('output' => $output,
                'info'   => $info);
    }
    function get_curl_post ($url, $referer, $post, $timeout=0) {
        // create a new CURL resource
        $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($timeout != 0) curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // grab URL and pass it to the browser
        $output = curl_exec($ch);
        //get url info
        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // close CURL resource, and free up system resources
        curl_close($ch);
        //return CURL output dan CURL info
        return array('output' => $output,
                'info'   => $info);
    }
    function get_curl_get ($url, $referer,$timeout=0) {
        // create a new CURL resource
        $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($timeout != 0) curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // grab URL and pass it to the browser
        $output = curl_exec($ch);
        //get url info
        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // close CURL resource, and free up system resources
        curl_close($ch);
        //return CURL output dan CURL info
        return array('output' => $output,
                'info'   => $info);
    }
}
