angular.module('messagecatApp').config(['$locationProvider', '$routeProvider',
    function config($locationProvider, $routeProvider) {
        $locationProvider.hashPrefix('!');

        $routeProvider.when('/messages', {
            template: '<messages-list></messages-list>'
        }).when('/messages/:messageId', {
            template: '<message-detail></message-detail>'
        }).otherwise('/messages');
    }
]);