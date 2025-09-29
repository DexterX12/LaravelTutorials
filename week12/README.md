# Week 12 - SOA Introduction
#### Activity 1:  Try to understand the previous code. Can you identify what changed versus the previous version?

The most noticeable change is the structure of the response. Both are returned as JSON, but the content and structure of the data is different. In the former approach, the whole model is retrieved from the DB and returned as a JSON document from a collection of rows; this method could be useful for simple use and testing APIs, but defining a well-specified (non ambigous) and documented structure is the basis for real-life useful APIs.

The latter approach defines a [Laravel Resource](https://laravel.com/docs/12.x/eloquent-resources#concept-overview). Citing the documentation, it mentions "A resource class represents a single model that needs to be transformed into a JSON structure". So, it serves as a layer to transform the structure of the model provided by the ORM.

#### Activity 2: Try to create a POST Api service, that collects the product name and product price and sends that information to a Laravel Api route (which stores the new product into the database).

The second approach of an API service with Laravel Resource is going to be used as the base.

Inside `App\Http\Controllers\Api\ProductApiControllerV2.php`, the next method is added:

```php
public  function  save(Request  $request): JsonResponse
{
	$productDataValidated  =  Product::validate($request->all());
	$product  =  new  ProductResource(Product::create($productDataValidated));

	return  response()->json($product, 200);
}
```

The product will first be validated, and check if it has the 2 required fields: `name` and `price`, then the product gets created using the validated data, and it is returned as a JSON document to the user. The reason the created product is returned is because of the HTTP specification about HTTP response codes, which are documented [here](https://datatracker.ietf.org/doc/html/rfc7231#section-6.3.1). Since the function is returning `200 OK`, a payload is needed, and that payload is the created product.

After that, product model will have to be modified in order to add validations. Inside `App\Models\Product`, the following changes are made:

```php
//...
protected  static  array  $rules  = [
	'name' => 'required|string|max:255',
	'price' => 'required',
];

//,,,

public  static  function  validate(array  $productData): array
{
	return  validator($productData,  static::$rules)->validate();
}
```

Finally, a `POST` route for saving products is created inside `routes\api.php`

```php
Route::post('/v2/products/save', 'App\Http\Controllers\Api\ProductApiControllerV2@save')->name('api.v2.product.save');
```


A test request can be made using [REST test test...](https://resttesttest.com/)
![Resttesttest](https://i.imgur.com/QEEFWjh.png)

The product is created and retrieved successfully.