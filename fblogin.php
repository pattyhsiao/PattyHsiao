<?php
ob_start();
session_start();
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require '../src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '183307721840095',
  'secret' => '21e11e3b620e361055456d2777a1017e',
  'cookie' => true,
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}

// This call will always work since we are fetching public data.
$naitik = $facebook->api('/naitik');

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
  </head>
  <body>
		<? if($user){//判斷是否有登入FB紀錄，有就直接抓值，沒有就要求重新登入
				$_SESSION['userID'] = $user;//這裡的$user中為SDK抓取到的FB ID，存進SESSION中使回到login.php時可以進行判斷
				$_SESSION['name'] = $user_profile['name'];//$user_profile為一個陣列變數，可從中取得許多會員基本資料，name為FB名稱
				$_SESSION['logout'] = $logoutUrl;//$logoutUrl為SDK所設定好的專門登出網址，一樣是先把值存回login.php，因為登出的動作是在那個網頁執行
				header('location: login.php');//header()這個涵式為自動跳轉，在這網頁取得所有資料後，跳回login.php
				exit();//避免header後繼續執行網頁，用exit()強制結束
			}else{
				header('location:'.$loginUrl);//$loginUrl為SDK所設定好的專門登入網址，如果沒登入過FB就會直接執行這行header跳到FB登入，登入完跳回這後再跳到login.php
			exit();
		}?>
<? ob_end_flush(); ?>
  </body>
</html>
