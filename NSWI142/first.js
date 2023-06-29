// Implement functions manipulating a linked list
// An item in the linked list must be represented as an object
// with properties 'value' and 'rest' (see tests below)
// Example: {value:1, rest: {value:2, rest:null}}
// Empty list is 'null'.

/**
* Insert value at the beginning of the list
* (and return newly created list).
*/
function prepend(value, list) { 
    let newList = {value : value, rest : list};
    return newList;
}

/**
* Convert an array into a list (empty list is null).
*/

function arrayToList(array) {
    if (array.length == 0) {return null;}
    let r = arrayToList(array.slice(1));
    let newList = {value:array[0], rest: r};
    return newList;
 }

/**
* Get n-th value from given list. Return undefined
* if the item does not exist.
*/
function nth(list, n) { 
    if (list == null) {return undefined;}
    if (n == 0) {return list.value;}
    else{ return nth(list.rest, n-1);}
}

/**
* Convert a list into an array.
*/
function listToArray(list) {
    if (list == null) return [];
    let arr = [list.value];
    if (list.rest == null){
    return([list.value])
}

return arr.concat(listToArray(list.rest));
 }

/**
* Call callback with each item in the list.
*/
function forEach(list, callback) { 
    if (list.rest == null) {return callback(list.value);}
    return callback(list.value) + forEach(list.rest, callback);
}

// TESTS, DO NOT MODIFY !!!
function test(value, expected) {
  const valueStr = JSON.stringify(value);
  const expectedStr = JSON.stringify(expected);
  console.log(valueStr == expectedStr
      ? "Test OK"
      : "Test FAILED: expecting " + expectedStr + ", got " + valueStr);
}

const threeItemsList = {value:1, rest: {value:2, rest:{value: 3, rest: null}}};
test(prepend(1, {value: 2, rest: {value: 3, rest: null}}), threeItemsList);
test(arrayToList([1, 2, 3]), threeItemsList);
test(nth(threeItemsList, 1), 2);
test(listToArray(threeItemsList), [1, 2, 3]);

test(prepend(1, null), {value:1, rest:null});
test(listToArray(null), []);
test(nth(null, 1), undefined);

let sum = 0;
forEach(arrayToList([1,2,3,4]), function (item) { sum += item; });
test(10, sum);