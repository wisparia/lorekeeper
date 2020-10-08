<?php

/*
|--------------------------------------------------------------------------
| Browse Routes
|--------------------------------------------------------------------------
|
| Routes for pages that don't require being logged in to view,
| specifically the information pages.
|
*/

/**************************************************************************************************
    Widgets
**************************************************************************************************/

Route::get('items/{id}', 'Users\InventoryController@getStack');

/**************************************************************************************************
    News
**************************************************************************************************/
# PROFILES
Route::group(['prefix' => 'news'], function() {
    Route::get('/', 'NewsController@getIndex');
    Route::get('{id}.{slug?}', 'NewsController@getNews');
    Route::get('{id}.', 'NewsController@getNews');
});

/**************************************************************************************************
    Users
**************************************************************************************************/
Route::get('/users', 'BrowseController@getUsers');
Route::get('/blacklist', 'BrowseController@getBlacklist');

# PROFILES
Route::group(['prefix' => 'user', 'namespace' => 'Users'], function() {
    Route::get('{name}', 'UserController@getUser');
    Route::get('{name}/characters', 'UserController@getUserCharacters');
    Route::get('{name}/myos', 'UserController@getUserMyoSlots');
    Route::get('{name}/inventory', 'UserController@getUserInventory');
    Route::get('{name}/bank', 'UserController@getUserBank');
    
    Route::get('{name}/currency-logs', 'UserController@getUserCurrencyLogs');
    Route::get('{name}/item-logs', 'UserController@getUserItemLogs');
    Route::get('{name}/ownership', 'UserController@getUserOwnershipLogs');
    Route::get('{name}/submissions', 'UserController@getUserSubmissions');
});

/**************************************************************************************************
    Characters
**************************************************************************************************/
Route::get('/masterlist', 'BrowseController@getCharacters');
Route::get('/myos', 'BrowseController@getMyos');
Route::group(['prefix' => 'character', 'namespace' => 'Characters'], function() {
    Route::get('{slug}', 'CharacterController@getCharacter');
    Route::get('{slug}/profile', 'CharacterController@getCharacterProfile');
    Route::get('{slug}/bank', 'CharacterController@getCharacterBank');
    Route::get('{slug}/images', 'CharacterController@getCharacterImages');
    
    Route::get('{slug}/currency-logs', 'CharacterController@getCharacterCurrencyLogs');
    Route::get('{slug}/ownership', 'CharacterController@getCharacterOwnershipLogs');
    Route::get('{slug}/change-log', 'CharacterController@getCharacterLogs');
    Route::get('{slug}/submissions', 'CharacterController@getCharacterSubmissions');
});
Route::group(['prefix' => 'myo', 'namespace' => 'Characters'], function() {
    Route::get('{id}', 'MyoController@getCharacter');
    Route::get('{id}/profile', 'MyoController@getCharacterProfile');
    Route::get('{id}/ownership', 'MyoController@getCharacterOwnershipLogs');
    Route::get('{id}/change-log', 'MyoController@getCharacterLogs');
});


/**************************************************************************************************
    World
**************************************************************************************************/

Route::group(['prefix' => 'world'], function() {
    Route::get('/', 'WorldController@getIndex');
    
    Route::get('currencies', 'WorldController@getCurrencies');
    Route::get('rarities', 'WorldController@getRarities');
    Route::get('species', 'WorldController@getSpecieses');
    Route::get('subtypes', 'WorldController@getSubtypes');
    Route::get('item-categories', 'WorldController@getItemCategories');
    Route::get('items', 'WorldController@getItems');
    Route::get('trait-categories', 'WorldController@getFeatureCategories');
    Route::get('traits', 'WorldController@getFeatures');
    Route::get('prompt-categories', 'WorldController@getPromptCategories');
    Route::get('prompts', 'WorldController@getPrompts');
    Route::get('character-categories', 'WorldController@getCharacterCategories');
});

Route::group(['prefix' => 'shops'], function() {
    Route::get('/', 'ShopController@getIndex');
    Route::get('{id}', 'ShopController@getShop')->where(['id' => '[0-9]+']);
    Route::get('{id}/{stockId}', 'ShopController@getShopStock')->where(['id' => '[0-9]+', 'stockId' => '[0-9]+']);
});

/**************************************************************************************************
    Site Pages
**************************************************************************************************/
Route::get('info/{key}', 'PageController@getPage');

/**************************************************************************************************
    Raffles
**************************************************************************************************/
Route::group(['prefix' => 'raffles'], function () {
    Route::get('/', 'RaffleController@getRaffleIndex');
    Route::get('view/{id}', 'RaffleController@getRaffleTickets');
});

/**************************************************************************************************
    Submissions
**************************************************************************************************/
Route::group(['prefix' => 'submissions', 'namespace' => 'Users'], function() {
    Route::get('view/{id}', 'SubmissionController@getSubmission');
});
Route::group(['prefix' => 'claims', 'namespace' => 'Users'], function() {
    Route::get('view/{id}', 'SubmissionController@getClaim');
});

/**************************************************************************************************
    World Expansion
**************************************************************************************************/

Route::group(['prefix' => 'world', 'namespace' => 'WorldExpansion'], function() {

    Route::get('info', 'LocationController@getWorld');
    Route::get('locations', 'LocationController@getLocations');
    Route::get('locations/{id}', 'LocationController@getLocation');
    Route::get('location-types', 'LocationController@getLocationTypes');
    Route::get('location-types/{id}', 'LocationController@getLocationType');

    Route::get('faunas', 'NatureController@getFaunas');
    Route::get('faunas/{id}', 'NatureController@getFauna');
    Route::get('fauna-categories', 'NatureController@getFaunaCategories');
    Route::get('fauna-categories/{id}', 'NatureController@getFaunaCategory');

    Route::get('floras', 'NatureController@getFloras');
    Route::get('floras/{id}', 'NatureController@getFlora');
    Route::get('flora-categories', 'NatureController@getFloraCategories');
    Route::get('flora-categories/{id}', 'NatureController@getFloraCategory');

    Route::get('events', 'EventController@getEvents');
    Route::get('events/{id}', 'EventController@getEvent');
    Route::get('event-categories', 'EventController@getEventCategories');
    Route::get('event-categories/{id}', 'EventController@getEventCategory');

    Route::get('figures', 'FigureController@getFigures');
    Route::get('figures/{id}', 'FigureController@getFigure');
    Route::get('figure-categories', 'FigureController@getFigureCategories');
    Route::get('figure-categories/{id}', 'FigureController@getFigureCategory');
});
