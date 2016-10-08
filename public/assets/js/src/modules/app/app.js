var mainApp = angular.module('jaraja', ['ngRoute','ngCookies', 'ngStorage'])
	
    .config(function ($routeProvider, $locationProvider){
    //$locationProvider.html5Mode(true);
    $routeProvider
        .when("/", {templateUrl:"views/home.php"})
        .when("/registration", {templateUrl:"views/registration.html", controller: "RegCtrl"})
        .when("/login", {templateUrl:"views/login.html", controller: "LoginCtrl"})
        .when("/admin", {templateUrl:"views/student.html", controller: "StudentCtrl"})
        .otherwise({redirectTo: "/"});
       
    })

mainApp.run(function() {

 //alert($cookieStore.get('UserDetails');   
//alert('yessss in run');
    
})
mainApp.directive("passwordVerify", function() {
   return {
      require: "ngModel",
      scope: {
        passwordVerify: '='
      },
      link: function(scope, element, attrs, ctrl) {
        scope.$watch(function() {
            var combined;

            if (scope.passwordVerify || ctrl.$viewValue) {
               combined = scope.passwordVerify + '_' + ctrl.$viewValue; 
            }                    
            return combined;
        }, function(value) {
            if (value) {
                ctrl.$parsers.unshift(function(viewValue) {
                    var origin = scope.passwordVerify;
                    if (origin !== viewValue) {
                        ctrl.$setValidity("passwordVerify", false);
                        return undefined;
                    } else {
                        ctrl.$setValidity("passwordVerify", true);
                        return viewValue;
                    }
                });
            }
        });
     }
   };
});

          
mainApp.factory('UserService', ['$cookieStore','$location', function($cookieStore, $location) {
   
  return {
    isLogged: false,
    userDetails: '',
    //dirlocation:'http://collegemobile.net',
    dirlocation:'http://localhost/collegemobile', 
	//dirlocation:'http://192.168.8.110/collegemobile', 
  };
    
}]);

/*
mainApp.directive('fileModel', ['$parse', function ($parse) {
            return {
               restrict: 'A',
               link: function(scope, element, attrs) {
                  var model = $parse(attrs.fileModel);
                  var modelSetter = model.assign;
                  
                  element.bind('change', function(){
                     scope.$apply(function(){
                        modelSetter(scope, element[0].files[0]);
                     });
                  });
               }
            };
         }]);


mainApp.service('fileUpload', ['$http', function ($http) {
            this.uploadFileToUrl = function(file, uploadUrl){
               var fd = new FormData();
               fd.append('file', file);
            
               $http.post(uploadUrl, fd, {
                  transformRequest: angular.identity,
                  headers: {'Content-Type': undefined}
               })
            
               .success(function(){
               })
            
               .error(function(){
               });
            }
         }]);
 */        