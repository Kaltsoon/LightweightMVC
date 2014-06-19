#LightweightMVC

Lightweight MVC framework for PHP applications

##Models

All application's models should be located in the folder <code>app/models</code> and the name of the model file should be in format <code>ModelNameModel.php</code>. Here's an example of a model class:

<pre>
class Person extends BaseModel{
    protected $attribute_accessor("name", "gender", "address", "age");
}
</pre>

The variable <code>$attribute_accessor</code> tells the base model, which of the model's attributes should be saved or edited in the database. In this case base model expects that there's a table <code>Person</code> in the database having fields <code>name</code>, <code>gender</code>, <code>address</code> and <code>age</code>. 
