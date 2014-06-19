#LightweightMVC

Lightweight MVC framework for PHP applications

##Configuration

The only configuration needed is for the database connection in the <code>config/Database.php</code>. There set the right values for your database. The default config is this:

<pre>
return array(
			"username" => "root",
			"password" => "root",
			"host" => "localhost",
			"database" => "framework"
		);
</pre>

The framework is currently compatible with only MySQL database.

##Models

All application's models should be located in the folder <code>app/models</code> and the name of the model file should be in format <code>ModelNameModel.php</code>. Here's an example of a model class:

<pre>
class Person extends BaseModel{
    protected $attribute_accessor("name", "gender", "address", "age");
}
</pre>

In this case the should be file <code>app/models/PersonModel.php</code>. The variable <code>$attribute_accessor</code> tells the base model, which of the model's attributes should be saved or edited in the database. In this case base model expects that there's a table <code>Person</code> in the database having fields <code>name</code>, <code>gender</code>, <code>address</code> and <code>age</code>. On top of that there should be a primary key <code>id</code> with <code>AUTO_INCREMENT</code> in the table <code>Person</code>.

###Methods and attributes

* <code>$attribute_accessor</code>, tells the base model, which of the model's attributes should be saved or edited in the database 
* <code>all()</code>, fetches all the objects from the database. Example: <pre>$persons = Person::all();</pre>.
* <code>find($id)</code>, fetches the object with the given <code>id</code> from the database. Example: <pre>$person = Person::find(1);</pre>
* <code>where($attributes)</code>, fetches all the objects from the database which match the given attributes. Example: <pre>
$males = Person::where(array("gender" => "male"));
</pre>
* <code>save()</code>, saves the given object to the database or updates its attributes if it's already in the database. Example: 
<pre> 
$person = new Person;
$person->name = "Kalle";
$person->gender = "Male";
$person->address = "Street 1";
$person->age = 21;
$person->save();
</pre>
* <code>destroy()</code>, removes the given object from the database. Example:
<pre>
$person = Person::find(1);
$person->destroy();
</pre>

##Controllers

All application's controllers should be located in the folder <code>app/controllers/</code> and the name of the controller file should be in format <code>ControllerNameController.php</code>. Here's an example of a controller class:

<pre>
class PersonController extends BaseController{
    public function index(){
        $persons = Person::all();
        $this->render("persons/index", array("persons" => $persons));
    }
    
    public function show(){
    	$person = Person::find($this->params["id"]);
    	$this->render("persons/show", array("person" => $person));
    }
    
    public function creation(){
    	$this->render("persons/creation_form");
    }
    
    public function create(){
    	$person = new Person;
    	$person->name = "Kalle";
		$person->gender = "Male";
		$person->address = "Street 1";
		$person->age = 21;
		$person->save();
	
		$this->redirect_to("/persons/" . $person->id);
    }
    
}
</pre>

## Routing

Next we wan't to assign certain routes to different controllers. The framework uses Fat-Free micro framework for its routes but with different interface. All application's routes can be found in the <code>config/Routes.php</code>. Here's an example how we could assign routes to the controller we created earlier:

<pre>
$router = new Router();
$router->get("/", "Person#index");
$router->get("/persons/@id", "Person#show");
$router->get("/persons/new", "Person#creation");
$router->post("/persons/new", "Person#create");
$router->route();
</pre>

So basicly just give router the route and the controller and its function to be called when the request is made. Notice that the format is <code>ControllerName#function</code>, so drop out the <code>Controller</code> ending.

## Methods and attributes

* <code>$params</code>, contains all the request parameters. For example if user has made an get request to <code>persons/9</code> (check the routes and controller we created earlier), the <code>$this->params["id"]</code> would contain the id 
of the person in question. Also a form which posts person's name would pass the <code>name</code> parameter to <code>$this->params["name"]</code> (check the routes and controller we created earlier)
* <code>redirect_to($route)</code>, redirects user to another route. Example: 
<pre>
$this->redirect_to("/");
</pre>
* <code>render($view, $data = null)</code>, renders the given view found in the <code>app/views/</code> and passes it the given data
* <code>set_session($key, $value)</code>, add a session key with the given value
* <code>get_session($key)</code>, return the value of the given session key
