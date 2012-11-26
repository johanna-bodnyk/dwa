//Fraction-0.2.js library was found online at http://hypervolu.me/~erik/fraction.js/, slightly modified (lines 210-214) to fix repeating decimal issues with division

$(document).ready(function() {
	
	var scale;
	var scale_fraction = new Fraction(1);
	var amt_entered;
	var amt_fraction;
	var unit_entered;
	
	//When user types in the amount field
	$('input[name=amount]').keyup(function() {
		amt_entered = $(this).val();
		
		//Create Fraction object using amount entered
		if (amt_entered.indexOf('/')!=-1) {
			amt_fraction = fractionFromString(amt_entered);
		}
		else {
			amt_fraction = new Fraction(Number(amt_entered));
		}
		
		console.log("Amount entered as fraction is "+amt_fraction.numerator+" / "+amt_fraction.denominator);
		
		//Check whether a valid amount was entered. If not, display error, reset amount variable, and clear results.
		if (amt_fraction.numerator != amt_fraction.numerator  || amt_fraction.numerator <= 0) {
			$('#amount-div .error').css('display','block');
			amt_entered = "";
			$('#results').hide();
		}
		else {
			$('#amount-div .error').hide();
			displayResults();
		}
	});
	
	//When user types in the unit field
	$('input[name=unit]').keyup(function() {
		unit_entered = $(this).val();
		
		//Replace unit entered with standardized unit name (or "" if unit is not found in list of synonyms)
		unit_entered = standardizeUnitName(unit_entered);	
		
		//Check whether a valid unit was entered. If not, display error and clear results
		if (unit_entered == "") {
			$('#unit-div .error').css('display','block');
			$('#results').hide();
		}
		else {
			displayResults();
			$('#unit-div .error').hide();
		}
	});
	
	//When user types in the scale field
	$('input[name=scale]').keyup(function() {
		scale = $(this).val();
		//Create Fraction object using scale entered (or reset to 1 if scale was deleted)
		if (scale == "") {
			scale_fraction = new Fraction(1);
		}
		else if (scale.indexOf('/')!=-1) {
			scale_fraction = fractionFromString(scale);
		}
		else {
			scale_fraction = new Fraction(Number(scale));
		}
		//If scale fraction does not validate, display error and reset to scale to 1
		if (scale_fraction.numerator != scale_fraction.numerator || scale_fraction.numerator <= 0) {
			$('#scale-div .error').css('display','block');
			scale_fraction = new Fraction(1);
		}
		else {
			$('#scale-div .error').hide();
		}
		displayResults();
	});
	
	function displayResults() {
		if (amt_entered && unit_entered) {
			
			//Multiply amount entered by scale entered (as fractions) to get scaled amount entered
			var scaled_amt_fraction = amt_fraction.multiply(scale_fraction);
			
			//Create Fraction object with appropriate multiplier for conversion to teaspoons (based on unit name entered)
			var conversion_fraction = new Fraction(units[unit_entered]);
			
			//Multiply scaled amount entered by conversion amount (as fractions) to get amount entered in teaspoons
			var amt_in_tsps = scaled_amt_fraction.multiply(conversion_fraction);
			
			//Call function to find best combination of common cooking measurements for scaled amount entered and display results in #best-answer div 	
			$('#best-answer').html("<p>"+findBestAnswer(amt_in_tsps)+"</p>");
			
			//Call function to convert amount entered to common units and display results in #conversions div
			$('#conversions').html(convert(amt_in_tsps));
			
			$('#results').show();
		}
	};

});

/*----------------------------------
	Conversions and synonyms
-----------------------------------*/

//Units and number of teaspoons in each unit
var units = [];
units['teaspoon'] = 1;
units['tablespoon'] = 3;
units['fluid ounce'] = 6;
units['cup'] = 48;
units['pint'] = 96;
units['quart'] = 192;
units['gallon'] = 768;

//Synonyms for units
var synonyms = [];
synonyms['teaspoon'] = ['teaspoon', 'tsp', 't'];
synonyms['tablespoon'] = ['tablespoon', 'tbsp', 'T'];
synonyms['fluid ounce'] = ['fluid ounce', 'ounce', 'oz'];
synonyms['cup'] = ['cup', 'cp', 'c'];	
synonyms['pint'] = ['pint', 'pt'];
synonyms['quart'] = ['quart', 'qt'];
synonyms['gallon'] = ['gallon', 'gal', 'g'];


/*------------------------------------------------------
	Convert fraction that was input as a string to a 
	fraction object by extracting numerator and denominator 
	to feed in seperately (to avoid errors with rounding
	and repeating decimals (this part of the fraction.js
	library does not seem to work as expected)).
-------------------------------------------------------*/
function fractionFromString(input) {
	
	//Parse input string into (optional) whole number, numerator, and denominator
	var whole_number = input.substring(0,input.indexOf(' '));
	var num = input.substring((input.indexOf(' ')+1),input.indexOf('/'));
	var den = input.substring((input.indexOf('/')+1));
	
	//Add whole number to numerator
	num = whole_number*den+(Number(num));
	
	//Create fraction using parsed numerator and denominator
	var fraction = new Fraction(Number(num),Number(den));
	return fraction;
	
};


