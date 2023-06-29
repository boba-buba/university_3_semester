rects= [
	//{"left": 6, "top": 1, "width": 2, "height": 3},
	{ "left": 20, "top": 10, "width": 150, "height": 30 },
	{ "left": 500, "top": 50, "width": 40, "height": 300 },
	{ "left": 290, "top": 300, "width": 20, "height": 100 },
	{ "left": 50, "top": 270, "width": 80, "height": 80 }];


function largestRectangle(heights){
	let n = heights.length;
	let stack = Array();



	let maxArea = 0;
	let topOfStack = 0;
	let areaWithTop = 0;

	let i = 0; 
	while (i < n){
		if (stack.length == 0 || heights[stack.length-1] <= heights[i]){
			stack.push(i+1);
			i = i +1;
		}
		else {
			topOfStack = heights[stack[length-1]];
			stack.pop();
			areaWithTop = topOfStack* i;
			if (stack.length != 0){
				areaWithTop = topOfStack * (i - stack[length-1] -1);
				
			}
			maxArea = Math.max(maxArea, areaWithTop);
		}

		while (stack.length != 0){
			topOfStack=heights[stack.length-1];
			stack.pop();
			areaWithTop = topOfStack * i;
			if (stack.length != 0){
				areaWithTop = topOfStack*(i-stack[length-1]-1);
			}
			maxArea = Math.max(maxArea, areaWithTop);
		}
		return maxArea;
	}


}
function maxFreeRect(width, height, rects)
{
	/*
	 * Your code goes here ...
	 */


	let area = Array(height);
	let topLeft = Array(height);
	for (let i = 0; i < area.length; i++){
		area[i] =[];
		topLeft[i] = [];
	}

	for (let i = 0; i < area.length; i++){
		//let a = " ";
		for(let j = 0; j < width; j++){
			//a += i+"/"+j+" ";
			area[i][j] = 1;
		}
		//console.log(a);
	}

	for (let k = 0; k < rects.length; k++){
		let left = rects[k].left;
		let top = rects[k].top;

		for(let i = top; i < top + rects[k].width; i++){
			//let a = " ";
			for (let j = left; j < left + rects[k].height; j++){
				//a += i+"/"+j+" ";
				//console.log(i+" "+j);
				area[i][j] = 0;
			}
			//console.log(a);
		}
	}

	let maxArea = 0;
	let maxTop;
	let maxLeft;
	let maxWidth;
	let maxHeight;

	
	for (let i = 1; i < height; i++){
		for (let j = 0; j < width; j++){
			if (area[i][j] != 0){
			area[i][j] += area[i-1][j];
			}
		}
	}

	for (let i = 0; i < height; i++){
		let minimum = Number.MAX_SAFE_INTEGER;
		for (let j = 0; j < width; j++){
			if (area[i][j] != 0){
				let cell = [];
				

			}
		}
	}



	for (let i = 0; i < height; i++){
		let b = "";
		for (let j = 0; j < width; j++){
			b += area[i][j]+" ";
		}
		console.log(b);
	}

	const rect = {
		left: 100, top: 100, width: 100, height: 100
	}

	//return rect;
	return area;
}

let a = maxFreeRect(600, 400, rects);
let b = largestRectangle(a[399]);
console.log(b);

// In nodejs, this is the way how export is performed.
// In browser, module has to be a global varibale object.
//module.exports = { maxFreeRect };
