<?php
define('unisitecms', true);

$config = require "./../../config.php";
$static_msg = require $config["basePath"] . "/static/msg.php";

require_once($config["basePath"]."/systems/unisite.php");

$key = $_GET["key"] ? $_GET["key"] : $_POST["key"];
$route = $_GET["route"] ? $_GET["route"] : $_POST["route"];

if(!$key || $key != $config["api_key"]){  
	http_response_code(500); exit('Invalid api key'); 
}

if(!$route){
	http_response_code(500); exit('Empty route');
}

require "fn.php";

if($route == 'geo/location'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'getSettings'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Geo

if($route == 'geo/country'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'geo/search'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'geo/getCheckAlias'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'geo/coordinatesDecodingAddress'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Ads

if($route == 'ads/getAds'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'ads/searchAds'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Shops

if($route == 'shops/getShops'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'shops/getShop'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Filters

if($route == 'filters/getOptions'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'filters/getFilters'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Home

if($route == 'home/getAds'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'home/getData'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'home/getFeed'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Profile

if($route == 'profile/auth/auth'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/auth/authToken'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/auth/recovery'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/auth/reg'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/auth/verifyPrev'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/auth/verify'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/card/editCard'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/card/editPass'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/card/editScore'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/card/deleteProfile'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/auth/statusSocialAuth'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Balance

if($route == 'profile/balance/getHistory'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/balance/initPayment'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/balance/statusPayment'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Chat

if($route == 'profile/chat/getUsers'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/chat/getDialog'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/chat/sendMessage'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/chat/updateDialog'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/chat/deleteDialog'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/chat/getCount'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/chat/updateToken'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/chat/clearDialogs'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Card

if($route == 'profile/card/editTextStatus'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/card/getAds'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/card/getData'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Favorites

if($route == 'profile/favorites/deleteFavorite'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/favorites/getFavorites'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/favorites/getFavoritesSubscriptions'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Stories

if($route == 'stories/getStories'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/getUserStories'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/getUsersStories'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/updateCountView'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/addImage'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/addVideo'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/updateUserStories'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/initLoaded'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/paymentStory'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/updateImage'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/updateVideo'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/delete'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'stories/getOneUserStories'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Card ad

if($route == 'card_ad/getCard'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_ad/getServicesTariffs'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_ad/addServiceTariff'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_ad/getSimilarAds'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_ad/changeStatus'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_ad/paymentPublication'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_ad/addSecureOrder'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_ad/addAuctionRate'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_ad/auctionAdReservation'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_ad/auctionCancelOrder'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_ad/auctionAcceptBuy'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_ad/addComplain'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Card user

if($route == 'card_user/getData'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_user/subscribe'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_user/block'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'card_user/complain'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Favorite

if($route == 'favorite/actionFavorite'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Reviews

if($route == 'reviews/getReviews'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'reviews/getReview'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'reviews/delete'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'reviews/add'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Subscriptions

if($route == 'profile/subscriptions/getSubscriptions'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/subscriptions/deleteSubscription'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Blacklist

if($route == 'profile/blacklist/getUsers'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/blacklist/delete'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Orders

if($route == 'profile/orders/getOrders'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/orders/getOrder'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/orders/changeStatus'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/orders/initPayment'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/orders/initPaymentBalance'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/orders/statusPayment'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/orders/addDispute'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/orders/getDeliveryPoints'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/orders/searchDeliveryPointsCity'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/orders/searchDeliveryPointsSend'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Catalog

if($route == 'catalog/getCategories'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'catalog/getAllCategories'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'catalog/getCategoriesOffers'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'catalog/getCategoriesNested'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'catalog/getSnippets'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'catalog/getAds'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'catalog/search'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'catalog/searchAdsShop'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'catalog/getUserStories'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Create Ad

if($route == 'create_ad/options'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'create_ad/validation'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'create_ad/create'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'create_ad/getFilters'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Edit Ad

if($route == 'edit_ad/load'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'edit_ad/edit'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'edit_ad/validation'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}
// Assets

if($route == 'assets/save'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Verify

if($route == 'verify/send_phone'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'verify/verify_phone'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'verify/send_email'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'verify/verify_email'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Map

if($route == 'map/searchAddress'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Blog

if($route == 'blog/getArticles'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'blog/getArticle'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Booking

if($route == 'booking/getData'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'booking/calculation'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'booking/addOrder'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'booking/getOrder'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'booking/changeStatus'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'booking/delete'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'booking/initPayment'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'booking/statusPayment'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Shop

if($route == 'profile/shop/getData'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/shop/delete'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/shop/edit'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/shop/add'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/shop/addPage'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/shop/editPage'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/shop/deletePage'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// User tariffs

if($route == 'profile/tariff/getData'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/tariff/activate'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/tariff/delete'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/tariff/getScheduler'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/tariff/deleteScheduler'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/tariff/getStatisticsAd'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/tariff/getBookingCalendar'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/tariff/loadOrdersBookingCalendar'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'profile/tariff/cancelDateBookingCalendar'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Packages orders

if($route == 'packages/getOrdersPackages'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'packages/getPackages'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'packages/getCategories'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

if($route == 'packages/payment'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

// Report

if($route == 'error_report/add'){
	require $config["basePath"]."/systems/api/{$route}.php";
	exit;
}

http_response_code(500);
exit('The router was not found');
?>