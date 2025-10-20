# Week 3 - MVC Introduction
#### Activity 1: Take a look of the previous routes. Do you have any comment?

The "about" behavior is set inside the routing file within a callback function, instead of having a separate method inside the corresponding controller class for this view (which, apparently, is the recommended pattern).

#### Activity 2: Create a “/contact” section in which you display the application email, address, and phone number.
Following the MVC pattern, a view with the HTML structure for the contact section has to be created, along with its controller for any data-related process, and lastly, an appropiate route must be defined.

So, for the controller, one could at this to HomeController:
```php
public  function  contact(): View
{
	$viewData  = [];
	$pageTitle  =  "Contact Information - Online Store";
	$pageSubTitle  =  "Contact Information";
	$contactInfo  = [

	"email" => "dex@fake.com",

	"phone" => "(123) 456-7890"

	];
	$viewData["title"] =  $pageTitle;

	$viewData["subtitle"] =  $pageSubTitle;

	$viewData["contact"] =  $contactInfo;

	return  view('home.contact',  ["viewData"  =>  $viewData]);
}
```

Then, creating a blade-like file for the contact page can go like this:

```php
@extends('layouts.app')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')
<div  class="container">
	<div  class="row">
		<div  class="col-lg-4 ms-auto">
			<p  class="lead">{{ $viewData["contact"]["email"] }}</p>
		</div>
		<div  class="col-lg-4 me-auto">
			<p  class="lead">{{ $viewData["contact"]["phone"] }}</p>
		</div>
	</div>
</div>
@endsection
```
Finally, just add the route:
```php
Route::get('/contact', 'App\Http\Controllers\HomeController@contact')->name("home.contact");
```

And there we go!

![New contact page](https://i.imgur.com/vDk0ytY.png)

#### Activity 3: Add the (“/products”) route as a new menu option (in the header navbar).
Based on our previously named view `product.index`, we only need to create a new anchor item inside the layout template with an href that allows routing our request to the specified view.

```html
...
<div  class="navbar-nav ms-auto">
	<a  class="nav-link active"  href="{{ route('home.index') }}">Home</a>
	<a  class="nav-link active"  href="{{ route('home.about') }}">About</a>
	<a  class="nav-link active"  href="{{ route('product.index') }}">Contact</a>
</div>
...
```

#### Activity 4: Add prices for each product and display the information in the product.show view.

Inside the `ProductController` controller we previously defined an array of associative arrays of products. So, we just need to create a new property for each of them:
```php
public  static  $products  = [
	["id" => "1", "name" => "TV", "description" => "Best TV", "price" => 100],

	["id" => "2", "name" => "iPhone", "description" => "Best iPhone", "price" => 120],

	["id" => "3", "name" => "Chromecast", "description" => "Best Chromecast", "price" => 60],

	["id" => "4", "name" => "Glasses", "description" => "Best Glasses", "price" => 90]
];
```

Finally, inside `show.blade.php`  add this line:
```php
...
<div  class="card-body">
	<h5  class="card-title">
	{{ $viewData["product"]["name"] }}
	</h5>
	<p  class="card-text">{{ $viewData["product"]["description"] }}</p>
	<!--this line--><p  class="card-text"><b>Price:</b> {{ $viewData["product"]["price"] }}</p>
</div>
...
``` 

#### Activity 5: Modify the show method. If the product number entered by the URL is not valid, redirect the user to the home page (“home.index”) route.

Before adding the condition, we need to include `RedirectResponse` inside `ProductController` controller, and then modify the `show` function to either return a View or a RedirectResponse `View | RedirectResponse`

Based on the products' context, an invalid ID is either:

 - not numeric
 - greater than the total of items
 - less than 1

So, we add this code at the top of the `show` function:
```php
...
public  function  show(string  $id): View | RedirectResponse
{
	if (!is_numeric($id) ||  $id  <  1  ||  $id  >  count(ProductController::$products))
		return  redirect()->route("home.index");
...
```
#### Activity 6: Add a conditional in the “product.show” view. If the price of a product is greater than 100, display the product name in red

Inside the `products.show` view, we can add the following code:
```php
@if ($viewData["product"]["price"] > 100)
	<p  class="card-text"><b>Price:</b> <span  style="color: red">{{ $viewData["product"]["price"] }}</span></p>
@else
	<p  class="card-text"><b>Price:</b> {{ $viewData["product"]["price"] }}</p>
@endif
```

Creating a simple condition that displays the price of the products, and if it's greater than 100, then we add a span tag with a style property with the color red.

#### Activity 7: Try to understand the previous code. Add a new product but leave the name empty (and click send). Then, leave the price empty. Then, enter the two fields.

The `save` function uses the `validate` function, which returns a `ValidationException` if any of the restrictions do not pass, and returns the error to the user via the original request. If it passes, it will use the function `dd` (Dump and die), which returns a debug-like screen with the data being passed as argument.

#### Activity 8: Modify the previous code to only allow numbers greater than zero for the prices. DO NOT USE ifs
Inside the validation function, we can add multiple validations, separated by the `|` character. Given the context, we need to add the `gt:` validation, which imposes that the field should be greater than a given value, which is 0 in this case.

The `save` function in `ProductController` should look like this:

```php
public  function  save(Request  $request)
{
	$request->validate([
		"name" => "required",
		"price" => "required|gt:0"
	]);
	dd($request->all());
	//here will be the code to call the model and save it to the database

}
```

![gt: validation](https://i.imgur.com/NC8U9m8.png)

#### Activity 9: If the info entered by the form is valid. Then display a message saying, “Product created”

First, we create a new view. We will call it `success`. So, inside `success.blade.php`, we put this:

```php
@extends('layouts.app')
@section('title', $title)
@section('content')

<div  class="container">
	<div  class="row">
		<div  class="card">
			<div  class="card-body">
				<ul  id="success"  class="alert alert-success">Product created successfully!</ul>
			</div>
		</div>
	</div>
</div>

@endsection
```

Then, we edit the `save` function inside `ProductController` for the app to be able to show the created view.

```php
public  function  save(Request  $request)
{
$request->validate([
		"name" => "required",
		"price" => "required|gt:0"
	]);
	$title  =  "Create product";
	//here will be the code to call the model and save it to the database
	return  view("product.success")->with("title", $title);
}
```

![SUCCESS](https://i.imgur.com/fLyWSLy.png)

#### Activity 10: Add a new menu option in the header (app layout), that links to the “/products/create” page.

Like we did before, just add a new anchor element inside the navbar. The anchor element to add is this one:
```html
<a  class="nav-link active"  href="{{ route('product.create') }}">Create Product</a>
```