angular.module('core').filter('status', function () {
    return function (input) {
        return input == '1' ? 'Активировано' : 'Не активировано';
    };
});