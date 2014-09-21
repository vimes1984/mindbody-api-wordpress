/**
 * This is the main javascript file for the Mind Body plugin's main administration view.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end administrator.
 *
 * @package   mind-body
 * @author    C.J.Churchill <churchill.c.j@gmail.com>
 * @license   GPL-2.0+
 * @link      http://accruesupport.com
 * @copyright 4-29-2014 Accrue
 */

(function ($) {
	"use strict";
	$(function () {
		// Place your administration-specific JavaScript here
    	jQuery( "#tabs" ).tabs();
	});
}(jQuery));

var adminapp = angular.module('ngappadmin', ['ngAnimate']);

adminapp.controller('ngappcontroller', function($scope) {
	if(typeof ARRAYFULL != 'undefined'){
		$scope.arrayfull = ARRAYFULL;
	}
	$scope.testclick = function testclick(argument) {
		//hide admin notice
		angular.element('#hidenotice').attr('value', '1');
	}

});