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

###Methods

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

All application's controllers should be located in the folder <code>app/controllers/<code> and the name of the controller file should be in format <code>ControllerNameController.php</code>. Here's an example of a controller class:

<pre>
class PersonController extends BaseController{
    public function index(){
        $persons = Person::all();
        $this->render("persons/index", array("persons" => $persons);
    }
    
    
}
</pre>
