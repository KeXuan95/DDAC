<?php
    session_destroy();
    header('Location: '.Config::get('home'));
    exit();