<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Controller;
use App\Brand;
use App\Product;
use App\Category;
use Cart;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Post;
use DB;

class Front extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    var $brands, $categories, $products, $title, $description;

    public function __construct(){
        $this->brands = Brand::all(array('name'));
        $this->categories = Category::all(array('name'));
        $this->products = Product::all(array('id', 'name', 'price'));
    }

    public function index() {
        return view('home', array('title' => 'Welcome', 'description' => '', 'page' => 'home', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function products() {
        return view('products', array('title' => 'Product Listing', 'description' => '', 'page' => 'products', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function product_details($id) {
        $product = Product::find($id);
        return view('product_details', array('product' => $product, 'title' => $product->name, 'description' => '', 'page' => 'products', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function product_categories($name) {
        return view('products', array('title' => 'Welcome', 'description' => '', 'page' => 'products', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function product_brands($name, $category = null) {
        return view('products', array('title' => 'Welcome', 'description' => '', 'page' => 'products', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function blog() {
        $posts = Post::where('id', '>', 0)->paginate(3);
        $posts->setPath('blog');
        $data['posts'] = $posts;
        return view('blog', array('data' => $data, 'title' => 'Latest blog posts', 'description' => '', 'page' => 'blog', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function blog_post($id) {
        $post = Post::whereUrl($url)->first();
        $tags = $post->tags;
        $prev_url = Post::prevBlogPostUrl($post->id);
        $next_url = Post::nextBlogPostUrl($post->id);
        $title = $post->title;
        $description = $post->description;
        $page = 'blog';
        $brands = $this->brands;
        $categories = $this->categories;
        $products = $this->products;
        $data = compact('prev_url', 'next_url', 'tags', 'post', 'title', 'description', 'page', 'brands', 'categories', 'products');
        return view('blog_post', $data);
    }

    public function contact_us() {
        return view('contact_us', array('title' => 'Welcome', 'description' => '', 'page' => 'contact_us'));
    }

    public function login() {
        return view('login', array('title' => 'Welcome', 'description' => '', 'page' => 'home'));
    }   

    public function checkout() {
        return view('checkout', array('title' => 'Welcome', 'description' => '', 'page' => 'home'));
    }

    public function search($query) {
        return view('products', array('title' => 'Welcome', 'description' => '', 'page' => 'products'));
    }

    public function cart() {
        //update/ add new item to cart
        if (Request::isMethod('post')) {
        $product_id = Request::get('product_id');
        $product = Product::find($product_id);
        Cart::add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $product->price));
    }

    if(Request::get('product_id') && Request::get('increment')==1){
        $rowId = Cart::search(array('id' => Request::get('product_id')));
        $item = Cart::get($rowId[0]);
        Cart::update($rowId[0], $item->qty + 1);
    }

    if(Request::get('product_id') && Request::get('decrease')==1){
        $rowId = Cart::search(array('id' => Request::get('product_id')));
        $item = Cart::get($rowId[0]);
        Cart::update($rowId[0], $item->qty - 1);
    }

    $cart = Cart::content();

    return view('cart', array('cart' => $cart, 'title' => 'Welcome', 'description' => '', 'page' => 'home'));
    }

    public function clear_cart() {
        Cart::destroy();
        return Redirect::away('cart');
    }

    public function register(){
        if(Request::isMethod('post')){
            User::create([
                'name' => Request::get('name'), 
                'email' => Request::get('email'),
                'password' => bcrypt(Request::get('password')),
                ]);
        }
        return Redirect::away('login');
    }

    public function authenticate() {
        if(Auth::attempt(['email' => Request::get('email'), 'password' => Request::get('password')])){
            return redirect()->intended('checkout');
        }else{
            return view('login', array('title' => 'Welcome', 'description' => '', 'page' => 'home'));
        }
    }

    public function logout(){
        Auth::logout();
        return Redirect::away('login');
    }

    public function test($id){
        $cat = DB::select('select * from categories');
        return Response::json(array('Category' => $cat));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
