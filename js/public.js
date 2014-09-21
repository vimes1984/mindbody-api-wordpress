/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   mind-body
 * @author    C.J.Churchill <churchill.c.j@gmail.com>
 * @license   GPL-2.0+
 * @link      http://accruesupport.com
 * @copyright 4-29-2014 Accrue
 */

/**
 * Here goes all the JS Code you need in your child theme buddy!
 */ 
 (function ($) {
    // here goes your custom code
}(jQuery));
var myApp = angular.module('ngAppDemo', ['ngAnimate', 'ui.calendar', 'ui.directives','ui.service', 'angularSpinner', 'iso.directives']  );
angular.module('ui.directives', []);
angular.module('ui.service', []);
angular.module('ui', ['ui.directives', 'ui.service', 'ngSanitize']).value('ui.config', {});

//main controller
myApp.controller('mainapp', function ($scope, $window, $document, $timeout) { });
//app controller
myApp.controller('ngAppDemoController', function ($scope) {
    $scope.arrayfull = jsonitfull;
});
myApp.controller('AccordionDemoCtrl', function ($scope) {
             
});
//call app controller
myApp.controller('calappwidget', function($scope, $http, $location, usSpinnerService){
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    $scope.uiConfig = {
        calendar:{
            editable: false,
            loading: function (isLoading, view) {
                    if(isLoading == true){
                        usSpinnerService.spin('spinner-1');
                    }else{
                        usSpinnerService.stop('spinner-1');
                    }
            },
            header:{
                left: 'prev',
                center: 'title',
                right: 'next'
            },
             eventClick: function(calEvent, jsEvent, view) {
                    //console.log(calEvent);
                    $scope.viewevent = calEvent;
                    $scope.showpopicker();
                    $scope.starttime = calEvent.start.format('hA');
                    $scope.endtime = calEvent.end.format('hA');
                    $scope.urllink = $location.$$protocol + '://'+ $location.$$host + '/product/' +calEvent.title.replace(/ /g,"-").toLowerCase();
                    //console.log($scope.urllink);
                    // change the border color just for fun

                }
        }
    };
    $scope.shippingother = false;
    $scope.showpopicker = function showpopicker(){
        $scope.eventinfoshwhd = false;
    };
    $scope.closethis = function closethis(){
        $scope.eventinfoshwhd = true;
    }
    $scope.eventinfoshwhd = true;
    
    
    $scope.dataurl = $location.$$protocol+'://'+$location.$$host+'/wp-content/plugins/mindbody/jsonclasses.php';
            $scope.method = 'GET';
            $http({method: $scope.method, url: 'http://dancenergy.zenutech.com/wp-content/plugins/mindbody/jsonclasses.php'}).
                success(function(data, status) {
                    $scope.status = status;
                    $scope.data = data;
                    //console.log(data);
            }).
            error(function(data, status) {
              $scope.data = data || "Request failed";
              $scope.status = status;
    });
  $scope.eventSource = {
       // your event source
            url: $scope.dataurl,
            type: 'GET',
            success: function(data){
                //console.log(data);

                $scope.repeatdata = data;
                $scope.calmoment = $scope.myCalendar.fullCalendar('getDate').format('l');
            },
            error: function() {
                alert('No classes found for that date');
            },
            textColor: '#fefcf0' // a non-ajax option
                 // an option!
    };
});
//cal app widget
myApp.controller('calappwidgetside', function($scope, $http, $location, usSpinnerService, $sce){
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    $scope.repeatdata = [];
    $scope.letterLimit = 3;
    $scope.viewevent = {};
    $scope.uiConfig = {
        calendar:{
            editable: false,
            loading: function (isLoading, view) {
                    //console.log();
                    if(isLoading == true){
                    $scope.repeatdata = [];
                        usSpinnerService.spin('spinner-1');
                    }else{
                        usSpinnerService.stop('spinner-1');
                    }
            },
            defaultView: 'basicDay',
            header:{
                left: 'prev',
                center: 'title',
                right: ' next'
            },
            theme: false,
            dayClick: function(calEvent, jsEvent, view) {
                alert('Event: ' + calEvent.title);
                alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                alert('View: ' + view.name);
            }, 
             eventClick: function(calEvent, jsEvent, view) {
                    //console.log(calEvent);
                    $scope.viewevent = calEvent;
                    $scope.showpopicker();
                    $scope.starttime = calEvent.start.format('hA');
                    $scope.endtime = calEvent.end.format('hA');
                    $scope.urllink = $location.$$protocol + '://'+ $location.$$host + '/product/' +calEvent.title.replace(/ /g,"-").toLowerCase();
                    // console.log($scope.urllink);
                    // change the border color just for fun

                }
        }
    };
    $scope.shippingother = false;
    $scope.showpopicker = function showpopicker(){
        $scope.eventinfoshwhd = false;
    };
    $scope.closethis = function closethis(){
        $scope.eventinfoshwhd = true;
    }
    $scope.eventinfoshwhd = true;
    


    $scope.dataurl = $location.$$protocol+'://'+$location.$$host+'/wp-content/plugins/mindbody/jsonclasses.php';
    $scope.eventSource = {
       // your event source
            url: $scope.dataurl,
            type: 'GET',
            success: function(data){
                // console.log(data);

                $scope.repeatdata = data;
                $scope.calmoment = $scope.myCalendar.fullCalendar('getDate').format('l');
            },
            error: function() {
                alert('No classes found for that date');
            },
            textColor: '#fefcf0' // a non-ajax option
                 // an option!
    };
});
myApp.controller('shoppage', function($scope, $http, usSpinnerService, getshop, getcatsparent, getcatschild , childcatsfilterService){



                $scope.parentclass = '';
                $scope.childtoshow = 0;
                $scope.childchildtoshow = 0;


                $scope.parentclicked = function parentclicked(clicked){
                    $scope.childtoshow = clicked.term_id;
                    $scope.parentclass = clicked.slug;
                };
                $scope.childclicked = function childclicked(clicked){
                    $scope.childchildtoshow = clicked.term_id;
                    $scope.parentchildclass = clicked.slug;
                };
                $scope.$watch('categorieschild', function(){

                });
                $scope.shuffle = function(){
                    $scope.$emit('iso-method', {name:'shuffle', params:null});
                };

                $scope.loader = usSpinnerService.spin('spinner-1');
                //child cats
                getcatschild.getcatschild()
                .success(function(data, status, headers, config){
                        
                    usSpinnerService.stop('spinner-1');

                    $scope.categorieschild = data;
                })
                .error(function(data, status, headers, config) {
                        usSpinnerService.stop('spinner-1');
                });
                //Parent Cat
                getcatsparent.getcatsparent()
                .success(function(data, status, headers, config){
                    $scope.categories = data;
                    usSpinnerService.stop('spinner-1');
                })
                .error(function(data, status, headers, config) {
                        usSpinnerService.stop('spinner-1');
                });
                //Shop loop
                getshop.getshop()
                .success(function(data, status, headers, config){
                    usSpinnerService.stop('spinner-1');
                    $scope.shoploop = data;
                    //console.log(data);             
                })
                .error(function(data, status, headers, config) {
                    usSpinnerService.stop('spinner-1');
                });
                // I am the callback handler for the ngRepeat completion.
                $scope.doSomething = function( index ) {

                };
});
myApp.factory('getshop', function($http){
    return {
        getshop: function() {
            return  $http({
                        method: 'POST', 
                        url: '/wp-admin/admin-ajax.php',
                        params: { 'action': 'shop_page_loop'}
                    });
        }
    };
});
myApp.factory('childcatsfilterService', ['$filter', function($filter) {
  return function(data) {
    return $filter('childcatsfilter')(data);
  };
}]);
myApp.directive(
            "repeatComplete",
            function( $rootScope ) {
 
                // Because we can have multiple ng-repeat directives in
                // the same container, we need a way to differentiate
                // the different sets of elements. We'll add a unique ID
                // to each set.
                var uuid = 0;
 
 
                // I compile the DOM node before it is linked by the
                // ng-repeat directive.
                function compile( tElement, tAttributes ) {
 
                    // Get the unique ID that we'll be using for this
                    // particular instance of the directive.
                    var id = ++uuid;
 
                    // Add the unique ID so we know how to query for
                    // DOM elements during the digests.
                    tElement.attr( "repeat-complete-id", id );
 
                    // Since this directive doesn't have a linking phase,
                    // remove it from the DOM node.
                    tElement.removeAttr( "repeat-complete" );
 
                    // Keep track of the expression we're going to
                    // invoke once the ng-repeat has finished
                    // rendering.
                    var completeExpression = tAttributes.repeatComplete;
 
                    // Get the element that contains the list. We'll
                    // use this element as the launch point for our
                    // DOM search query.
                    var parent = tElement.parent();
 
                    // Get the scope associated with the parent - we
                    // want to get as close to the ngRepeat so that our
                    // watcher will automatically unbind as soon as the
                    // parent scope is destroyed.
                    var parentScope = ( parent.scope() || $rootScope );
 
                    // Since we are outside of the ng-repeat directive,
                    // we'll have to check the state of the DOM during
                    // each $digest phase; BUT, we only need to do this
                    // once, so save a referene to the un-watcher.
                    var unbindWatcher = parentScope.$watch(
                        function() {
  
                            // Now that we're in a digest, check to see
                            // if there are any ngRepeat items being
                            // rendered. Since we want to know when the
                            // list has completed, we only need the last
                            // one we can find.
                            var lastItem = parent.children( "*[ repeat-complete-id = '" + id + "' ]:last" );
 
                            // If no items have been rendered yet, stop.
                            if ( ! lastItem.length ) {
 
                                return;
 
                            }
 
                            // Get the local ng-repeat scope for the item.
                            var itemScope = lastItem.scope();
 
                            // If the item is the "last" item as defined
                            // by the ng-repeat directive, then we know
                            // that the ng-repeat directive has finished
                            // rendering its list (for the first time).
                            if ( itemScope.$last ) {
 
                                // Stop watching for changes - we only
                                // care about the first complete rendering.
                                unbindWatcher();
 
                                // Invoke the callback.
                                itemScope.$eval( completeExpression );
 
                            }
 
                        }
                    );
 
                }
 
                // Return the directive configuration. It's important
                // that this compiles before the ngRepeat directive
                // compiles the DOM node.
                return({
                    compile: compile,
                    priority: 1001,
                    restrict: "A"
                });
 
            }
);
myApp.factory('getcatsparent', function($http) {
    return {
        getcatsparent: function(){
            return  $http({
                        method: 'GET', 
                        url: '/wp-admin/admin-ajax.php',
                        params: { 'action': 'cats_loop'}
                    });
        }

    };
});
myApp.factory('getcatschild', function($http) {
    return {
        getcatschild: function(){
            return  $http({
                        method: 'GET', 
                        url: '/wp-admin/admin-ajax.php',
                        params: { 'action': 'cats_child_loop'}
                    }); 
        }

    };
});
myApp.factory('getcatsparent', function($http) {
    return {
        getcatsparent: function(){
            return  $http({
                        method: 'GET', 
                        url: '/wp-admin/admin-ajax.php',
                        params: { 'action': 'cats_loop'}
                    }); 
        }

    };
});
myApp.filter('childcatsfilter', function() {
  
  return function(input,childtoshow) {
    
    var returnobj = {};

    angular.forEach(input, function(value, key){
        
        if(value.parent == childtoshow){
            returnobj[key] = value;
        }

    });


                     
     //$emit(numbers[count]);

    return returnobj;
  
  };
});
myApp.filter("as", function($parse) {
  return function(value, path) {
    return $parse(path).assign(this, value);
  };
});
myApp.config(function ($httpProvider) {
  $httpProvider.responseInterceptors.push('myHttpInterceptor');

  var spinnerFunction = function spinnerFunction(data, headersGetter) {
   // $("#spinner").show();
    return data;
  };

  $httpProvider.defaults.transformRequest.push(spinnerFunction);
});
myApp.factory('myHttpInterceptor', function ($q, $window, usSpinnerService) {
  return function (promise) {
    usSpinnerService.spin('spinner-1');
    return promise.then(function (response) {
        

        return response;
    
    }, function (response) {
    
        usSpinnerService.stop('spinner-1');  
        return $q.reject(response);
    
    });
  };
});
myApp.filter('getClasses', function() {
    return function (repeatdata, calmoment) {
        var filtered_list = [];
        if(typeof repeatdata != 'undefined'){

            for (var i = 0; i < repeatdata.length; i++) {
                
                var classesdate = new Date(repeatdata[i].start);
                var month_class = classesdate.getMonth()+1;
                var day_class = classesdate.getDate();
                var year_class = classesdate.getFullYear();
                var newdate_class =  month_class + "/" + day_class + "/" + year_class ;
                if (newdate_class == calmoment) {
                    filtered_list.push(repeatdata[i]);
                }
            }

        }
        return filtered_list;
    }
});
myApp.filter('unsafe', function($sce) {
    return function(val) {
        return $sce.trustAsHtml(val);
    };
});
myApp.directive('form', ['$location', function($location) {
    return {
        restrict:'E',
        priority: 999,
        compile: function() {
            return {
                pre: function(scope, element, attrs){
                    if (attrs.noaction === '') return;
                    if (attrs.action === undefined || attrs.action === ''){
                        attrs.action = $location.absUrl();

                    }                  
                }
            }
        }
    }
}]);
angular.module('ui.service', []).service('mindbodyjsonit', [function () {
}]);
myApp.directive("btstAccordion", function () {
    return {
        restrict: "E",
        transclude: true,
        replace: true,
        scope: {},
        template:
            "<div class='accordion' ng-transclude></div>",
        link: function (scope, element, attrs) {

            // give this element a unique id
            var id = element.attr("id");
            if (!id) {
                id = "btst-acc" + scope.$id;
                element.attr("id", id);
            }

            // set data-parent on accordion-toggle elements
            var arr = element.find(".accordion-toggle");
            for (var i = 0; i < arr.length; i++) {
                jQuery(arr[i]).attr("data-parent", "#" + id);
                jQuery(arr[i]).attr("href", "#" + id + "collapse" + i);
            }
            arr = element.find(".accordion-body");
            jQuery(arr[0]).addClass("in"); // expand first pane
            for (var i = 0; i < arr.length; i++) {
                jQuery(arr[i]).attr("id", id + "collapse" + i);
            }
        },
        controller: function () {}
    };
});
myApp.directive('btstPane', function () {
    return {
        require: "^btstAccordion",
        restrict: "E",
        transclude: true,
        replace: true,
        scope: {
            title: "@",
            category: "=",
            order: "="
        },
        template:
            "<div class='accordion-group' >" +
            "  <div class='accordion-heading'>" +
            "    <a class='accordion-toggle' data-toggle='collapse'> {{category.name}} - </a>" +
       
            "  </div>" +
            "<div class='accordion-body collapse'>" +
            "  <div class='accordion-inner' ng-transclude></div>" +
            "  </div>" +
            "</div>",
        link: function (scope, element, attrs) {
            scope.$watch("title", function () {
                // NOTE: this requires jQuery (jQLite won't do html)
                var hdr = element.find(".accordion-toggle");
                hdr.html(scope.title);
            });
        }
    };
})