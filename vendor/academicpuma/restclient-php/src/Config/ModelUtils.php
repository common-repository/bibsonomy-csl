<?php

namespace AcademicPuma\RestClient\Config;

class ModelUtils
{
    // Keeps all curly braces in titel, abstract and author field.
    const CB_KEEP = 0;
    // Keeps curly braces within math mode -> $...$, otherwise they are removed.
    const CB_KEEP_IN_MATH_MODE = 1;
    // Removes all curly braces in title, abstract and author field.
    const CB_REMOVE = 2;

    // Keeps all backslashes in title, abstract and author field.
    const BS_KEEP = 0;
    // Keeps backslashes within math mode -> $...$, otherwise they are removed.
    const BS_KEEP_IN_MATH_MODE = 1;
    // Removes all backslashes in title, abstract and author field.
    const BS_REMOVE = 2;
}