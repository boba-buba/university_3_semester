# JavaScript and Asynchronous HTTP [Answers and Solutions]

**AJAX fundamentals:** [solution](labs/11-js-ajax/ajax-solution.html)

**Sending data over:** [JSON solution](labs/11-js-ajax/json-solution.html)

**Prototypes:**

Read the following code:

```
function Graph() {
  this.vertices = [];
  this.edges = [];
}
Graph.prototype = {
  addVertex: function(v){ this.vertices.push(v); }
};
```

And its utilization:

```
var g = new Graph();
g.addVertex(1);
```

1. New object is created, `Graph.prototype` is copied to its `[[prototype]]`, `Graph` function is called to initialize it.
2. When `g.addVertex(1);` is called, the prototype chain is searched, `addVertex` is found in the prototype.

What is the equivalent of:

- **Static method:** method placed as property of constructing function (e.g., `Graph.foo())
- **Virtual overridden method:** method of a same name set directly to object's property (e.g., `g.addVertex = function() {}`)
- **Abstract method:** no such thing, but we can create a method that throws an error "I am not implemented"
- **Interface:** again, no such thing, interfaces are more conceptual than exact syntactic constructs in JS
- **Inheritance:** `function SuperGraph() {}; SuperGraph.prototype = new Graph()` (more in summer term)

Solution of the Java pseudocode exercise (inheritance): [ES5](labs/11-js-ajax/inheritance.js), [ES6](labs/11-js-ajax/inheritance-es6.js)
