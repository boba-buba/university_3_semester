<?php
/******************************************************************************

                            Online PHP Interpreter.
                Code, Compile, Run and Debug php program online.
Write your code in this editor and press "Run" button to execute it.

*******************************************************************************/


class ShoppingCart implements Iterator, ArrayAccess{

  // TODO: YOUR CODE HERE 
    private $position = null;
    public $list = array();
    
    public function offsetSet($offset, $value): void {
        
        $this->list[$offset] =$value;
    }
    
    public function offsetExists($offset): bool
    {
        return isset($this->list[$offset]);
    }
    
    public function offsetUnset($offset): void
    {
        if (!isset($this->list[$offset])){
            return;
        }
        unset($this->list[$offset]);
    }
    
    public function &offsetGet($offset)
    {
        return $this->list[$offset];
    }
    public function current(){
        return $this->list[$this->position];
    }
    public function key() {
        return $this->position;
    }
    
    public function next() {
        next($this->list);
        $this->position = key($this->list);
    }
    public function rewind(): void {
        reset($this->list);
        $this ->position = key($this->list);
    }
    
    public function valid(){return isset($this->list[$this->position]);}
    
    public function add($name, $count) 
    {
        if (array_key_exists($name, $this->list))
        {
            $this->list["$name"] += $count;
        }
        else {
            
            $this->list["$name"] = $count;    
        }
        
    }
    
    public function remove($name)
    {
        unset($this->list[$name]);
    }
    
    
    public function __toString()
    {
        echo "Content:\n";
        $result = "";
        foreach ($this->list as $key => $value)
        {
            $result .= "  {$key} : {$value}\n";
        }
        return $result;
    }
    
}

// Exercise 1 

$cart = new ShoppingCart();
$cart->add('milk', 1);
$cart->add('bread', 1);
$cart->add('basil', 1);
$cart->add('milk', 1);
$cart->remove('basil');
//print($cart);


// Exercise 2
//print("Content:\n");
$cart['apple'] = 2;
++$cart['apple'];

/*foreach($cart as $name => $count) {
  print("  $name : $count\n");
}*/


//Exercise 3
interface Writer {
    public function write(ShoppingCart &$cart);
    
}

class JsonWriter implements Writer {
    public function write(ShoppingCart &$cart){
        $content = [];
        foreach($cart as $key => $value)
        {
            $content[$key] = $value;
        }
        return json_encode($content);
    }
}

class StringWriter implements Writer {
    public function write(ShoppingCart &$cart){
        
        
        return (string)$cart;
        
    }
}

function createWriter($name) : Writer{
    $className = ucfirst($name)."Writer";
    return new $className();
}

print("JSON\n");
print(createWriter("json")->write($cart));

print("\n\nSTRING\n");
print(createWriter("string")->write($cart));

print("\n");