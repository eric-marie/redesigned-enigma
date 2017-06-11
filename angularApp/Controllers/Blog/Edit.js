angular.module('ProjetXModule').controller('BlogEditCtrl', ['$scope', 'ApiSecurityFactory', 'UtilsAjaxLoadingFactory', function ($scope, ApiSecurityFactory, UtilsAjaxLoadingFactory) {
    ApiSecurityFactory.redirectIfNotLogged(function() {
        $scope.isLoading = UtilsAjaxLoadingFactory.isLoading;

    });
}]);