<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/test/{id}', 'Front@test');
Route::get('/','Front@index');
Route::get('/products','Front@products');
Route::get('/products/details/{id}','Front@product_details');
Route::get('/products/categories','Front@product_categories');
Route::get('/products/brands','Front@product_brands');
Route::get('/blog','Front@blog');
Route::get('/blog/post/{id}','Front@blog_post');
Route::get('/contact-us','Front@contact_us');
Route::get('/login','Front@login');
Route::get('/logout','Front@logout');
Route::get('/checkout','Front@checkout');
Route::get('/search/{query}','Front@search');
Route::get('/insert', function(){
	App\Category::create(array('name' => 'Music'));
	return 'Category Added';
});
Route::get('/read', function(){
	$category = new App\Category();
	$data = $category->all(array('name','id'));
	foreach ($data as $key) {
		echo "$key->id. $key->name<br>";
	}
});
Route::get('/update', function(){
	$category = App\Category::find(7);
	$category->name = 'HEAVY METAL';
	$category->save();
	$data = $category->all(array('name', 'id'));
	foreach ($data as $key) {
		echo "$key->id. $key->name<br>";
	}
});
Route::post('/cart', 'Front@cart');
Route::get('/cart', 'Front@cart');
Route::get('auth/login', 'Front@login');
Route::post('auth/login', 'Front@authenticate');
Route::get('auth/logout', 'Front@logout');
Route::post('/register', 'Front@register');
Route::get('/checkout', ["middleware" => 'auth', 'uses' => 'Front@checkout']);
Route::get('/raw', function () {
    $sql = "INSERT INTO categories (name) VALUES ('POMBE')";

    DB::statement($sql);
    $results = DB::select(DB::raw("SELECT * FROM categories"));

    print_r($results);
}
);
Route::get('/api/v1/products/{id?}', ['middleware' => 'auth.basic', function($id = null){
	if($id == null){
		$products = App\Product::all(array('id', 'name', 'price'));
	}else{
		$products = App\Product::find($id, array('id', 'name', 'price'));
	}
	return Response::json(array(
		'error' => false, 
		'products' => $products, 
		'status_code' => 200
		));
}]);
Route::get('/api/v1/categories/{id?}', ['middleware' => 'auth.basic', function($id = null){
	if($id == null){
		$categories = App\Category::all(array('id', 'name'));
	}else{
		$categories = App\Category::find($id,array('id', 'name'));
	}

	return Response::json(array(
		'error' => false,
		'user' => $categories,
		'status_code' => 200
		));
}]);