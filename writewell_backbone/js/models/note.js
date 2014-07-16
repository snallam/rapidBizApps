define([
  'backbone'
], function (Backbone) {
    var model = Backbone.Model.extend({
        urlRoot: null,
        defaults: {
            id: null,
            title: '',
            text: '',
            sections: null, //backbone collection
            unassigned: true,
            sectionIdAssigned: null
        },
        initialize: function () {
        },
        sync: function () {
            return false;
        },
        parse: function (resp) {
            if (resp.response === undefined) return resp;
            else return resp.response;

        },
        validate: function (attrs) {

            return null;
        }
    });
    return model;
});