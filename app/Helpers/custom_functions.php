<?php

function getLoginRole(){
    $auth = \Illuminate\Support\Facades\Auth::user();
    return $auth->getRoleNames()[0];
}