/*----------------------------------
	Standardize unit name
-----------------------------------*/
function standardizeUnitName(unit_entered) {
	
	//Trim "s" or "." off unit if needed
	var last_letter = unit_entered.substring(unit_entered.length-1);
	if (last_letter == "s" || last_letter == ".") {
		unit_entered = unit_entered.substring(0,unit_entered.length-1);
	}

	//Unless unit entered is "T" or "t", convert to lowercase
	if (unit_entered != "T" && unit_entered != "t")
	{
		unit_entered = unit_entered.toLowerCase();
	}
	
	//If unit entered is not found in list of synonyms, "" will be returned
	var standard_unit = "";
	
	//Loop through synonyms list looking for unit entered in sub-array. If found, set value to current main array key
	for (i in synonyms) {
		if (synonyms[i].indexOf(unit_entered) != -1) {
			standard_unit = i;
		}
	}
	
	return standard_unit;
}

/*------------------------------------------
	Generate list of conversions and build
	HTML string for display
------------------------------------------*/
function convert(amt_in_tsps) {

	var conversion_list = "";

	for(i in units) {
		
		//Divide amount in teaspoons (as a fraction) by number of teaspoons in this unit (as a fraction) to get equivalent amount in this unit (as a fraction)
		var unit_in_tsps = new Fraction(units[i]);
		var amount = amt_in_tsps.divide(unit_in_tsps);
		
		//If amount is greater than 1, make the unit label plural
		var unit_name = i;
		if (amount.numerator > amount.denominator) {
			unit_name = unit_name+"s";
		}
		
		//Add converted amount to list of conversions as a string
		conversion_list += "<p>" + amount.toString() + " " + unit_name +"</p>";
	}
	
	return conversion_list;
};
	
/*-------------------------------------------------
	Find best combination of common cooking 
	measurements to add up to amount entered
---------------------------------------------------*/

function findBestAnswer(amt_in_tsps) {
	
	var best_answer = "";
	var remainder; 
	var cup_fraction;
	
	//Convert given amount fraction to a decimal for comparisons
	var decimal_amount = amt_in_tsps.numerator/amt_in_tsps.denominator;
	
	while (decimal_amount > 0) {
				
		//Find amount in cups, 1/3 cups, and/or 1/4 cups

		//(The if and else if here--conversion to cups and tablespoons--are repetitious, but since the code is only used twice it felt more readable to repeat it rather than abstract.)
		
		if (decimal_amount >= 12) {
		
			//Determine whether amount works better as 1/4 cups or 1/3 cups
			if (decimal_amount % 16 < decimal_amount % 12) {
				var cup_fraction = 16;
			}
			else {
				var cup_fraction = 12;
			}
		
			//Isolate the portion of the total that divides evenly into quarter or third cups and create a Fraction object of that portion
			remainder = decimal_amount % cup_fraction;
			decimal_amount -= remainder;
			
			//Okay to create a fraction from a decimal here since we should now have a whole number
			var cup_portion = new Fraction(decimal_amount);
			
			//Create Fraction object for conversion into cups
			var cup_conversion = new Fraction(1,48);
			
			//Convert portion that divides evenly into 1/4 or 1/3 cups into cups and add to string that will display best answer
			best_answer += cup_portion.multiply(cup_conversion).toString()+" cup";
			
			//If we have more than 1 cup, make label plural
			if (decimal_amount >= 60) {
				best_answer += "s";
			}
			//If there's anything left over, add a + to the string
			if (remainder != 0){
				best_answer += " +";
			}
			best_answer += "<br>";
			
			//Subtract portion now being displayed as cups from original fraction object holding amount
			amt_in_tsps = amt_in_tsps.subtract(cup_portion);
			
			//Update decimal amount using new amount in tsps fraction
			decimal_amount = amt_in_tsps.numerator/amt_in_tsps.denominator
		}
		
		//Find amount in tablespoons
		else if (decimal_amount >= 3) {
			
			//Isolate the portion of the total that divides evenly into tablespoons and create a Fraction object of that portionns
			remainder = decimal_amount % 3;
			decimal_amount -= remainder;
			var tb_portion = new Fraction(decimal_amount);
			
			//Fraction object for conversion into tablespoons
			var tb_conversion = new Fraction(1,3);
			
			//Convert portion that divides evenly into tablespoons into tablespoons and add to string that will display best answer
			best_answer += tb_portion.multiply(tb_conversion).toString()+" tablespoon";
			
			//If we have more than 1 tablespoon, make label plural
			if (decimal_amount >= 6) {
				best_answer += "s";
			}
			//If there's anything left over, add a + to the string
			if (remainder != 0){
				best_answer += " +";
			}
			best_answer += "<br>";		
			
			//Subtract portion now being displayed as tablespoons from fraction object holding current amount
			amt_in_tsps = amt_in_tsps.subtract(tb_portion);
			
			//Update decimal amount based on new amount in tsps fraction
			decimal_amount = amt_in_tsps.numerator/amt_in_tsps.denominator
		}
		
		//When fewer than 3 teaspoons remain, display whatever is left as teaspoons
		else {
			best_answer += amt_in_tsps.toString() + " teaspoon"
			
			//If we have more than 1 tablespoon, make label plural
			if (decimal_amount > 1) {
				best_answer += "s";
			}
			best_answer += "<br>";
			
			//Set comparison value to 0 to break loop
			decimal_amount = 0;
		}
	}
	
	return best_answer;
}


