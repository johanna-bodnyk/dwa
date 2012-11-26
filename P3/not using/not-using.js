/*----------------------------------
	jQuery autocomplete for unit field - DELETE - not worth the trouble
-----------------------------------*/
/* $(function() {
        var unit_list = [
            "teaspoon",
			"tablespoon",
			"ounce",
			"fluid ounce",
			"cup",
			"pint",
			"quart",
			"gallon"
        ];
        $( "#unit" ).autocomplete({
            source: unit_list
        });
    }); */

/*----------------------------------
	Convert decimal to fraction
-----------------------------------*/
// Code adapted from http://www.dynamicguru.com/javascript/javascript-function-to-convert-decimal-fraction/

// function d_to_f(number) {

	// var decimal=number.substr(number.lastIndexOf('.'));
	// var zeroes = decimal.length-1;
	// var den = Math.pow(10,zeroes);
	// var num=number*den;

	// for(i=2;num>den?i<num:i<den;i++) {
		// if(num%i==0 && den%i==0) {
			// den/=i;
			// num/=i;
			// i=1;
	  // }
	// }

	// var remainder=num%den;
	// var whole_number=(num-remainder)/den;
	// var fraction = remainder+"/"+den;
	// if (whole_number!==0) {
		// fraction = whole_number+" "+fraction;
	// }
	
	// return fraction;
// };


/*----------------------------------
	Convert fraction to decimal
-----------------------------------*/
// function f_to_d(number) {
	
	// var whole_number = number.substring(0,number.indexOf(' '));
	// var num = number.substring((number.indexOf(' ')+1),number.indexOf('/'));
	// var den = number.substring((number.indexOf('/')+1));
	// var decimal = Number(whole_number)+num/den;
	// return decimal;
	
// };