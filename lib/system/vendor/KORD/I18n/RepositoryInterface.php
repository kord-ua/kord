<?php

namespace KORD\I18n;

/**
 * Internationalization (i18n) repository interface
 */
interface RepositoryInterface
{

    /**
     * Attach an i18n reader
     * 
     * @param  \KORD\I18n\Reader\ReaderInterface  $reader
     */
    public function attach(\KORD\I18n\Reader\ReaderInterface $reader);

    /**
     * Get and set the target language.
     *
     *     // Get the current language
     *     $lang = $i18n->lang();
     *
     *     // Change the current language to Spanish
     *     $i18n->lang('es-es');
     *
     * @param   string  $lang   new language setting
     * @return  string
     */
    public function lang($lang = null);

    /**
     * Translation/internationalization function with context support.
     * The PHP function [strtr](http://php.net/strtr) is used for replacing parameters.
     * 
     *    $i18n->translate(':count user is online', 1000, [':count' => 1000]);
     *    // 1000 users are online
     * 
     * @param   string  $string   String to translate
     * @param   mixed   $context  String form or numeric count
     * @param   array   $values   Param values to insert
     * @param   string  $lang     Target language
     * @return  string
     */
    public function translate($string, $context = 0, $values = null, $lang = null);

    /**
     * Returns specified form of a string translation. If no translation exists, the original string will be
     * returned. No parameters are replaced.
     * 
     *     $hello = $i18n->form('I\'ve met :name, he is my friend now.', 'fem');
     *     // I've met :name, she is my friend now.
     * 
     * @param   string  $string
     * @param   string  $form if null, looking for 'other' form, else the very first form
     * @param   string  $lang
     * @return  string
     */
    public function form($string, $form = null, $lang = null);

    /**
     * Returns translation of a string. If no translation exists, the original string will be
     * returned. No parameters are replaced.
     * 
     *     $hello = $i18n->plural('Hello, my name is :name and I have :count friend.', 10);
     *     // Hello, my name is :name and I have :count friends.
     * 
     * @param   string  $string
     * @param   mixed   $count
     * @param   string  $lang
     * @return  string
     */
    public function plural($string, $count = 0, $lang = null);
}
