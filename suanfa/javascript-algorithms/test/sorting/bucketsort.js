var sort = require('../../src/'+
'sorting/bucketsort').bucketSort;
console.log(sort([2, 5, 1, 0, 4])); // [ 0, 1, 2, 4, 5 ]