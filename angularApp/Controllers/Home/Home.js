angular.module('ProjetXModule').controller('HomeHomeCtrl', ['$scope', 'UtilsAjaxLoadingFactory', function ($scope, UtilsAjaxLoadingFactory) {
    $scope.isLoading = UtilsAjaxLoadingFactory.isLoading;

}]);