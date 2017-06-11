angular.module('ProjetXModule').factory('ApiArticleFactory', ['$http', 'Routing', 'UtilsAjaxLoadingFactory', function ($http, Routing, UtilsAjaxLoadingFactory) {
    return {
        getAll: function () {
            return $http.get(Routing.generate('api_article_get_all'));
        }
    }
}]);